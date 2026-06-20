<?php
/**
 * @var int $totalClubs
 * @var int $pendingApps
 * @var int $totalAnnouncements
 * @var int $totalEvents
 * @var string $role
 */
?>

<div class="mb-4">
    <h4 class="text-primary-custom fw-bold m-0">
        <i class="fa-solid fa-chart-pie me-2 text-warning"></i>แผงควบคุมระบบจัดการ (Backoffice Dashboard)
    </h4>
    <p class="text-muted small">ยินดีต้อนรับเข้าสู่ระบบจัดการข้อมูลชมรมและกิจกรรมนักศึกษา มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง</p>
</div>

<!-- Statistics Cards Grid -->
<div class="row g-3 mb-4">
    <?php if ($role === 'admin'): ?>
        <div class="col-sm-6 col-lg-3">
            <div class="card-custom p-3 border-0 bg-primary bg-opacity-10 text-primary h-100 d-flex align-items-center justify-content-between shadow-sm">
                <div>
                    <h6 class="text-muted small fw-bold mb-1">ชมรมทั้งหมด</h6>
                    <h3 class="fw-bold m-0 text-dark"><?= $totalClubs ?></h3>
                </div>
                <div class="fs-1 opacity-50 text-primary"><i class="fa-solid fa-layer-group"></i></div>
            </div>
        </div>
    <?php else: ?>
        <div class="col-sm-6 col-lg-3">
            <div class="card-custom p-3 border-0 bg-primary bg-opacity-10 text-primary h-100 d-flex align-items-center justify-content-between shadow-sm">
                <div>
                    <h6 class="text-muted small fw-bold mb-1">ชมรมของคุณ</h6>
                    <h3 class="fw-bold m-0 text-dark">1</h3>
                </div>
                <div class="fs-1 opacity-50 text-primary"><i class="fa-solid fa-house-laptop"></i></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-sm-6 col-lg-3">
        <div class="card-custom p-3 border-0 bg-success bg-opacity-10 text-success h-100 d-flex align-items-center justify-content-between shadow-sm">
            <div>
                <h6 class="text-muted small fw-bold mb-1">คำขอสมัครสมาชิกใหม่</h6>
                <h3 class="fw-bold m-0 text-dark"><?= $pendingApps ?></h3>
            </div>
            <div class="fs-1 opacity-50 text-success"><i class="fa-solid fa-user-clock"></i></div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card-custom p-3 border-0 bg-danger bg-opacity-10 text-danger h-100 d-flex align-items-center justify-content-between shadow-sm">
            <div>
                <h6 class="text-muted small fw-bold mb-1">ข่าวสารประชาสัมพันธ์</h6>
                <h3 class="fw-bold m-0 text-dark"><?= $totalAnnouncements ?></h3>
            </div>
            <div class="fs-1 opacity-50 text-danger"><i class="fa-solid fa-bullhorn"></i></div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card-custom p-3 border-0 bg-info bg-opacity-10 text-info h-100 d-flex align-items-center justify-content-between shadow-sm">
            <div>
                <h6 class="text-muted small fw-bold mb-1">กิจกรรมปฏิทิน</h6>
                <h3 class="fw-bold m-0 text-dark"><?= $totalEvents ?></h3>
            </div>
            <div class="fs-1 opacity-50 text-info"><i class="fa-regular fa-calendar-check"></i></div>
        </div>
    </div>
</div>

<!-- Guidance Panel -->
<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <h5 class="fw-bold text-primary-custom mb-3">
        <i class="fa-solid fa-circle-info me-2 text-warning"></i>คำแนะนำและคู่มือการใช้งานเบื้องต้น
    </h5>
    <ul class="text-muted mb-0" style="line-height: 1.8; font-size: 0.95rem;">
        <li>ใช้ <strong>เมนูควบคุมในแถบด้านซ้ายมือ (Sidebar Menu)</strong> เพื่อสลับไปจัดการงานในด้านต่าง ๆ ได้ทันที</li>
        <li><strong>การจัดการข้อมูลชมรม:</strong> ผู้ดูแลระบบ (Admin) สามารถเพิ่ม/ลบชมรมและแต่งตั้งประธานชมรมได้ ส่วนประธานชมรมสามารถเข้าแก้ไขภาพโลโก้ คิวอาร์โค้ด และข้อมูลแนะนำของตนเองได้</li>
        <li><strong>การจัดการคำขอสมัครสมาชิก:</strong> ประธานชมรมและผู้ดูแลระบบสามารถเข้าอนุมัติหรือปฏิเสธคำขอสมัครเข้าร่วมชมรมของนักศึกษา</li>
        <li><strong>การจัดการสมาชิก & ตำแหน่ง:</strong> สำหรับจัดสรรตำแหน่งต่างๆ ภายในชมรม (เช่น รองประธาน, ฝ่ายประชาสัมพันธ์, เลขานุการ ฯลฯ) ให้แก่สมาชิกที่อนุมัติแล้ว</li>
        <li><strong>การจัดการข้อมูลเนื้อหา:</strong> (ข่าวสาร PR, ปฏิทินกิจกรรม, แกลเลอรี) ใช้เขียนโพสต์แจ้งข่าวประชาสัมพันธ์, เพิ่มข้อมูลกิจกรรมใหม่ และอัปโหลดภาพกิจกรรมลงคลังผลงาน</li>
    </ul>
</div>
