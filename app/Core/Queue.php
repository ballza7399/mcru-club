<?php
namespace App\Core;

/**
 * ระบบจัดคิวเข้าใช้งานเว็บไซต์เพื่อป้องกันเซิร์ฟเวอร์ล่ม (Virtual Waiting Room)
 * ทำงานบนไฟล์ซิสเต็มเพื่อหลีกเลี่ยงการเปิดเชื่อมต่อ Database ให้ประหยัดทรัพยากรมากที่สุด
 */
class Queue
{
    private const QUEUE_DIR = BASE_PATH . '/uploads/queue';
    private const MAX_ACTIVE_USERS = 150; // จำนวนผู้ใช้ที่อนุญาตให้เข้าใช้งานได้พร้อมกัน (ปรับลด/เพิ่มตามสมรรถนะโฮสต์)
    private const SESSION_TIMEOUT = 60;   // ระยะเวลาที่ถือว่าผู้ใช้ยังใช้งานอยู่ (วินาที) หากไม่มีกิจกรรมใน 1 นาทีคิวจะหมดอายุ

    public static function check(): void
    {
        // ข้ามการจัดคิวสำหรับหน้า Waiting Room, ข้อมูล API และ Static Assets เพื่อไม่ให้เกิด Loop
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (str_contains($uri, '/waiting-room') || str_contains($uri, '/api/') || str_contains($uri, '/assets/')) {
            return;
        }

        $status = self::getQueueStatus();
        if (!$status['can_enter']) {
            header('Location: ' . BASE_URL . '/waiting-room');
            exit;
        }
    }

    public static function getQueueStatus(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sessionId = session_id();
        if (!$sessionId) {
            return ['can_enter' => false, 'queue_position' => 0, 'total_waiting' => 0];
        }

        if (!is_dir(self::QUEUE_DIR)) {
            @mkdir(self::QUEUE_DIR, 0777, true);
        }

        $now = time();

        // รันสุ่ม 10% ลบประวัติคิวที่หมดอายุแล้วเพื่อระบายทรัพยากรดิสก์
        if (rand(1, 100) <= 10) {
            self::cleanExpired($now);
        }

        $activeFile = self::QUEUE_DIR . '/active_' . $sessionId;

        // 1. ถ้าเป็น Active User อยู่แล้ว ให้ผ่านได้เลย และ Touch เพื่อต่ออายุ
        if (file_exists($activeFile)) {
            @touch($activeFile);
            return ['can_enter' => true, 'queue_position' => 0, 'total_waiting' => 0];
        }

        // 2. ถ้ายังไม่แอคทีฟ หาไฟล์รอคิวของผู้ใช้นี้
        $waitingFilePattern = self::QUEUE_DIR . '/waiting_*_' . $sessionId;
        $myWaitingFiles = glob($waitingFilePattern);
        $myWaitingFile = !empty($myWaitingFiles) ? $myWaitingFiles[0] : null;

        if (!$myWaitingFile) {
            // ยังไม่มีคิวรอ ให้สร้างไฟล์รอคิวใหม่
            $myWaitingFile = self::QUEUE_DIR . '/waiting_' . $now . '_' . $sessionId;
            @file_put_contents($myWaitingFile, $now);
        } else {
            // มีไฟล์รอคิวอยู่แล้ว ให้ Touch เพื่อบอกว่ายังออนไลน์อยู่ (ป้องกันหมดอายุ)
            @touch($myWaitingFile);
        }

        // ดึงคิวรอทั้งหมดมาจัดลำดับ
        $waitingFiles = glob(self::QUEUE_DIR . '/waiting_*');
        
        // กรองเฉพาะไฟล์ที่เป็นไฟล์จริงๆ และจัดเรียงตามลำดับชื่อไฟล์ (เนื่องจากชื่อมี timestamp อยู่ข้างหน้า)
        $waitingQueue = [];
        if ($waitingFiles) {
            foreach ($waitingFiles as $file) {
                if (is_file($file)) {
                    $waitingQueue[] = $file;
                }
            }
        }
        sort($waitingQueue); // เรียงจากเวลาเก่าสุดไปใหม่สุด (FIFO)

        // หาตำแหน่งของเราในคิว
        $myPosition = 1;
        foreach ($waitingQueue as $index => $file) {
            if ($file === $myWaitingFile) {
                $myPosition = $index + 1;
                break;
            }
        }

        $totalWaiting = count($waitingQueue);

        // 3. ตรวจสอบจำนวนผู้ใช้งานระบบอยู่ (Active Users)
        $activeCount = self::getActiveCount($now);

        // ถ้าที่ว่างพอ และเราเป็นคิวแรกๆ 
        $availableSlots = self::MAX_ACTIVE_USERS - $activeCount;

        if ($availableSlots > 0 && $myPosition <= $availableSlots) {
            // จองคิว Active สำเร็จ
            @file_put_contents($activeFile, $now);
            // ลบไฟล์รอคิว
            if ($myWaitingFile && file_exists($myWaitingFile)) {
                @unlink($myWaitingFile);
            }
            return ['can_enter' => true, 'queue_position' => 0, 'total_waiting' => 0];
        }

        return [
            'can_enter' => false,
            'queue_position' => $myPosition,
            'total_waiting' => $totalWaiting
        ];
    }

    /** ลงทะเบียนเข้าใช้คิว (สำหรับหน้าเปลี่ยนผ่าน) */
    public static function registerAccess(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $sessionId = session_id();
        if ($sessionId) {
            $userFile = self::QUEUE_DIR . '/active_' . $sessionId;
            @file_put_contents($userFile, time());
        }
    }

    private static function cleanExpired(int $now): void
    {
        $files = glob(self::QUEUE_DIR . '/*');
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    $basename = basename($file);
                    $mtime = filemtime($file);
                    
                    if (str_starts_with($basename, 'active_')) {
                        if ($now - $mtime > self::SESSION_TIMEOUT) {
                            @unlink($file);
                        }
                    } elseif (str_starts_with($basename, 'waiting_')) {
                        // คิวรอหมดอายุหากไม่ได้เช็ก/อัปเดตเกิน 15 วินาที
                        if ($now - $mtime > 15) {
                            @unlink($file);
                        }
                    } else {
                        @unlink($file);
                    }
                }
            }
        }
    }

    private static function getActiveCount(int $now): int
    {
        $count = 0;
        $files = glob(self::QUEUE_DIR . '/active_*');
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
