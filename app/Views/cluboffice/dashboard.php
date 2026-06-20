<?php
/**
 * @var int $totalMembers
 * @var int $totalApps
 * @var int $totalEvents
 * @var int $totalNews
 * @var array $club
 */
?>
<div class="row g-4 mb-4">
    <!-- Card: Members -->
    <div class="col-md-3">
        <div class="card-custom p-4 border shadow-sm text-center" style="background: var(--surface); border-color: var(--border);">
            <div class="fs-1 text-primary-custom mb-2"><i class="fa-solid fa-users"></i></div>
            <h5 class="text-muted small">สมาชิกทั้งหมด</h5>
            <h2 class="fw-bold text-dark m-0"><?= $totalMembers ?> / <?= (int)$club['max_members'] ?></h2>
        </div>
    </div>
    <!-- Card: Pending Apps -->
    <div class="col-md-3">
        <div class="card-custom p-4 border shadow-sm text-center" style="background: var(--surface); border-color: var(--border);">
            <div class="fs-1 text-warning mb-2"><i class="fa-solid fa-user-plus"></i></div>
            <h5 class="text-muted small">คำขอรอดำเนินการ</h5>
            <h2 class="fw-bold text-dark m-0"><?= $totalApps ?> รายการ</h2>
        </div>
    </div>
    <!-- Card: Events -->
    <div class="col-md-3">
        <div class="card-custom p-4 border shadow-sm text-center" style="background: var(--surface); border-color: var(--border);">
            <div class="fs-1 text-success mb-2"><i class="fa-solid fa-calendar-days"></i></div>
            <h5 class="text-muted small">กิจกรรมของชมรม</h5>
            <h2 class="fw-bold text-dark m-0"><?= $totalEvents ?> กิจกรรม</h2>
        </div>
    </div>
    <!-- Card: News -->
    <div class="col-md-3">
        <div class="card-custom p-4 border shadow-sm text-center" style="background: var(--surface); border-color: var(--border);">
            <div class="fs-1 text-info mb-2"><i class="fa-solid fa-bullhorn"></i></div>
            <h5 class="text-muted small">ข่าวสารที่ลงประกาศ</h5>
            <h2 class="fw-bold text-dark m-0"><?= $totalNews ?> ข่าว</h2>
        </div>
    </div>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <h4 class="text-primary-custom fw-bold mb-3 border-bottom pb-2">ยินดีต้อนรับสู่ระบบจัดการชมรม</h4>
    <p class="m-0 text-muted">ใช้เมนูด้านซ้ายเพื่อเริ่มต้นการจัดการข้อมูลพื้นฐานชมรม, การอนุมัติผู้สมัคร, สมาชิกและตำแหน่งภายในชมรม รวมทั้งกิจกรรมและคลังรูปภาพ</p>
</div>
