<?php
/**
 * @var int $totalMembers
 * @var int $uniqueFaculties
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

<!-- Phase II Verification Section -->
<div class="card-custom p-4 border shadow-sm mb-4" style="background: var(--surface); border-color: var(--border);">
    <h5 class="text-primary-custom fw-bold mb-3 border-bottom pb-2">
        <i class="fa-solid fa-scale-balanced me-2"></i>ขั้นตอนจัดตั้งระยะที่ 2: การตรวจสอบคุณสมบัติและจำนวนสมาชิก
    </h5>
    
    <?php if ($club['member_verification_status'] === 'approved'): ?>
        <div class="alert alert-success d-flex align-items-center mb-0 p-4 rounded-3 border-0">
            <i class="fa-solid fa-circle-check fs-2 me-4 text-success"></i>
            <div>
                <h6 class="fw-bold text-success mb-1">ชมรมได้รับการก่อตั้งเสร็จสมบูรณ์เรียบร้อยแล้ว!</h6>
                <p class="mb-0 small text-muted">รายชื่อและสัดส่วนคณะของสมาชิกชมรมได้รับการพิจารณาและอนุมัติผ่านเกณฑ์จากกองพัฒนานักศึกษาเรียบร้อยแล้ว ชมรมเปิดใช้งานเป็นทางการอย่างสมบูรณ์แบบ</p>
            </div>
        </div>
    <?php elseif ($club['member_verification_status'] === 'pending'): ?>
        <div class="alert alert-warning d-flex align-items-center mb-0 p-4 rounded-3 border-0">
            <i class="fa-solid fa-spinner fa-spin fs-2 me-4 text-warning"></i>
            <div>
                <h6 class="fw-bold text-warning-ink mb-1">อยู่ระหว่างการตรวจสอบรายชื่อโดยกองพัฒนานักศึกษา</h6>
                <p class="mb-0 small text-muted">เจ้าหน้าที่กำลังพิจารณาคุณสมบัติสมาชิกและสัดส่วนคณะชมรมของคุณ โปรดตรวจสอบความคืบหน้าของสถานะหน้านี้อีกครั้งภายหลัง</p>
            </div>
        </div>
    <?php else: // not_submitted or correcting ?>
        <?php if ($club['member_verification_status'] === 'correcting'): ?>
            <div class="alert alert-danger p-3 mb-4 rounded-3 text-start border-0">
                <h6 class="fw-bold mb-1"><i class="fa-solid fa-circle-xmark me-2"></i>เจ้าหน้าที่ส่งกลับแก้ไขการจัดทำรายชื่อสมาชิก</h6>
                <p class="mb-2 small text-muted">กรุณาปรับปรุงรายชื่อสมาชิกชมรมตามคำแนะนำด้านล่าง:</p>
                <div class="p-3 bg-white rounded border border-danger-subtle font-monospace text-dark" style="white-space: pre-wrap; font-size: 0.9rem;"><?= e($club['member_verification_comment']) ?></div>
            </div>
        <?php endif; ?>

        <div class="row g-4 align-items-center text-start">
            <div class="col-lg-8">
                <p class="mb-3 text-muted small"><strong>เงื่อนไขการอนุมัติการจัดตั้งสมบูรณ์:</strong> ชมรมต้องรวบรวมสมาชิกอย่างน้อย 50 คน และใน 50 คนนั้นจะต้องมาจากอย่างน้อย 3 คณะที่ต่างกัน (คุณสามารถกดส่งรายชื่อให้ตรวจสอบก่อนล่วงหน้าได้ทันที)</p>
                
                <div class="d-flex gap-3 flex-wrap">
                    <div class="d-flex align-items-center">
                        <span class="badge <?= $totalMembers >= 50 ? 'bg-success' : 'bg-secondary' ?> p-2 rounded-circle me-2">
                            <i class="fa-solid <?= $totalMembers >= 50 ? 'fa-check' : 'fa-xmark' ?>"></i>
                        </span>
                        <span class="small fw-bold">สมาชิกทั้งหมด: <?= $totalMembers ?> / 50 คน</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge <?= $uniqueFaculties >= 3 ? 'bg-success' : 'bg-secondary' ?> p-2 rounded-circle me-2">
                            <i class="fa-solid <?= $uniqueFaculties >= 3 ? 'fa-check' : 'fa-xmark' ?>"></i>
                        </span>
                        <span class="small fw-bold">มาจากคณะที่ต่างกัน: <?= $uniqueFaculties ?> / 3 คณะ</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <form method="POST" action="<?= url('cluboffice/verify-members') ?>">
                    <button type="submit" class="btn btn-primary-custom py-3 px-4 shadow-sm" onclick="return confirm('ยืนยันส่งรายชื่อสมาชิกทั้งหมดให้เจ้าหน้าที่ตรวจสอบ?')">
                        <i class="fa-solid fa-paper-plane me-2"></i>ส่งรายชื่อให้ตรวจสอบ
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <h4 class="text-primary-custom fw-bold mb-3 border-bottom pb-2">ยินดีต้อนรับสู่ระบบจัดการชมรม</h4>
    <p class="m-0 text-muted">ใช้เมนูด้านซ้ายเพื่อเริ่มต้นการจัดการข้อมูลพื้นฐานชมรม, การอนุมัติผู้สมัคร, สมาชิกและตำแหน่งภายในชมรม รวมทั้งกิจกรรมและคลังรูปภาพ</p>
</div>
