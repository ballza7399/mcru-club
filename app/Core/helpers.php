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

/** สร้าง URL แบบสัมบูรณ์ (Absolute URL) มี protocol และ domain สำหรับระบุใน Meta Tags */
function absoluteUrl(string $path = ''): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $protocol . $host . url($path);
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

/** ดึงประวัติความยินยอมของผู้ใช้ปัจจุบัน */
function getMyConsents(): array
{
    if (empty($_SESSION['user_id'])) {
        return [];
    }
    try {
        $userId = (int)$_SESSION['user_id'];
        $db = \App\Core\Database::instance();
        $stmt = $db->prepare("
            SELECT c.*, p.title 
            FROM user_consents c
            LEFT JOIN policies p ON c.policy_key = p.policy_key
            WHERE c.user_id = ?
            ORDER BY c.consented_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (\Exception $e) {
        return [];
    }
}

/** ดึงค่าการตั้งค่าจากฐานข้อมูล */
function getSetting(string $key, string $default = ''): string
{
    try {
        $db = \App\Core\Database::instance();
        $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return ($val !== false) ? (string)$val : $default;
    } catch (\Exception $e) {
        return $default;
    }
}

// Polyfills สำหรับ PHP 7.4 (ช่วยรองรับฟังก์ชันพื้นฐานของ PHP 8.0)
if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return 0 === strpos($haystack, $needle);
    }
}

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }
}

/** ตรวจสอบสิทธิ์การเข้าถึงหลังบ้านตามบทบาทในเซสชัน */
function hasBackofficePermission(string $permissionKey): bool
{
    if (empty($_SESSION['user_id'])) {
        return false;
    }
    $roleKey = $_SESSION['role'] ?? '';
    if ($roleKey === 'admin') {
        return true;
    }
    try {
        $db = \App\Core\Database::instance();
        $stmtRole = $db->prepare('SELECT id FROM roles WHERE role_key = ?');
        $stmtRole->execute([$roleKey]);
        $roleId = $stmtRole->fetchColumn();
        if (!$roleId) {
            return false;
        }
        $stmtCheck = $db->prepare('
            SELECT COUNT(*) 
            FROM role_permissions rp
            JOIN permissions p ON rp.permission_id = p.id
            WHERE rp.role_id = ? AND p.perm_key = ?
        ');
        $stmtCheck->execute([$roleId, $permissionKey]);
        return (int)$stmtCheck->fetchColumn() > 0;
    } catch (\Exception $e) {
        return false;
    }
}




