<?php
// Global helpers — โหลดใน index.php

/** สร้าง URL จาก path (อิงจาก base_path ของแอป) */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** alias ของ url() ใช้กับไฟล์ asset เช่น รูป/CSS ที่เก็บใต้ root */
function asset(string $path): string
{
    return url($path);
}

/** escape สำหรับแสดงผลใน HTML */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** ตรวจว่าไฟล์ asset (relative path) มีอยู่จริงบน disk */
function assetExists(?string $path): bool
{
    return !empty($path) && file_exists(BASE_PATH . '/' . $path);
}
