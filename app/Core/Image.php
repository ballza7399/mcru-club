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
        [$srcW, $srcH] = $info;
        $mime = $info['mime'];

        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($tmp),
            'image/png'  => @imagecreatefrompng($tmp),
            'image/webp' => @imagecreatefromwebp($tmp),
            'image/gif'  => @imagecreatefromgif($tmp),
            default      => null,
        };
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
        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'png',
        };

        $name    = time() . '_' . $prefix . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $relPath = 'uploads/' . $name;
        $absPath = $dir . $name;

        $ok = match ($ext) {
            'jpg'  => imagejpeg($dst, $absPath, self::JPEG_QUALITY),
            'png'  => imagepng($dst, $absPath, self::PNG_LEVEL),
            'webp' => imagewebp($dst, $absPath, self::WEBP_QUALITY),
        };

        imagedestroy($src);
        imagedestroy($dst);

        return $ok ? $relPath : '';
    }
}
