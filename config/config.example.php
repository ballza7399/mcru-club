<?php
// คัดลอกไฟล์นี้เป็น config/config.php แล้วใส่ค่าจริง (config.php ถูก gitignore)
//
// ค่าทั้งหมดอ่านจาก environment variable ก่อน (สำหรับ Docker)
// ถ้าไม่มี env จะใช้ค่า default ฝั่งขวา (สำหรับ XAMPP/local)
return [
    'db' => [
        'host'    => getenv('DB_HOST') ?: '127.0.0.1',
        'user'    => getenv('DB_USER') ?: 'root',
        'pass'    => getenv('DB_PASS') ?: '',
        'name'    => getenv('DB_NAME') ?: 'mcru-club',
        'charset' => 'utf8mb4',
    ],
    // base path ของแอปเมื่อรันใน subfolder เช่น /mcru-club  (เว้นว่าง '' ถ้าอยู่ที่ document root เช่นใน Docker)
    'base_path' => getenv('APP_BASE_PATH') !== false ? getenv('APP_BASE_PATH') : '/mcru-club',
];
