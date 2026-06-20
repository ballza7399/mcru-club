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

/** แสดงผลลิ้งก์แบ่งหน้า (Pagination) แบบ Bootstrap */
function renderPagination(int $currentPage, int $totalPages, string $baseUrlPath, string $pageKey = 'page'): string
{
    if ($totalPages <= 0) {
        return '';
    }

    $html = '<nav class="mt-4"><ul class="pagination justify-content-center">';
    
    // ดึง query parameters ทั้งหมดที่ส่งมาก่อนหน้าเพื่อคงสถานะตัวกรอง (เช่น club_id=X)
    $queryParams = $_GET;
    
    // ปุ่มย้อนกลับ (Previous)
    if ($currentPage > 1) {
        $queryParams[$pageKey] = $currentPage - 1;
        $url = url($baseUrlPath . '?' . http_build_query($queryParams));
        $html .= '<li class="page-item"><a class="page-link" href="' . $url . '"><i class="fa-solid fa-chevron-left"></i> ย้อนกลับ</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link"><i class="fa-solid fa-chevron-left"></i> ย้อนกลับ</span></li>';
    }

    // ปุ่มตัวเลขหน้า
    for ($i = 1; $i <= $totalPages; $i++) {
        $queryParams[$pageKey] = $i;
        $url = url($baseUrlPath . '?' . http_build_query($queryParams));
        if ($i === $currentPage) {
            $html .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . $url . '">' . $i . '</a></li>';
        }
    }

    // ปุ่มถัดไป (Next)
    if ($currentPage < $totalPages) {
        $queryParams[$pageKey] = $currentPage + 1;
        $url = url($baseUrlPath . '?' . http_build_query($queryParams));
        $html .= '<li class="page-item"><a class="page-link" href="' . $url . '">ถัดไป <i class="fa-solid fa-chevron-right"></i></a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">ถัดไป <i class="fa-solid fa-chevron-right"></i></span></li>';
    }

    $html .= '</ul></nav>';
    return $html;
}
