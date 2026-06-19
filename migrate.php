<?php
/**
 * MCRU Club Database Migrator
 * รันไฟล์ SQL ต่าง ๆ ในโฟลเดอร์ migrations/ เรียงตามลำดับชื่อไฟล์แบบอัตโนมัติ
 * และบันทึกประวัติการรันเพื่อหลีกเลี่ยงการรันซ้ำซ้อน (ป้องกันข้อมูลสูญหาย)
 */

declare(strict_types=1);

session_start();
define('BASE_PATH', __DIR__);

$config = require BASE_PATH . '/config/config.php';

echo "<h2>MCRU Club Database Migrator</h2>";
echo "<p>กำลังเริ่มวิเคราะห์สถานะระบบฐานข้อมูล...</p>";

try {
    // 1. เชื่อมต่อเซิร์ฟเวอร์ MySQL
    $host = $config['db']['host'];
    $user = $config['db']['user'];
    $pass = $config['db']['pass'];
    $dbName = $config['db']['name'];
    $charset = $config['db']['charset'];

    $dsn = "mysql:host=$host;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✔ เชื่อมต่อฐานข้อมูลสำเร็จ...<br>";

    // 2. สร้าง Database หากยังไม่มี
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
    $pdo->exec("USE `$dbName`");
    echo "✔ ใช้ฐานข้อมูล `$dbName`<br>";

    // 3. ตรวจสอบหรือสร้างตาราง migrations เพื่อเก็บประวัติ
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `migrations` (
            `id` int NOT NULL AUTO_INCREMENT,
            `migration_name` varchar(255) NOT NULL UNIQUE,
            `run_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 4. สแกนโฟลเดอร์ migrations/ หาไฟล์ .sql ทั้งหมด
    $migrationsDir = BASE_PATH . '/migrations';
    if (!is_dir($migrationsDir)) {
        mkdir($migrationsDir, 0777, true);
        echo "ℹ สร้างโฟลเดอร์ migrations/ เรียบร้อยแล้ว (ยังไม่มีการเปลี่ยนแปลงใดๆ)<br>";
        exit;
    }

    $files = glob($migrationsDir . '/*.sql');
    if (empty($files)) {
        echo "ℹ ไม่พบไฟล์ Migration (.sql) ในโฟลเดอร์ migrations/<br>";
        exit;
    }

    // เรียงลำดับชื่อไฟล์ตามตัวอักษร
    sort($files);

    // ดึงประวัติไฟล์ที่เคยรันสำเร็จแล้ว
    $runMigrations = $pdo->query("SELECT migration_name FROM migrations")->fetchAll(PDO::FETCH_COLUMN);

    $executedCount = 0;

    // 5. รันแต่ละไฟล์ที่ยังไม่เคยรัน
    foreach ($files as $filePath) {
        $fileName = basename($filePath);
        
        if (in_array($fileName, $runMigrations, true)) {
            echo "<span style='color:gray;'>➜ $fileName (เคยรันไปแล้ว - ข้าม)</span><br>";
            continue;
        }

        echo "<b>➜ กำลังรัน $fileName...</b><br>";
        $sql = file_get_contents($filePath);
        
        try {
            $pdo->exec($sql);
            
            // บันทึกประวัติลงตาราง
            $stmt = $pdo->prepare("INSERT INTO migrations (migration_name) VALUES (?)");
            $stmt->execute([$fileName]);
            
            echo "<span style='color:green;'>✔ รัน $fileName สำเร็จ</span><br>";
            $executedCount++;
        } catch (Exception $e) {
            throw new Exception("เกิดข้อผิดพลาดขณะประมวลผลไฟล์ $fileName: " . $e->getMessage());
        }
    }

    // --- ตรวจสอบและแฮชรหัสผ่านที่เป็น plaintext ในตาราง users ทั้งหมด ---
    $stmtUsers = $pdo->query("SELECT id, password FROM users");
    $usersToUpdate = $stmtUsers->fetchAll();
    
    $hashCount = 0;
    $stmtUpdatePass = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    
    foreach ($usersToUpdate as $u) {
        $pass = $u['password'];
        // รหัสผ่านที่แฮชแล้วแบบ Bcrypt จะมีความยาว 60 ตัวอักษรและมักขึ้นต้นด้วย $2y$ (หรือ $2a$ / $2b$)
        $isHashed = (str_starts_with($pass, '$2y$') || str_starts_with($pass, '$2a$') || str_starts_with($pass, '$2b$')) && strlen($pass) === 60;
        
        if (!$isHashed) {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmtUpdatePass->execute([$hashed, $u['id']]);
            $hashCount++;
        }
    }
    if ($hashCount > 0) {
        echo "✔ ดำเนินการเข้ารหัสความปลอดภัยสำหรับรหัสผ่านบัญชีเดิมจำนวน $hashCount รายการเรียบร้อยแล้ว<br>";
    }

    echo "<hr>";
    if ($executedCount > 0) {
        echo "<h3 style='color:green;'>✔ ดำเนินการอัปเกรดฐานข้อมูล ($executedCount ไฟล์ใหม่) สำเร็จเรียบร้อยแล้ว!</h3>";
    } else {
        echo "<h3 style='color:blue;'>ℹ ฐานข้อมูลเป็นเวอร์ชันล่าสุดอยู่แล้ว ไม่พบสิ่งต้องอัปเดต</h3>";
    }
    
    echo "<p><a href='index.php'>กลับไปหน้าเว็บหลัก</a></p>";

} catch (Exception $e) {
    echo "<h3 style='color:red;'>❌ เกิดข้อผิดพลาดในการอัปเกรดฐานข้อมูล:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
