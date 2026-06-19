<div class="row justify-content-center">
<div class="col-md-8 text-center card-custom p-5">
    <?php if (assetExists($club['club_logo'])): ?>
        <img src="<?= asset($club['club_logo']) ?>" class="mb-3 rounded-circle" style="width:120px;height:120px;object-fit:cover;border:4px solid var(--accent-gold);" alt="Logo">
    <?php endif; ?>

    <h2 class="text-primary-custom fw-bold mb-2"><?= e($club['club_name']) ?></h2>
    <p class="text-muted fw-bold mb-4">ประธานชมรม: <?= $club['pres_name'] ? e($club['pres_name']) : '<span class="text-danger">ยังไม่ระบุ</span>' ?></p>

    <?php
        $cur = (int) $club['current_members'];
        $max = (int) $club['max_members'];
        $ratio = $max > 0 ? $cur / $max : 1;
        $capState = $isFull ? 'full' : ($ratio >= 0.8 ? 'nearly' : 'open');
    ?>
    <div class="d-flex justify-content-center mb-4">
        <div class="capacity-meter capacity-meter--<?= $capState ?>">
            <span class="capacity-meter__label">สถานะรับสมาชิก</span>
            <span class="capacity-meter__value"><?= $cur ?> / <?= $max ?></span>
        </div>
    </div>

    <p class="text-dark mb-4 text-start bg-light p-4 rounded-3" style="line-height:1.8;text-indent:2em;"><?= nl2br(e($club['description'])) ?></p>

    <hr class="my-4">
    <h5 class="fw-bold mb-3">สแกน QR Code เข้ากลุ่ม (สำหรับสมาชิก)</h5>
    <?php if (assetExists($club['qr_code'])): ?>
        <img src="<?= asset($club['qr_code']) ?>" alt="QR Code" class="img-fluid mb-4 border rounded p-2" style="max-width:200px;">
    <?php else: ?>
        <p class="text-muted small border p-3 bg-light rounded d-inline-block">ยังไม่มี QR Code</p>
    <?php endif; ?>

    <div class="mt-4">
        <?php if ($_SESSION['role'] === 'student'): ?>
            <?php if ($appStatus === null): ?>
                <?php if ($isFull): ?>
                    <div class="alert alert-danger py-3 fw-bold rounded-3">❌ ชมรมนี้สมาชิกเต็มแล้ว ไม่สามารถสมัครได้</div>
                <?php else: ?>
                    <form action="<?= url('applications/apply') ?>" method="POST">
                        <input type="hidden" name="club_id" value="<?= (int) $club['id'] ?>">
                        <button type="submit" class="btn-primary-custom w-100 py-3 fs-5">สมัครเข้าชมรมนี้</button>
                    </form>
                <?php endif; ?>
            <?php elseif ($appStatus === 'pending'): ?>
                <div class="alert alert-warning py-3 fw-bold rounded-3">⏳ คุณได้ส่งคำขอแล้ว รอการอนุมัติ</div>
            <?php elseif ($appStatus === 'approved'): ?>
                <div class="alert alert-success py-3 fw-bold rounded-3">✅ คุณเป็นสมาชิกชมรมนี้แล้ว</div>
            <?php else: ?>
                <div class="alert alert-danger py-3 fw-bold rounded-3">❌ คำขอสมัครถูกปฏิเสธ</div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-info py-2 rounded-3 small">สิทธิ์ผู้ดูแล/ประธาน ไม่สามารถสมัครชมรมได้</div>
        <?php endif; ?>
    </div>
</div>
</div>
