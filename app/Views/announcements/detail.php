<?php
/**
 * @var array $announcement
 */
?>

<div class="row justify-content-center">
    <div class="col-md-9 col-lg-8">
        <!-- Breadcrumb / Back Navigation -->
        <div class="mb-4">
            <a href="<?= url('/') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> กลับสู่หน้าแรก
            </a>
        </div>

        <article class="card-custom p-4 p-md-5 mb-5 shadow-sm">
            <!-- Badge: Club or Central News -->
            <div class="mb-3">
                <span class="badge py-2 px-3 fs-6 <?= $announcement['club_id'] ? 'bg-warning text-dark' : 'bg-primary text-white' ?>" style="border-radius: 30px;">
                    <i class="fa-solid <?= $announcement['club_id'] ? 'fa-users' : 'fa-bullhorn' ?> me-1"></i>
                    <?= $announcement['club_id'] ? e($announcement['club_name']) : 'ข่าวสารส่วนกลาง' ?>
                </span>
            </div>

            <!-- Title -->
            <h1 class="display-6 fw-bold text-primary-custom mb-3" style="line-height: 1.3;">
                <?= e($announcement['title']) ?>
            </h1>

            <!-- Metadata -->
            <div class="d-flex flex-wrap gap-3 align-items-center text-muted small pb-3 mb-4 border-bottom opacity-75">
                <span>
                    <i class="fa-regular fa-user me-1 text-primary"></i>
                    เขียนโดย: <strong><?= e($announcement['author_name']) ?></strong>
                </span>
                <span class="d-none d-sm-inline">•</span>
                <span>
                    <i class="fa-regular fa-calendar-days me-1 text-primary"></i>
                    วันที่ลงข่าว: <?= date('d/m/Y H:i', strtotime($announcement['created_at'])) ?> น.
                </span>
            </div>

            <!-- Cover Image (If available) -->
            <?php if (!empty($announcement['thumbnail']) && assetExists($announcement['thumbnail'])): ?>
                <div class="mb-4 text-center overflow-hidden rounded shadow-sm" style="max-height: 450px;">
                    <img src="<?= asset($announcement['thumbnail']) ?>" class="img-fluid w-100" style="object-fit: cover; max-height: 450px;" alt="Cover image">
                </div>
            <?php endif; ?>

            <!-- Rich HTML Content -->
            <div class="announcement-content text-dark mb-4" style="line-height: 1.8; font-size: 1.1rem; word-break: break-word;">
                <!-- Output CKEditor content directly as it is HTML -->
                <?= $announcement['content'] ?>
            </div>

            <!-- Share/Action Section -->
            <hr class="my-4 opacity-10">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-muted small">ขอบคุณที่ติดตามข่าวสารชมรมและกิจกรรมนักศึกษา MCRU</span>
                <div>
                    <button class="btn btn-sm btn-light border text-secondary rounded-pill px-3" onclick="window.print()">
                        <i class="fa-solid fa-print me-1"></i> พิมพ์หน้านี้
                    </button>
                </div>
            </div>
        </article>
    </div>
</div>
