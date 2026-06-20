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
        <ul class="navbar-nav ms-auto align-items-center gap-2 mt-3 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="<?= url() ?>"><i class="fa-solid fa-house-chimney me-1"></i> หน้าหลัก</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('clubs') ?>"><i class="fa-solid fa-people-group me-1"></i> รายชื่อชมรม</a>
            </li>
            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'president'])): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle btn-manage" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-gear me-1"></i> จัดการระบบ
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="min-width: 240px;">
                    <li><a class="dropdown-item py-2" href="<?= url('clubs/manage') ?>">
                        <i class="fa-solid fa-layer-group text-primary me-2"></i> จัดการข้อมูลชมรม
                    </a></li>
                    <li><a class="dropdown-item py-2" href="<?= url('applications/manage') ?>">
                        <i class="fa-solid fa-user-check text-success me-2"></i> จัดการคำขอสมาชิก
                    </a></li>
                    <li><a class="dropdown-item py-2" href="<?= url('clubs/members') ?>">
                        <i class="fa-solid fa-users text-info me-2"></i> จัดการสมาชิก & ตำแหน่ง
                    </a></li>
                    <li><a class="dropdown-item py-2" href="<?= url('roles/manage') ?>">
                        <i class="fa-solid fa-shield-halved text-warning me-2"></i> จัดการสิทธิ์การใช้งาน
                    </a></li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li><a class="dropdown-item py-2" href="<?= url('faculties/manage') ?>">
                        <i class="fa-solid fa-building-columns text-secondary me-2"></i> จัดการคณะ & สาขาวิชา
                    </a></li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2" href="<?= url('announcements/manage') ?>">
                        <i class="fa-solid fa-bullhorn text-danger me-2"></i> จัดการข่าวสาร PR
                    </a></li>
                    <li><a class="dropdown-item py-2" href="<?= url('events/manage') ?>">
                        <i class="fa-regular fa-calendar-check text-primary me-2"></i> จัดการปฏิทินกิจกรรม
                    </a></li>
                    <li><a class="dropdown-item py-2" href="<?= url('gallery/manage') ?>">
                        <i class="fa-regular fa-images text-success me-2"></i> จัดการภาพกิจกรรม
                    </a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <li class="nav-item ms-lg-3 my-2 my-lg-0">
                    <div class="user-profile-badge">
                        <div class="avatar"><i class="fa-solid fa-user"></i></div>
                        <div class="user-info text-start">
                            <span class="name"><?= e($_SESSION['name'] ?? '') ?></span>
                            <span class="role"><?php
                                $roleLabel = ['admin' => 'ผู้ดูแลระบบ (Admin)', 'president' => 'ประธานชมรม', 'student' => 'นักศึกษา'];
                                echo $roleLabel[$_SESSION['role'] ?? ''] ?? '';
                            ?></span>
                        </div>
                    </div>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn-logout" href="<?= url('auth/logout') ?>" title="ออกจากระบบ">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item ms-lg-3 my-2 my-lg-0">
                    <a class="btn btn-outline-light rounded-pill px-3 text-white" href="<?= url('auth/login') ?>">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> เข้าสู่ระบบ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning rounded-pill px-3 text-dark fw-bold border-0" style="background-color: var(--accent-gold); color: #1a1a1a !important;" href="<?= url('auth/register') ?>">
                        <i class="fa-solid fa-user-plus me-1"></i> สมัครสมาชิก
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</nav>
