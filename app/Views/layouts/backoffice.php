<?php
/**
 * Layout เฉพาะส่วนระบบจัดการหลังบ้าน (Backoffice)
 * @var string $content
 * @var string|null $flash
 */

// ตรวจสอบและกำหนดหน้าปัจจุบันจาก Request URI เพื่อนำไปใส่คลาส active ใน Sidebar
$uri = $_SERVER['REQUEST_URI'] ?? '';
$activePage = 'dashboard';
if (str_contains($uri, 'clubs/manage')) {
    $activePage = 'clubs';
} elseif (str_contains($uri, 'applications/manage')) {
    $activePage = 'applications';
} elseif (str_contains($uri, 'clubs/members')) {
    $activePage = 'members';
} elseif (str_contains($uri, 'roles/manage')) {
    $activePage = 'roles';
} elseif (str_contains($uri, 'users/manage')) {
    $activePage = 'users';
} elseif (str_contains($uri, 'faculties/manage')) {
    $activePage = 'faculties';
} elseif (str_contains($uri, 'announcements/manage')) {
    $activePage = 'announcements';
} elseif (str_contains($uri, 'events/manage')) {
    $activePage = 'events';
} elseif (str_contains($uri, 'gallery/manage')) {
    $activePage = 'gallery';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>หลังบ้านระบบจัดการ MCRU</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= asset('style.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.addEventListener('error', function(e) {
    if (e.target.tagName === 'IMG') {
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" viewBox="0 0 300 200"><rect width="100%" height="100%" fill="#f8fafc" rx="10"/><rect width="98%" height="97%" x="1%" y="1%" fill="none" stroke="#e2e8f0" stroke-width="2" stroke-dasharray="6" rx="10"/><g transform="translate(150, 85)" text-anchor="middle"><path d="M-18,-15 L-10,-15 L-7,-22 L7,-22 L10,-15 L18,-15 C22,-15 25,-12 25,-8 L25,12 C25,16 22,19 18,19 L-18,19 C-22,19 -25,16 -25,12 L-25,-8 C-25,-12 -22,-15 -18,-15 Z" fill="none" stroke="#94a3b8" stroke-width="2.5" stroke-linejoin="round"/><circle cx="0" cy="2" r="7" fill="none" stroke="#94a3b8" stroke-width="2.5"/><circle cx="14" cy="-7" r="1.5" fill="#94a3b8"/><text y="42" font-family="'Kanit', sans-serif" font-size="13" font-weight="500" fill="#64748b">ไม่มีรูปภาพ (No Image)</text></g></svg>`;
        const noImageUrl = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svg)));
        if (e.target.src !== noImageUrl) {
            e.target.src = noImageUrl;
        }
    }
}, true);
</script>
</head>
<body>
<?php require BASE_PATH . '/app/Views/layouts/navbar.php'; ?>

<div class="container-fluid pb-5 mt-4 px-md-4">
    <div class="row g-4">
        <!-- Sidebar ด้านซ้าย -->
        <div class="col-lg-3 col-md-4">
            <div class="card-custom p-3 shadow-sm border" style="background: var(--surface); border-color: var(--border);">
                <h5 class="fw-bold text-primary-custom mb-3 pb-2 border-bottom">
                    <i class="fa-solid fa-screwdriver-wrench me-2" style="color: var(--accent-gold) !important;"></i>เมนูจัดการระบบ
                </h5>
                <div class="nav flex-column nav-pills gap-1">
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'dashboard' ? 'active' : '' ?>" href="<?= url('backoffice') ?>">
                        <i class="fa-solid fa-chart-pie me-2"></i>ภาพรวมระบบ (Dashboard)
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'clubs' ? 'active' : '' ?>" href="<?= url('clubs/manage') ?>">
                        <i class="fa-solid fa-layer-group me-2"></i>จัดการข้อมูลชมรม
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'applications' ? 'active' : '' ?>" href="<?= url('applications/manage') ?>">
                        <i class="fa-solid fa-user-check me-2"></i>จัดการคำขอสมัครสมาชิก
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'members' ? 'active' : '' ?>" href="<?= url('clubs/members') ?>">
                        <i class="fa-solid fa-users me-2"></i>จัดการสมาชิก & ตำแหน่ง
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'roles' ? 'active' : '' ?>" href="<?= url('roles/manage') ?>">
                        <i class="fa-solid fa-shield-halved me-2"></i>จัดการสิทธิ์การใช้งาน
                    </a>
                    
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a class="nav-link admin-sidebar-link <?= $activePage === 'users' ? 'active' : '' ?>" href="<?= url('users/manage') ?>">
                            <i class="fa-solid fa-users-gear me-2"></i>จัดการผู้ใช้ในระบบ
                        </a>
                        <a class="nav-link admin-sidebar-link <?= $activePage === 'faculties' ? 'active' : '' ?>" href="<?= url('faculties/manage') ?>">
                            <i class="fa-solid fa-building-columns me-2"></i>จัดการคณะ & สาขาวิชา
                        </a>
                    <?php endif; ?>
                    
                    <div class="my-2 border-top"></div>
                    
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'announcements' ? 'active' : '' ?>" href="<?= url('announcements/manage') ?>">
                        <i class="fa-solid fa-bullhorn me-2"></i>จัดการข่าวสาร PR
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'events' ? 'active' : '' ?>" href="<?= url('events/manage') ?>">
                        <i class="fa-regular fa-calendar-check me-2"></i>จัดการปฏิทินกิจกรรม
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'gallery' ? 'active' : '' ?>" href="<?= url('gallery/manage') ?>">
                        <i class="fa-regular fa-images me-2"></i>จัดการภาพกิจกรรม
                    </a>
                </div>
            </div>
        </div>
        
        <!-- ฝั่งแสดงข้อมูลด้าขวา -->
        <div class="col-lg-9 col-md-8">
            <?php if ($flash): ?><div class="alert alert-success"><?= e($flash) ?></div><?php endif; ?>
            <?= $content ?>
        </div>
    </div>
</div>
</body>
</html>
