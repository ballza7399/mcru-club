<?php
/**
 * Installer — เตรียมสภาพแวดล้อมเริ่มต้นของระบบ MVC
 *
 * หมายเหตุ: เวอร์ชันเดิมของไฟล์นี้ใช้ regenerate ไฟล์ PHP แบบ flat (db.php,
 * index.php, navbar.php ฯลฯ) ผ่าน base64 ซึ่งจะ "เขียนทับ" โครงสร้าง MVC ปัจจุบัน
 * จึงถูกแทนที่ด้วย installer แบบปลอดภัยที่ทำแค่:
 *   1) สร้างโฟลเดอร์ uploads/
 *   2) เตือนให้สร้าง config/config.php จาก config.example.php
 *   3) แสดงบัญชีทดสอบและลิงก์เข้าระบบ
 */

declare(strict_types=1);

$base = __DIR__;
$messages = [];

// 1) สร้างโฟลเดอร์ uploads/
$uploadDir = $base . '/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
    $messages[] = '✅ สร้างโฟลเดอร์ uploads/ แล้ว';
} else {
    $messages[] = 'ℹ️ มีโฟลเดอร์ uploads/ อยู่แล้ว';
}

// 2) ตรวจ config
$configExists = file_exists($base . '/config/config.php');
if ($configExists) {
    $messages[] = '✅ พบ config/config.php';
} else {
    $messages[] = '⚠️ ยังไม่มี config/config.php — คัดลอกจาก config/config.example.php แล้วใส่ค่าฐานข้อมูล';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ติดตั้งระบบ MCRU Club</title>
<style>body{font-family:sans-serif;max-width:640px;margin:50px auto;line-height:1.7;color:#2d3748;}
.box{background:#f4f7f6;padding:20px 25px;border-radius:8px;}
code{background:#e2e8f0;padding:2px 6px;border-radius:4px;}
.btn{padding:12px 25px;background:#0b2c5c;color:#fff;text-decoration:none;border-radius:8px;display:inline-block;font-weight:bold;margin-top:15px;}</style>
</head>
<body>
<h2 style="color:green;">ติดตั้งระบบ (โครงสร้าง MVC)</h2>
<ul>
<?php foreach ($messages as $m): ?>
    <li><?= htmlspecialchars($m, ENT_QUOTES, 'UTF-8') ?></li>
<?php endforeach; ?>
</ul>

<p>ขั้นตอนฐานข้อมูล: import ไฟล์ <code>database.sql</code> เข้า MySQL/phpMyAdmin</p>

<div class="box">
<b>บัญชีสำหรับทดสอบ:</b><br>
🛡️ แอดมิน: admin / admin123<br>
👑 ประธานชมรม: 660002 / 123456<br>
🎓 นักศึกษา: 660001 / 123456
</div>

<a class="btn" href="index.php">ไปที่หน้าเว็บ</a>
</body>
</html>
