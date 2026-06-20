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
function renderPagination(int $currentPage, int $totalPages, string $baseUrlPath, int $limit, string $pageKey = 'page', string $limitKey = 'limit'): string
{
    if ($totalPages <= 0) {
        return '';
    }

    $queryParams = $_GET;
    // reset page to 1 when changing limit
    $queryParams[$pageKey] = 1;

    // We can support different default lists of limits, e.g. for gallery (multiples of 12) or normal (10, 20, 30, 50, 100)
    $limits = ($limit % 12 === 0) ? [12, 24, 36, 48, 96] : [10, 20, 30, 50, 100];
    if (!in_array($limit, $limits)) {
        $limits[] = $limit;
        sort($limits);
    }

    $html = '<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">';
    
    // Page size selector
    $html .= '<div class="d-flex align-items-center gap-2">';
    $html .= '<span class="text-muted small">แสดง</span>';
    $html .= '<select class="form-select form-select-sm d-inline-block w-auto" onchange="window.location.href = this.value">';
    foreach ($limits as $l) {
        $queryParams[$limitKey] = $l;
        $url = url($baseUrlPath . '?' . http_build_query($queryParams));
        $selected = ($l === $limit) ? 'selected' : '';
        $html .= '<option value="' . $url . '" ' . $selected . '>' . $l . '</option>';
    }
    $html .= '</select>';
    $html .= '<span class="text-muted small">รายการต่อหน้า</span>';
    $html .= '</div>';

    // Pagination
    $html .= '<nav class="m-0"><ul class="pagination justify-content-center m-0">';
    
    $queryParams = $_GET;
    $queryParams[$limitKey] = $limit; // preserve the current limit

    // Previous Button
    if ($currentPage > 1) {
        $queryParams[$pageKey] = $currentPage - 1;
        $url = url($baseUrlPath . '?' . http_build_query($queryParams));
        $html .= '<li class="page-item"><a class="page-link" href="' . $url . '"><i class="fa-solid fa-chevron-left"></i> ย้อนกลับ</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link"><i class="fa-solid fa-chevron-left"></i> ย้อนกลับ</span></li>';
    }

    // Numbers
    for ($i = 1; $i <= $totalPages; $i++) {
        $queryParams[$pageKey] = $i;
        $url = url($baseUrlPath . '?' . http_build_query($queryParams));
        if ($i === $currentPage) {
            $html .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . $url . '">' . $i . '</a></li>';
        }
    }

    // Next Button
    if ($currentPage < $totalPages) {
        $queryParams[$pageKey] = $currentPage + 1;
        $url = url($baseUrlPath . '?' . http_build_query($queryParams));
        $html .= '<li class="page-item"><a class="page-link" href="' . $url . '">ถัดไป <i class="fa-solid fa-chevron-right"></i></a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">ถัดไป <i class="fa-solid fa-chevron-right"></i></span></li>';
    }

    $html .= '</ul></nav>';
    $html .= '</div>';
    
    return $html;
}

/** ตรวจสอบความยินยอม PDPA ของผู้ใช้งาน */
function needsPdpaConsent(): bool
{
    if (empty($_SESSION['user_id'])) {
        return false;
    }
    
    // ละเว้นหน้า logout และ หน้า API ยินยอมข้อมูล
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (str_contains($uri, '/auth/logout') || str_contains($uri, '/api/pdpa/consent')) {
        return false;
    }
    
    try {
        $userId = (int)$_SESSION['user_id'];
        $db = \App\Core\Database::instance();
        
        $stmt = $db->query("SELECT policy_key, version FROM policies");
        $policies = $stmt->fetchAll();
        
        foreach ($policies as $policy) {
            $key = $policy['policy_key'];
            $ver = $policy['version'];
            
            $stmtConsent = $db->prepare("SELECT COUNT(*) FROM user_consents WHERE user_id = ? AND policy_key = ? AND version = ?");
            $stmtConsent->execute([$userId, $key, $ver]);
            $hasConsented = (int)$stmtConsent->fetchColumn() > 0;
            
            if (!$hasConsented) {
                return true;
            }
        }
    } catch (\Exception $e) {
        return false;
    }
    
    return false;
}

/** ดึงข้อมูลนโยบายที่เปิดใช้งานอยู่ทั้งหมด */
function getActivePolicies(): array
{
    try {
        $db = \App\Core\Database::instance();
        return $db->query("SELECT * FROM policies")->fetchAll();
    } catch (\Exception $e) {
        return [];
    }
}

