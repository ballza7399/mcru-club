<?php
/**
 * @var string $startDate
 * @var string $endDate
 */
$periodMode = getSetting('club_proposal_period_enabled', 'always_open');
$isClosedIndefinitely = ($periodMode === 'always_closed');

$startStr = !empty($startDate) ? date('d/m/Y H:i', strtotime($startDate)) : 'ไม่ระบุ';
$endStr = !empty($endDate) ? date('d/m/Y H:i', strtotime($endDate)) : 'ไม่ระบุ';
?>
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="academic-card shadow-lg border-0 overflow-hidden text-center">
            <!-- Header Banner with Warning Icon -->
            <div class="bg-danger text-white py-5 px-4 position-relative" style="border-bottom: 4px solid var(--accent-gold);">
                <div class="position-relative z-index-2">
                    <i class="fa-solid fa-clock-rotate-left fa-4x mb-3 text-warning"></i>
                    <h2 class="fw-bold mb-2">ไม่อยู่ในช่วงเปิดรับเสนอจัดตั้งชมรม</h2>
                    <p class="mb-0 opacity-90 fw-light">ระบบเปิดรับข้อเสนอขอก่อตั้งชมรมใหม่ในรอบปีการศึกษาตามวันเวลาที่กำหนด</p>
                </div>
            </div>

            <div class="p-4 p-md-5 bg-white">
                <!-- Info Panel -->
                <?php if ($isClosedIndefinitely): ?>
                    <div class="p-4 rounded-4 mb-4 border text-center border-danger-subtle" style="background: #fef2f2; border-left: 5px solid var(--danger) !important;">
                        <h5 class="fw-bold text-danger mb-0"><i class="fa-solid fa-ban me-2"></i>ระบบปิดรับข้อเสนอขอก่อตั้งชมรมชั่วคราวอย่างไม่มีกำหนด</h5>
                    </div>
                <?php else: ?>
                    <div class="p-4 rounded-4 mb-4 border text-start" style="background: rgba(11, 44, 92, 0.02); border-color: var(--border-strong) !important; border-left: 5px solid var(--danger) !important;">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-calendar-days text-danger me-2"></i>กำหนดการยื่นคำเสนอขอ (Phase I)</h5>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="small text-muted">วันเวลาเริ่มต้นเปิดรับ:</div>
                                <div class="fw-bold text-primary-custom" style="font-size: 1.1rem;"><?= $startStr ?> น.</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">วันเวลาสิ้นสุดการปิดรับ:</div>
                                <div class="fw-bold text-danger-custom" style="font-size: 1.1rem;"><?= $endStr ?> น.</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <p class="text-muted mb-4" style="line-height: 1.7;">
                    ขณะนี้ระบบขอก่อตั้งชมรมออนไลน์อยู่นอกกำหนดการรับสมัครส่งเสริมกิจกรรมนักศึกษา จึงไม่อนุญาตให้นักศึกษายื่นเสนอข้อมูลเข้ามาใหม่ได้ในขณะนี้
                    หากมีข้อสงสัยประการใดโปรดติดต่อฝ่ายกิจกรรมนักศึกษา กองพัฒนานักศึกษา มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง
                </p>

                <div class="border-top pt-4">
                    <a href="<?= url('clubs') ?>" class="btn btn-academic-primary border-0 px-4 py-2">
                        <i class="fa-solid fa-house me-2"></i>กลับสู่หน้าหลักชมรม
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.academic-card {
    background: var(--surface);
    border-radius: 24px;
    box-shadow: 0 15px 35px rgba(11, 44, 92, 0.08) !important;
    border: 1px solid var(--border) !important;
    margin-bottom: 40px;
}
.btn-academic-primary {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-soft) 100%);
    color: #ffffff !important;
    font-weight: 600;
    padding: 12px 28px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(11, 44, 92, 0.2);
    transition: all var(--dur) var(--ease-out);
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    border: none;
}
.btn-academic-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(11, 44, 92, 0.3);
    background: linear-gradient(135deg, var(--primary-soft) 0%, #205c9e 100%);
}
</style>
