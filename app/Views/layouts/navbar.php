<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
<div class="container">
    <a class="navbar-brand d-flex align-items-center" href="<?= url() ?>">
        <div class="brand-icon me-2"><i class="fa-solid fa-graduation-cap"></i></div>
        MCRU<span class="fw-light">Clubs</span>
    </a>
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <i class="fa-solid fa-bars-staggered text-white fs-3"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-start align-items-lg-center gap-2 mt-3 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="<?= url() ?>"><i class="fa-solid fa-house-chimney me-1"></i> หน้าหลัก</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('clubs') ?>"><i class="fa-solid fa-people-group me-1"></i> รายชื่อชมรม</a>
            </li>
            <?php if (!empty($_SESSION['user_id']) && $_SESSION['role'] === 'student'): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('clubs/register') ?>"><i class="fa-solid fa-file-signature me-1"></i> เสนอเพิ่มข้อมูลชมรม</a>
            </li>
            <?php endif; ?>
            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'president'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('backoffice') ?>">
                    <i class="fa-solid fa-gear me-1"></i> จัดการระบบ
                </a>
            </li>
            <?php endif; ?>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <!-- Notification Bell Dropdown -->
                <li class="nav-item dropdown dropdown-notifications ms-lg-2 position-relative">
                    <a class="nav-link text-white position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 8px 12px; border-radius: 10px;">
                        <i class="fa-regular fa-bell fs-5"></i>
                        <span class="position-absolute badge rounded-pill bg-danger d-none" id="notificationBadge" style="top: 2px; right: 2px; font-size: 0.65rem; padding: 3px 6px;">
                            0
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0 mt-2" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px; overflow-y: auto; border-radius: 12px; z-index: 1050;">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                            <span class="fw-bold text-dark" style="font-size: 0.9rem;"><i class="fa-solid fa-bell text-warning me-2"></i>การแจ้งเตือน</span>
                            <button class="btn btn-link btn-sm text-decoration-none p-0 text-primary-custom d-none" id="markAllReadBtn" style="font-size: 0.75rem;">อ่านแล้วทั้งหมด</button>
                        </div>
                        <div id="notificationList" class="py-1">
                            <div class="text-center py-4 text-muted">
                                <i class="fa-regular fa-bell-slash fs-4 mb-2 opacity-50"></i>
                                <p class="small m-0">ไม่มีการแจ้งเตือนใหม่</p>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown ms-lg-3 my-2 my-lg-0">
                    <a class="p-0 d-inline-block text-decoration-none border-0" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none;">


                        <div class="user-profile-badge d-flex align-items-center">
                            <div class="avatar overflow-hidden d-flex align-items-center justify-content-center" style="position: relative;">
                                <?php if (!empty($_SESSION['avatar'])): ?>
                                    <img src="<?= asset($_SESSION['avatar']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fa-solid fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <div class="user-info text-start me-1">
                                <span class="name"><?= e($_SESSION['name'] ?? '') ?></span>
                                <span class="role"><?php
                                    $roleLabel = ['admin' => 'ผู้ดูแลระบบ (Admin)', 'president' => 'ประธานชมรม', 'student' => 'นักศึกษา'];
                                    echo $roleLabel[$_SESSION['role'] ?? ''] ?? '';
                                ?></span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2" aria-labelledby="profileDropdown" style="border-radius: 12px; min-width: 200px;">
                        <li>
                            <a class="dropdown-item py-2 px-3 text-dark d-flex align-items-center gap-2 rounded-3" href="<?= url('profile') ?>">
                                <i class="fa-solid fa-circle-user text-primary-custom" style="width: 18px;"></i>
                                <span>ข้อมูลส่วนตัวของฉัน</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 px-3 text-danger d-flex align-items-center gap-2 rounded-3" href="<?= url('auth/logout') ?>">
                                <i class="fa-solid fa-right-from-bracket" style="width: 18px;"></i>
                                <span>ออกจากระบบ</span>
                            </a>
                        </li>
                    </ul>
                </li>

            <?php else: ?>
                <li class="nav-item ms-lg-3 my-2 my-lg-0">
                    <a class="btn btn-outline-light rounded-pill px-3" href="<?= url('auth/login') ?>">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> เข้าสู่ระบบ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-gold-custom rounded-pill px-3 fw-bold" href="<?= url('auth/register') ?>">
                        <i class="fa-solid fa-user-plus me-1"></i> สมัครสมาชิก
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</nav>

<?php if (!empty($_SESSION['user_id'])): ?>
<style>
.dropdown-notifications .dropdown-menu {
    border: 1px solid var(--border) !important;
    max-height: 400px;
}
.notification-item:hover {
    background-color: var(--surface-alt) !important;
}
.notification-item.unread-bg {
    background-color: rgba(11, 44, 92, 0.04) !important;
}
.notification-item.unread-bg:hover {
    background-color: rgba(11, 44, 92, 0.08) !important;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const badge = document.getElementById('notificationBadge');
    const list = document.getElementById('notificationList');
    const markBtn = document.getElementById('markAllReadBtn');
    
    function fetchNotifications() {
        if (!badge || !list) return;
        
        fetch('<?= url("api/notifications") ?>')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update Badge
                    const count = parseInt(data.unread_count);
                    if (count > 0) {
                        badge.innerText = count;
                        badge.classList.remove('d-none');
                        if (markBtn) markBtn.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                        if (markBtn) markBtn.classList.add('d-none');
                    }
                    
                    // Update List
                    if (data.notifications.length === 0) {
                        list.innerHTML = `
                            <div class="text-center py-4 text-muted">
                                <i class="fa-regular fa-bell-slash fs-4 mb-2 opacity-50"></i>
                                <p class="small m-0">ไม่มีการแจ้งเตือน</p>
                            </div>`;
                    } else {
                        let html = '';
                        data.notifications.forEach(n => {
                            const isUnread = n.is_read === 0;
                            const bgClass = isUnread ? 'unread-bg' : '';
                            const dotHtml = isUnread ? '<span class="d-inline-block bg-danger rounded-circle me-1 animate-pulse" style="width: 6px; height: 6px;"></span>' : '';
                            
                            html += `
                                <div class="dropdown-item notification-item p-3 border-bottom text-wrap ${bgClass}" data-id="${n.id}" style="cursor: pointer; transition: background 0.2s;">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="fw-bold text-dark small" style="line-height: 1.2;">${dotHtml}${n.title}</span>
                                        <small class="text-muted text-nowrap font-monospace ms-2" style="font-size: 0.7rem;">${n.created_at}</small>
                                    </div>
                                    <p class="text-muted small m-0" style="line-height: 1.4; font-size: 0.8rem;">${n.message}</p>
                                </div>`;
                        });
                        list.innerHTML = html;
                        
                        // Add click listeners to items
                        list.querySelectorAll('.notification-item').forEach(item => {
                            item.addEventListener('click', function(e) {
                                e.stopPropagation();
                                const notifId = this.getAttribute('data-id');
                                fetch(`<?= url("api/notifications/read/") ?>${notifId}`, { method: 'POST' })
                                    .then(res => res.json())
                                    .then(resData => {
                                        if (resData.success) {
                                            this.classList.remove('unread-bg');
                                            const dot = this.querySelector('.bg-danger');
                                            if (dot) dot.remove();
                                            
                                            // Re-fetch count
                                            fetch('<?= url("api/notifications") ?>')
                                                .then(res => res.json())
                                                .then(updateData => {
                                                    const newCount = parseInt(updateData.unread_count);
                                                    if (newCount > 0) {
                                                        badge.innerText = newCount;
                                                        badge.classList.remove('d-none');
                                                        if (markBtn) markBtn.classList.remove('d-none');
                                                    } else {
                                                        badge.classList.add('d-none');
                                                        if (markBtn) markBtn.classList.add('d-none');
                                                    }
                                                });
                                        }
                                    });
                            });
                        });
                    }
                }
            })
            .catch(err => console.error('Error fetching notifications:', err));
    }
    
    // Initial fetch
    fetchNotifications();
    
    // Poll every 30 seconds for new notifications
    setInterval(fetchNotifications, 30000);
    
    // Mark all as read
    if (markBtn) {
        markBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            fetch('<?= url("api/notifications/read-all") ?>', { method: 'POST' })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        badge.classList.add('d-none');
                        markBtn.classList.add('d-none');
                        list.querySelectorAll('.notification-item').forEach(item => {
                            item.classList.remove('unread-bg');
                            const dot = item.querySelector('.bg-danger');
                            if (dot) dot.remove();
                        });
                    }
                });
        });
    }
});
</script>
<?php endif; ?>
