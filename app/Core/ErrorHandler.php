<?php
namespace App\Core;

use Throwable;
use ErrorException;

/**
 * Error Handling Middleware — ดัก error ทุกชนิดในเว็บไว้ที่จุดเดียว
 *
 * ครอบคลุม:
 *  - PHP warning/notice/error ทั่วไป (แปลงเป็น ErrorException)
 *  - Exception ที่ไม่ถูก catch (รวมถึง PDOException จากฐานข้อมูล)
 *  - Fatal error ตอน shutdown (parse error, memory ฯลฯ)
 *
 * ทุกกรณีจะ log รายละเอียดฝั่ง server แล้วแสดงหน้า error
 * ที่เด้งด้วย SweetAlert2 ให้ผู้ใช้ (ไม่หลุดรายละเอียดเมื่อ debug=false)
 */
class ErrorHandler
{
    private static bool $debug = false;
    private static bool $handling = false; // กัน loop ถ้า handler เองพัง

    /** ติดตั้ง handler ทั้งหมด — เรียกครั้งเดียวตอน bootstrap */
    public static function register(bool $debug = false): void
    {
        self::$debug = $debug;

        error_reporting(E_ALL);
        ini_set('display_errors', '0'); // ไม่ให้ PHP พ่น error ดิบ เราจัดการเอง

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    /** แปลง PHP error (warning/notice/...) เป็น exception เพื่อไหลเข้า flow เดียวกัน */
    public static function handleError(int $severity, string $message, string $file, int $line): bool
    {
        // เคารพ @ operator และ error_reporting ที่ถูกปิด
        if (!(error_reporting() & $severity)) {
            return false;
        }
        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    /** จุดรวมแสดงผล exception ที่ไม่ถูก catch */
    public static function handleException(Throwable $e): void
    {
        // กัน recursion ถ้าการ render error เองดันเกิด error ซ้ำ
        if (self::$handling) {
            http_response_code(500);
            echo 'Fatal error.';
            return;
        }
        self::$handling = true;

        // log รายละเอียดฝั่ง server เสมอ (ไม่ว่า debug หรือไม่)
        error_log(sprintf(
            "[%s] %s in %s:%d\n%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        ));

        // เคลียร์ output ที่ค้างใน buffer (เผื่อ error เกิดกลางการ render view)
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if (!headers_sent()) {
            http_response_code(self::statusFor($e));
        }

        self::renderView($e);
    }

    /** ดัก fatal error ที่ handler ปกติจับไม่ได้ (เช่น E_ERROR, parse error) */
    public static function handleShutdown(): void
    {
        $err = error_get_last();
        if ($err === null) {
            return;
        }
        $fatal = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
        if (!in_array($err['type'], $fatal, true)) {
            return;
        }
        self::handleException(new ErrorException(
            $err['message'], 0, $err['type'], $err['file'], $err['line']
        ));
    }

    /** เลือก HTTP status จากชนิด exception */
    private static function statusFor(Throwable $e): int
    {
        $code = $e->getCode();
        // ถ้า code เป็น HTTP status ที่สมเหตุผล ใช้เลย ไม่งั้น 500
        if (is_int($code) && $code >= 400 && $code < 600) {
            return $code;
        }
        return 500;
    }

    /** render หน้า error (standalone — ไม่พึ่ง DB/layout เผื่อ DB ล่ม) */
    private static function renderView(Throwable $e): void
    {
        $debug   = self::$debug;
        $status  = self::statusFor($e);

        // ข้อความที่ปลอดภัยสำหรับผู้ใช้ทั่วไป
        $userMessage = match ($status) {
            403     => 'คุณไม่มีสิทธิ์เข้าถึงส่วนนี้',
            404     => 'ไม่พบหน้าที่คุณต้องการ',
            default => 'เกิดข้อผิดพลาดในระบบ กรุณาลองใหม่อีกครั้ง',
        };

        // รายละเอียดเชิงเทคนิคแบบเต็ม (เหมือน developer exception page ของ .NET)
        // แสดงเฉพาะตอน debug — มี exception chain (inner) + stack trace
        $detail = $debug ? self::buildDetail($e) : '';

        require BASE_PATH . '/app/Views/errors/error.php';
        exit;
    }

    /**
     * สร้างรายละเอียด exception แบบเต็มสำหรับ debug mode
     * ไล่ chain ของ inner exception (getPrevious) เหมือน .NET InnerException
     *
     * @return array{message:string, frames:array<int,array{class:string,message:string,location:string,trace:string}>}
     */
    private static function buildDetail(Throwable $e): array
    {
        $frames = [];
        $depth  = 0;

        // ไล่ตั้งแต่ exception นอกสุด ลงไปถึง inner สุด
        for ($cur = $e; $cur !== null; $cur = $cur->getPrevious()) {
            $frames[] = [
                'class'    => get_class($cur),
                'message'  => $cur->getMessage(),
                'location' => $cur->getFile() . ':' . $cur->getLine(),
                'trace'    => $cur->getTraceAsString(),
                'inner'    => $depth > 0,
            ];
            $depth++;
        }

        return [
            'message' => sprintf('%s: %s', get_class($e), $e->getMessage()),
            'frames'  => $frames,
        ];
    }
}
