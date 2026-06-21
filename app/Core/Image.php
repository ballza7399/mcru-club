<?php
namespace App\Core;

/**
 * ฟังก์ชันกลางสำหรับจัดการรูปภาพที่อัปโหลด
 * - ย่อขนาด (คงสัดส่วน) เฉพาะเมื่อรูปใหญ่กว่าที่กำหนด เพื่อลดขนาดไฟล์
 * - บีบอัด (จูน quality) ตามชนิดไฟล์
 * - รองรับ jpeg / png / webp / gif (gif แปลงเป็น png เพื่อคงความโปร่งใส)
 *
 * ต้องเปิด GD extension (Docker: ติดตั้งใน Dockerfile, XAMPP: เปิด extension=gd)
 */
class Image
{
    // ค่าเริ่มต้น: กรอบสูงสุดและคุณภาพการบีบอัด
    private const MAX_WIDTH   = 1024;
    private const MAX_HEIGHT  = 1024;
    private const JPEG_QUALITY = 80;   // 0-100
    private const WEBP_QUALITY = 80;   // 0-100
    private const PNG_LEVEL     = 6;    // 0-9 (ยิ่งมากยิ่งบีบแน่น)

    /**
     * อัปโหลดไฟล์รูปจาก $_FILES[...] พร้อม resize แล้วเก็บใน uploads/
     *
     * @param array  $file      หนึ่งรายการจาก $_FILES (เช่น $_FILES['logo'])
     * @param string $prefix    คำนำหน้าชื่อไฟล์ เพื่อให้รู้ที่มา (เช่น 'logo')
     * @param int    $maxWidth  ความกว้างสูงสุด (px)
     * @param int    $maxHeight ความสูงสูงสุด (px)
     * @return string relative path เช่น "uploads/169..._logo.jpg" หรือ '' ถ้าไม่มีไฟล์/ไม่ใช่รูป
     */
    public static function uploadResized(
        array $file,
        string $prefix = 'img',
        int $maxWidth = self::MAX_WIDTH,
        int $maxHeight = self::MAX_HEIGHT
    ): string {
        // ไม่มีไฟล์ส่งมา หรืออัปโหลดไม่สำเร็จ
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return '';
        }

        $tmp = $file['tmp_name'];
        if (!is_uploaded_file($tmp)) {
            return '';
        }

        // ตรวจว่าเป็นรูปจริงและอ่าน mime จากเนื้อไฟล์ (ไม่เชื่อนามสกุล/ค่า client)
        $info = @getimagesize($tmp);
        if ($info === false) {
            return '';
        }

        // เช็คว่า GD extension ถูกติดตั้งและเปิดใช้งานหรือไม่
        if (!extension_loaded('gd')) {
            // หากไม่มี GD extension ให้ใช้โหมดทดแทน (Fallback) โดยย้ายไฟล์ที่อัปโหลดไปเก็บโดยตรง
            $dir = BASE_PATH . '/uploads/';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $origName = $file['name'] ?? '';
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                $ext = 'jpg';
            }
            if ($ext === 'jpeg') $ext = 'jpg';
            
            $name    = time() . '_' . $prefix . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $relPath = 'uploads/' . $name;
            $absPath = $dir . $name;
            
            if (@move_uploaded_file($tmp, $absPath)) {
                return $relPath;
            }
            return '';
        }

        [$srcW, $srcH] = $info;
        $mime = $info['mime'];

        $src = null;
        switch ($mime) {
            case 'image/jpeg':
                $src = @imagecreatefromjpeg($tmp);
                break;
            case 'image/png':
                $src = @imagecreatefrompng($tmp);
                break;
            case 'image/webp':
                $src = @imagecreatefromwebp($tmp);
                break;
            case 'image/gif':
                $src = @imagecreatefromgif($tmp);
                break;
        }

        if (!$src) {
            return '';
        }

        // คำนวณขนาดใหม่ คงสัดส่วน — scale = 1 หมายถึงไม่ขยายรูปที่เล็กกว่ากรอบ
        $scale = min($maxWidth / $srcW, $maxHeight / $srcH, 1);
        $dstW  = max(1, (int) round($srcW * $scale));
        $dstH  = max(1, (int) round($srcH * $scale));

        $dst = imagecreatetruecolor($dstW, $dstH);

        // คงความโปร่งใสสำหรับชนิดที่มี alpha
        $hasAlpha = in_array($mime, ['image/png', 'image/webp', 'image/gif'], true);
        if ($hasAlpha) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefilledrectangle($dst, 0, 0, $dstW, $dstH, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

        // เตรียมโฟลเดอร์ปลายทาง
        $dir = BASE_PATH . '/uploads/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // gif → png เพื่อคงความโปร่งใส, ที่เหลือคงชนิดเดิม
        $ext = 'png';
        switch ($mime) {
            case 'image/jpeg':
                $ext = 'jpg';
                break;
            case 'image/png':
            case 'image/gif':
                $ext = 'png';
                break;
            case 'image/webp':
                $ext = 'webp';
                break;
        }

        $name    = time() . '_' . $prefix . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $relPath = 'uploads/' . $name;
        $absPath = $dir . $name;

        $ok = false;
        switch ($ext) {
            case 'jpg':
                $ok = imagejpeg($dst, $absPath, self::JPEG_QUALITY);
                break;
            case 'png':
                $ok = imagepng($dst, $absPath, self::PNG_LEVEL);
                break;
            case 'webp':
                $ok = imagewebp($dst, $absPath, self::WEBP_QUALITY);
                break;
        }

        imagedestroy($src);
        imagedestroy($dst);

        return $ok ? $relPath : '';
    }
}

