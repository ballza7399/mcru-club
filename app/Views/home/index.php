<?php /** @var array $clubs */ ?>
<h4 class="text-primary-custom fw-bold mb-4">รายชื่อชมรมที่เปิดรับสมัคร</h4>
<div class="row g-4">
    <?php foreach ($clubs as $row): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card-custom h-100 text-center d-flex flex-column">
            <div class="club-banner"></div>
            <div class="px-3 pb-4 d-flex flex-column flex-grow-1 align-items-center">
                <?php if (assetExists($row['club_logo'])): ?>
                    <img src="<?= asset($row['club_logo']) ?>" class="club-logo-thumb" alt="Logo">
                <?php else: ?>
                    <div class="club-logo-thumb bg-light d-flex align-items-center justify-content-center text-muted">No Image</div>
                <?php endif; ?>
                <h5 class="text-primary-custom fw-bold mt-3 mb-1"><?= e($row['club_name']) ?></h5>
                <div class="mb-2"><span class="badge bg-info text-dark rounded-pill">สมาชิก: <?= (int) $row['current_members'] ?> / <?= (int) $row['max_members'] ?></span></div>
                <p class="text-muted small flex-grow-1 mb-4"><?= mb_substr(e($row['description']), 0, 90) . '...' ?></p>
                <a href="<?= url('clubs/detail/' . $row['id']) ?>" class="btn-outline-custom w-100 py-2">รายละเอียด / สมัคร</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
