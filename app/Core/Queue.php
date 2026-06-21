<?php
namespace App\Core;

/**
 * ระบบจัดคิวเข้าใช้งานเว็บไซต์เพื่อป้องกันเซิร์ฟเวอร์ล่ม (Virtual Waiting Room)
 * ทำงานบนไฟล์ซิสเต็มเพื่อหลีกเลี่ยงการเปิดเชื่อมต่อ Database ให้ประหยัดทรัพยากรมากที่สุด
 */
class Queue
{
    private const QUEUE_DIR = BASE_PATH . '/uploads/queue';
    private const MAX_ACTIVE_USERS = 1; // จำนวนผู้ใช้ที่อนุญาตให้เข้าใช้งานได้พร้อมกัน (ปรับลด/เพิ่มตามสมรรถนะโฮสต์)
    private const SESSION_TIMEOUT = 60;   // ระยะเวลาที่ถือว่าผู้ใช้ยังใช้งานอยู่ (วินาที) หากไม่มีกิจกรรมใน 1 นาทีคิวจะหมดอายุ

    public static function check(): void
    {
        // ข้ามการจัดคิวสำหรับหน้า Waiting Room, ข้อมูล API และ Static Assets เพื่อไม่ให้เกิด Loop
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (str_contains($uri, '/waiting-room') || str_contains($uri, '/api/') || str_contains($uri, '/assets/')) {
            return;
        }

        if (!self::canEnter()) {
            header('Location: ' . BASE_URL . '/waiting-room');
            exit;
        }
    }

    public static function canEnter(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sessionId = session_id();
        if (!$sessionId) {
            return false;
        }

        if (!is_dir(self::QUEUE_DIR)) {
            @mkdir(self::QUEUE_DIR, 0777, true);
        }

        $now = time();
        $userFile = self::QUEUE_DIR . '/' . $sessionId;

        if (rand(1, 100) <= 10) {
            self::cleanExpired($now);
        }

        $hasAccess = file_exists($userFile);

        if (!$hasAccess) {
            $activeCount = self::getActiveCount($now);
            if ($activeCount >= self::MAX_ACTIVE_USERS) {
                return false;
            }
            @file_put_contents($userFile, $now);
            return true;
        } else {
            @touch($userFile);
            return true;
        }
    }

    /** ลงทะเบียนเข้าใช้คิว (สำหรับหน้าเปลี่ยนผ่าน) */
    public static function registerAccess(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $sessionId = session_id();
        if ($sessionId) {
            $userFile = self::QUEUE_DIR . '/' . $sessionId;
            @file_put_contents($userFile, time());
        }
    }

    private static function cleanExpired(int $now): void
    {
        $files = glob(self::QUEUE_DIR . '/*');
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    $mtime = filemtime($file);
                    if ($now - $mtime > self::SESSION_TIMEOUT) {
                        @unlink($file);
                    }
                }
            }
        }
    }

    private static function getActiveCount(int $now): int
    {
        $count = 0;
        $files = glob(self::QUEUE_DIR . '/*');
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    $mtime = filemtime($file);
                    if ($now - $mtime <= self::SESSION_TIMEOUT) {
                        $count++;
                    } else {
                        @unlink($file);
                    }
                }
            }
        }
        return $count;
    }
}
