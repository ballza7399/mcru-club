<?php
/**
 * Layout เฉพาะส่วนระบบจัดการหลังบ้าน (Backoffice)
 * @var string $content
 * @var string|null $flash
 */

// ตรวจสอบและกำหนดหน้าปัจจุบันจาก Request URI เพื่อนำไปใส่คลาส active ใน Sidebar
$uri = $_SERVER['REQUEST_URI'] ?? '';
$activePage = 'dashboard';
if (str_contains($uri, 'backoffice/clubs/members')) {
    $activePage = 'members';
} elseif (str_contains($uri, 'backoffice/clubs')) {
    $activePage = 'clubs';
} elseif (str_contains($uri, 'backoffice/applications')) {
    $activePage = 'applications';
} elseif (str_contains($uri, 'backoffice/roles')) {
    $activePage = 'roles';
} elseif (str_contains($uri, 'backoffice/users')) {
    $activePage = 'users';
} elseif (str_contains($uri, 'backoffice/faculties')) {
    $activePage = 'faculties';
} elseif (str_contains($uri, 'backoffice/announcements')) {
    $activePage = 'announcements';
} elseif (str_contains($uri, 'backoffice/events')) {
    $activePage = 'events';
} elseif (str_contains($uri, 'backoffice/gallery')) {
    $activePage = 'gallery';
} elseif (str_contains($uri, 'backoffice/settings/footer')) {
    $activePage = 'footer_settings';
} elseif (str_contains($uri, 'backoffice/settings/mourning')) {
    $activePage = 'mourning_settings';
} elseif (str_contains($uri, 'backoffice/settings/og')) {
    $activePage = 'og_settings';
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>หลังบ้านระบบจัดการ MCRU</title>
<link rel="shortcut icon" href="<?= asset('favicon.ico') ?>" type="image/x-icon">
<link rel="icon" href="<?= asset('favicon.png') ?>" type="image/png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= asset('style.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
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
        <div class="col-lg-3 col-md-4" id="backoffice-sidebar">
            <div class="card-custom p-3 shadow-sm border" style="background: var(--surface); border-color: var(--border);">
                <h5 class="fw-bold text-primary-custom mb-3 pb-2 border-bottom">
                    <i class="fa-solid fa-screwdriver-wrench me-2" style="color: var(--accent-gold) !important;"></i>เมนูจัดการระบบ
                </h5>
                <div class="nav flex-column nav-pills gap-1">
                    <!-- กลุ่มภาพรวม -->
                    <div class="sidebar-category-header text-uppercase text-muted fw-bold mb-1 px-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        ภาพรวมระบบ
                    </div>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'dashboard' ? 'active' : '' ?>" href="<?= url('backoffice') ?>">
                        <i class="fa-solid fa-chart-pie me-2"></i>ภาพรวมระบบ (Dashboard)
                    </a>
                    
                    <!-- กลุ่มการจัดการชมรม -->
                    <div class="sidebar-category-header text-uppercase text-muted fw-bold mb-1 mt-3 px-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        การจัดการชมรม
                    </div>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'clubs' ? 'active' : '' ?>" href="<?= url('backoffice/clubs') ?>">
                        <i class="fa-solid fa-layer-group me-2"></i>จัดการข้อมูลชมรม
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'applications' ? 'active' : '' ?>" href="<?= url('backoffice/applications') ?>">
                        <i class="fa-solid fa-user-check me-2"></i>จัดการคำขอสมัครสมาชิก
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'members' ? 'active' : '' ?>" href="<?= url('backoffice/clubs/members') ?>">
                        <i class="fa-solid fa-users me-2"></i>จัดการสมาชิก & ตำแหน่ง
                    </a>
                    
                    <!-- กลุ่มเนื้อหา & กิจกรรม -->
                    <div class="sidebar-category-header text-uppercase text-muted fw-bold mb-1 mt-3 px-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        เนื้อหา & ประชาสัมพันธ์
                    </div>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'announcements' ? 'active' : '' ?>" href="<?= url('backoffice/announcements') ?>">
                        <i class="fa-solid fa-bullhorn me-2"></i>จัดการข่าวสาร PR
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'events' ? 'active' : '' ?>" href="<?= url('backoffice/events') ?>">
                        <i class="fa-regular fa-calendar-check me-2"></i>จัดการปฏิทินกิจกรรม
                    </a>
                    <a class="nav-link admin-sidebar-link <?= $activePage === 'gallery' ? 'active' : '' ?>" href="<?= url('backoffice/gallery') ?>">
                        <i class="fa-regular fa-images me-2"></i>จัดการภาพกิจกรรม
                    </a>

                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <!-- กลุ่มความปลอดภัย & สิทธิ์ -->
                        <div class="sidebar-category-header text-uppercase text-muted fw-bold mb-1 mt-3 px-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            ผู้ใช้งาน & สิทธิ์การใช้งาน
                        </div>
                        <a class="nav-link admin-sidebar-link <?= $activePage === 'users' ? 'active' : '' ?>" href="<?= url('backoffice/users') ?>">
                            <i class="fa-solid fa-users-gear me-2"></i>จัดการผู้ใช้ในระบบ
                        </a>
                        <a class="nav-link admin-sidebar-link <?= $activePage === 'roles' ? 'active' : '' ?>" href="<?= url('backoffice/roles') ?>">
                            <i class="fa-solid fa-shield-halved me-2"></i>จัดการสิทธิ์การใช้งาน
                        </a>

                        <!-- กลุ่มการตั้งค่าระบบ -->
                        <div class="sidebar-category-header text-uppercase text-muted fw-bold mb-1 mt-3 px-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            การตั้งค่าระบบ
                        </div>
                        <a class="nav-link admin-sidebar-link <?= $activePage === 'faculties' ? 'active' : '' ?>" href="<?= url('backoffice/faculties') ?>">
                            <i class="fa-solid fa-building-columns me-2"></i>จัดการคณะ & สาขาวิชา
                        </a>
                        <a class="nav-link admin-sidebar-link <?= $activePage === 'pdpa' ? 'active' : '' ?>" href="<?= url('backoffice/pdpa') ?>">
                            <i class="fa-solid fa-scale-balanced me-2"></i>จัดการนโยบาย PDPA
                        </a>
                        <a class="nav-link admin-sidebar-link <?= $activePage === 'footer_settings' ? 'active' : '' ?>" href="<?= url('backoffice/settings/footer') ?>">
                            <i class="fa-solid fa-list-check me-2"></i>จัดการข้อมูล Footer
                        </a>
                        <a class="nav-link admin-sidebar-link <?= $activePage === 'mourning_settings' ? 'active' : '' ?>" href="<?= url('backoffice/settings/mourning') ?>">
                            <i class="fa-solid fa-ribbon me-2"></i>ตั้งค่าหน้าไว้อาลัย
                        </a>
                        <a class="nav-link admin-sidebar-link <?= $activePage === 'og_settings' ? 'active' : '' ?>" href="<?= url('backoffice/settings/og') ?>">
                            <i class="fa-solid fa-share-nodes me-2"></i>ตั้งค่าการแชร์ (Open Graph)
                        </a>
                    <?php endif; ?>
                    
                    <!-- กลุ่มอื่น ๆ -->
                    <div class="sidebar-category-header text-uppercase text-muted fw-bold mb-1 mt-3 px-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        เอกสารการใช้งาน
                    </div>
                    <a class="nav-link admin-sidebar-link" href="<?= url('policy') ?>" onclick="openPdpaViewerModal(event)">
                        <i class="fa-solid fa-user-shield me-2 text-success"></i>นโยบาย & ข้อตกลง (PDPA)
                    </a>
                </div>

            </div>
        </div>
        
        <!-- ฝั่งแสดงข้อมูลด้าขวา -->
        <div class="col-lg-9 col-md-8" id="backoffice-content">
            <?php if ($flash): ?><div class="alert alert-success"><?= e($flash) ?></div><?php endif; ?>
            <?= $content ?>
        </div>
    </div>
</div>

<script>
let activeEditors = [];

function destroyEditors() {
    activeEditors.forEach(editor => {
        if (typeof editor.destroy === 'function') {
            editor.destroy().catch(err => console.error('Error destroying editor:', err));
        }
    });
    activeEditors = [];
}

function initDynamicComponents() {
    // 1. Initialize CKEditor instances
    document.querySelectorAll('.ckeditor-replace').forEach(textarea => {
        if (typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(textarea, {
                    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
                })
                .then(editor => {
                    activeEditors.push(editor);
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });
        }
    });
    
    // 2. SweetAlert Toast for flash messages
    const flashEl = document.querySelector('#backoffice-content .alert-success');
    if (flashEl) {
        const message = flashEl.innerText.trim();
        flashEl.style.display = 'none';
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        Toast.fire({
            icon: 'success',
            title: message
        });
    }
}

function showLoading(isMutating = false) {
    if (isMutating) {
        Swal.fire({
            title: 'กำลังประมวลผล...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    } else {
        const contentArea = document.getElementById('backoffice-content');
        if (contentArea) {
            contentArea.style.opacity = '0.5';
            contentArea.style.pointerEvents = 'none';
        }
    }
}

function hideLoading() {
    if (Swal.isVisible() && !Swal.isTimerRunning()) {
        Swal.close();
    }
    const contentArea = document.getElementById('backoffice-content');
    if (contentArea) {
        contentArea.style.opacity = '1';
        contentArea.style.pointerEvents = 'auto';
    }
}

function closeOpenModals() {
    document.querySelectorAll('.modal.show').forEach(modalEl => {
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) {
            modalInstance.hide();
        } else {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
        }
    });
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
    document.body.style.removeProperty('overflow');
}

async function loadBackofficePage(url, options = {}) {
    const isMutating = (options.method && options.method.toUpperCase() !== 'GET') || url.includes('/delete/') || url.includes('/toggle/') || url.includes('/reset/') || url.includes('/approve/') || url.includes('/reject/');
    showLoading(isMutating);
    
    try {
        const response = await fetch(url, options);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const finalUrl = response.url;
        const htmlText = await response.text();
        const parser = new DOMParser();
        const newDoc = parser.parseFromString(htmlText, 'text/html');
        
        // Fallback if returned page is not part of the backoffice (e.g. redirected to login page)
        if (!newDoc.getElementById('backoffice-content')) {
            window.location.href = finalUrl;
            return;
        }
        
        // Update URL path in browser history
        if (finalUrl !== window.location.href) {
            history.pushState(null, '', finalUrl);
        }
        
        closeOpenModals();
        destroyEditors();
        
        // Swap content
        document.getElementById('backoffice-content').innerHTML = newDoc.getElementById('backoffice-content').innerHTML;
        
        // Swap sidebar to keep active menu classes in sync
        const newSidebar = newDoc.getElementById('backoffice-sidebar');
        if (newSidebar) {
            document.getElementById('backoffice-sidebar').innerHTML = newSidebar.innerHTML;
        }
        
        // Update browser tab title
        const newTitle = newDoc.querySelector('title');
        if (newTitle) {
            document.title = newTitle.innerText;
        }
        
        // Re-initialize dynamic components
        initDynamicComponents();
        
    } catch (error) {
        console.error('AJAX request failed:', error);
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: 'ไม่สามารถโหลดข้อมูลได้ กรุณาลองใหม่อีกครั้ง'
        });
    } finally {
        hideLoading();
    }
}

// Intercept all anchor link clicks pointing inside backoffice or cluboffice
document.addEventListener('click', function(e) {
    const a = e.target.closest('a');
    if (!a) return;
    
    // Skip external links, downloads, hash links, or target="_blank"
    if (a.getAttribute('target') === '_blank' || a.hasAttribute('download')) return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    
    const href = a.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
    
    try {
        const targetUrl = new URL(a.href);
        const isBackoffice = targetUrl.pathname.includes('/backoffice') || targetUrl.pathname.includes('/cluboffice');
        if (targetUrl.origin === window.location.origin && isBackoffice) {
            // Respect inline onclick cancellation (e.g. return confirm('...'))
            if (e.defaultPrevented) return;
            
            e.preventDefault();
            
            const confirmMsg = a.getAttribute('data-confirm');
            if (confirmMsg) {
                const title = a.getAttribute('data-confirm-title') || 'ยืนยันการทำรายการ';
                const icon = a.getAttribute('data-confirm-icon') || 'warning';
                const color = a.getAttribute('data-confirm-color') || '#d33';
                const btnText = a.getAttribute('data-confirm-btn') || 'ยืนยัน';
                Swal.fire({
                    title: title,
                    text: confirmMsg,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: color,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: btnText,
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        loadBackofficePage(a.href);
                    }
                });
            } else {
                loadBackofficePage(a.href);
            }
        }
    } catch (err) {
        console.error('Invalid URL:', err);
    }
});

// Intercept all form submissions inside the backoffice or cluboffice
document.addEventListener('submit', function(e) {
    const form = e.target;
    const action = form.getAttribute('action') || window.location.href;
    
    try {
        const targetUrl = new URL(action, window.location.origin);
        const isBackoffice = targetUrl.pathname.includes('/backoffice') || targetUrl.pathname.includes('/cluboffice');
        if (targetUrl.origin === window.location.origin && isBackoffice) {
            if (e.defaultPrevented) return;
            
            e.preventDefault();
            
            // Sync CKEditor values to their associated textareas
            activeEditors.forEach(editor => {
                if (typeof editor.updateSourceElement === 'function') {
                    editor.updateSourceElement();
                }
            });
            
            const formData = new FormData(form);
            const method = (form.getAttribute('method') || 'POST').toUpperCase();
            
            const options = {
                method: method
            };
            
            let finalAction = action;
            if (method === 'GET') {
                const params = new URLSearchParams(formData);
                const urlObj = new URL(action, window.location.origin);
                for (const [key, value] of params.entries()) {
                    urlObj.searchParams.set(key, value);
                }
                finalAction = urlObj.pathname + urlObj.search;
            } else {
                options.body = formData;
            }
            
            const confirmMsg = form.getAttribute('data-confirm');
            if (confirmMsg) {
                const title = form.getAttribute('data-confirm-title') || 'ยืนยันการทำรายการ';
                const icon = form.getAttribute('data-confirm-icon') || 'warning';
                const color = form.getAttribute('data-confirm-color') || '#d33';
                const btnText = form.getAttribute('data-confirm-btn') || 'ยืนยัน';
                Swal.fire({
                    title: title,
                    text: confirmMsg,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: color,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: btnText,
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        loadBackofficePage(finalAction, options);
                    }
                });
            } else {
                loadBackofficePage(finalAction, options);
            }
        }
    } catch (err) {
        console.error('Error intercepts form submit:', err);
    }
});

// Handle browser Back/Forward navigation buttons
window.addEventListener('popstate', function() {
    const isBackoffice = window.location.pathname.includes('/backoffice') || window.location.pathname.includes('/cluboffice');
    if (isBackoffice) {
        loadBackofficePage(window.location.href);
    } else {
        window.location.reload();
    }
});

// Initial load
document.addEventListener('DOMContentLoaded', function() {
    initDynamicComponents();
});
</script>
<?php require BASE_PATH . '/app/Views/layouts/pdpa_modal.php'; ?>
</body>
</html>
