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
            <li class="nav-item">
                <a class="nav-link" href="<?= url('backoffice') ?>">
                    <i class="fa-solid fa-gear me-1"></i> จัดการระบบ
                </a>
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
