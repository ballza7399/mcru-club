<?php
/**
 * @var array $club
 */
$progressWidth = '0%';
if ($club['status'] === 'pending') {
    $progressWidth = '50%';
} elseif ($club['status'] === 'approved' || $club['status'] === 'rejected') {
    $progressWidth = '100%';
}
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-custom p-5 border shadow-sm text-center" style="background: var(--surface); border-color: var(--border);">
            
            <h3 class="text-primary-custom fw-bold mb-4"><i class="fa-solid fa-file-waveform text-warning me-2"></i>สถานะการยื่นเสนอขอเพิ่มข้อมูลชมรม</h3>
            
            <div class="mb-4 bg-light p-3 rounded text-start" style="border-left: 4px solid var(--accent-gold);">
                <h5 class="fw-bold text-dark m-0"><?= e($club['club_name']) ?></h5>
                <p class="text-muted small m-0 mt-1">ผู้ยื่นเสนอข้อมูล: <?= e($_SESSION['name']) ?></p>
            </div>

            <div class="alert alert-warning mb-4 small text-start">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>หมายเหตุสำคัญ:</strong> ระบบนี้เป็นเพียงช่องทางสำหรับเสนอขอเพิ่มข้อมูลชมรมเข้าสู่ระบบออนไลน์ของมหาวิทยาลัยเท่านั้น ไม่ใช่การจัดตั้งชมรมอย่างเป็นทางการโดยตรง โดยกระบวนการจัดตั้งชมรมอย่างเป็นทางการจริงจะดำเนินการผ่านทาง <strong>กองพัฒนานักศึกษา</strong> ตามระเบียบของมหาวิทยาลัย
            </div>

            <!-- Stepper Container -->
            <div class="stepper-wrapper">
                <div class="stepper-progress" style="width: <?= $progressWidth ?>;"></div>
                
                <div class="stepper-item completed">
                    <div class="step-counter"><i class="fa-solid fa-check"></i></div>
                    <div class="step-name">ยื่นเสนอคำขอ</div>
                </div>
                
                <?php if ($club['status'] === 'pending'): ?>
                    <div class="stepper-item active">
                        <div class="step-counter"><i class="fa-solid fa-clock-rotate-left"></i></div>
                        <div class="step-name">รอการตรวจสอบ</div>
                    </div>
                    <div class="stepper-item">
                        <div class="step-counter"><i class="fa-solid fa-circle"></i></div>
                        <div class="step-name">อนุมัติเพิ่มข้อมูล</div>
                    </div>
                <?php elseif ($club['status'] === 'approved'): ?>
                    <div class="stepper-item completed">
                        <div class="step-counter"><i class="fa-solid fa-check"></i></div>
                        <div class="step-name">ตรวจสอบแล้ว</div>
                    </div>
                    <div class="stepper-item completed">
                        <div class="step-counter"><i class="fa-solid fa-circle-check"></i></div>
                        <div class="step-name">อนุมัติเพิ่มข้อมูลสำเร็จ</div>
                    </div>
                <?php else: // rejected ?>
                    <div class="stepper-item completed">
                        <div class="step-counter"><i class="fa-solid fa-check"></i></div>
                        <div class="step-name">ตรวจสอบแล้ว</div>
                    </div>
                    <div class="stepper-item rejected">
                        <div class="step-counter"><i class="fa-solid fa-circle-xmark"></i></div>
                        <div class="step-name">ปฏิเสธข้อเสนอ</div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Description / Action -->
            <div class="mt-5 pt-3 border-top text-start">
                <?php if ($club['status'] === 'pending'): ?>
                    <div class="alert alert-info d-flex align-items-center m-0">
                        <i class="fa-solid fa-circle-info fs-4 me-3 text-info"></i>
                        <div>
                            <strong class="d-block mb-1">อยู่ระหว่างตรวจสอบข้อมูล</strong>
                            <span class="text-muted small">คณะกรรมการผู้ดูแลระบบกำลังตรวจสอบรายละเอียด วัตถุประสงค์ และภาพรวมของชมรมของคุณ โปรดรอการอนุมัติ</span>
                        </div>
                    </div>
                <?php elseif ($club['status'] === 'approved'): ?>
                    <div class="alert alert-success d-flex align-items-center mb-3">
                        <i class="fa-solid fa-circle-check fs-4 me-3 text-success"></i>
                        <div>
                            <strong class="d-block mb-1">อนุมัติเพิ่มข้อมูลชมรมเข้าระบบสำเร็จแล้ว!</strong>
                            <span class="text-muted small">ยินดีด้วย! ข้อมูลชมรมของคุณได้รับการบันทึกและอนุมัติเข้าระบบแล้ว และบัญชีผู้ใช้นี้ได้รับสิทธิ์การเป็นประธานชมรมเป็นที่เรียบร้อย</span>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="<?= url('cluboffice') ?>" class="btn-primary-custom rounded-pill px-4 py-2 border-0 d-inline-block text-decoration-none">
                            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> เข้าสู่แผงจัดการหลังบ้านชมรม
                        </a>
                    </div>
                <?php else: // rejected ?>
                    <div class="alert alert-danger d-flex align-items-center mb-3">
                        <i class="fa-solid fa-triangle-exclamation fs-4 me-3 text-danger"></i>
                        <div>
                            <strong class="d-block mb-1">ปฏิเสธข้อเสนอขอเพิ่มข้อมูลชมรม</strong>
                            <span class="text-muted small">ขออภัย ข้อเสนอขอเพิ่มข้อมูลชมรมนี้ไม่ผ่านเกณฑ์การพิจารณาตรวจสอบจากคณะกรรมการ</span>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="<?= url('clubs/register/reset') ?>" class="btn btn-outline-danger rounded-pill px-4 text-decoration-none" data-confirm="ยืนยันต้องการเคลียร์ประวัติและยื่นข้อเสนอขอเพิ่มข้อมูลชมรมใหม่อีกครั้ง?">
                            <i class="fa-solid fa-rotate-left me-1"></i> ยื่นข้อเสนอใหม่อีกครั้ง
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<style>
/* Stepper CSS System */
.stepper-wrapper {
    display: flex;
    justify-content: space-between;
    position: relative;
    max-width: 600px;
    margin: 40px auto 20px auto;
}
.stepper-wrapper::before {
    content: "";
    position: absolute;
    top: 27px;
    left: 0;
    width: 100%;
    height: 4px;
    background: #e2e8f0;
    z-index: 1;
}
.stepper-progress {
    position: absolute;
    top: 27px;
    left: 0;
    height: 4px;
    background: #28a745;
    z-index: 1;
    transition: width 0.4s ease;
}
.stepper-item {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 120px;
}
.step-counter {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #cbd5e1;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: bold;
    border: 4px solid var(--surface, #fff);
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}
.stepper-item.completed .step-counter {
    background: #28a745;
}
.stepper-item.active .step-counter {
    background: #fd7e14; /* Orange */
}
.stepper-item.rejected .step-counter {
    background: #dc3545;
}
.step-name {
    margin-top: 12px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #64748b;
    text-align: center;
}
.stepper-item.completed .step-name {
    color: #28a745;
}
.stepper-item.active .step-name {
    color: #fd7e14;
    font-weight: 600;
}
.stepper-item.rejected .step-name {
    color: #dc3545;
    font-weight: 600;
}
</style>
