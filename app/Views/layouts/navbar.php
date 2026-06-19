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
            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'president'])): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle btn-manage" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-gear me-1"></i> จัดการระบบ
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">
                    <li><a class="dropdown-item" href="<?= url('clubs/manage') ?>">
                        <i class="fa-solid fa-layer-group text-primary me-2"></i> จัดการข้อมูลชมรม
                    </a></li>
                    <li><a class="dropdown-item" href="<?= url('applications/manage') ?>">
                        <i class="fa-solid fa-user-check text-success me-2"></i> จัดการคำขอสมาชิก
                    </a></li>
                </ul>
            </li>
            <?php endif; ?>
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
        </ul>
    </div>
</div>
</nav>
