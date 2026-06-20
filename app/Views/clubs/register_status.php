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
    <div class="col-lg-9 col-md-10">
        <!-- Academic Modern Card -->
        <div class="academic-card shadow-lg border-0 overflow-hidden">
            <!-- Header Banner with Gradient and Gold Ribbon -->
            <div class="academic-header text-center text-white py-5 px-4 position-relative">
                <div class="academic-pattern"></div>
                <div class="position-relative z-index-2">
                    <span class="badge badge-academic-accent mb-2">MCRU ONLINE DIRECTORY</span>
                    <h2 class="fw-bold mb-2 text-white">สถานะการยื่นเสนอขอเพิ่มข้อมูลชมรม</h2>
                    <p class="mb-0 text-white opacity-75 fw-light">ตรวจสอบขั้นตอนและติดตามความคืบหน้าการยื่นขอเพิ่มข้อมูลเข้าสู่ระบบออนไลน์</p>
                </div>
            </div>

            <div class="academic-body p-4 p-md-5">
                <!-- Submission Info Panel -->
                <div class="info-panel mb-4 p-4 rounded-4 shadow-sm border position-relative overflow-hidden">
                    <div class="info-pattern"></div>
                    <div class="position-relative z-index-2 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                        <div>
                            <span class="text-uppercase text-muted font-monospace small tracking-wider">ชื่อชมรมที่เสนอ</span>
                            <h4 class="fw-bold text-dark mb-1 mt-1"><?= e($club['club_name']) ?></h4>
                            <p class="text-muted small mb-0"><i class="fa-solid fa-user me-2 text-primary"></i>ผู้ยื่นเสนอข้อมูล: <?= e($_SESSION['name']) ?></p>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <?php if ($club['status'] === 'pending'): ?>
                                <span class="badge badge-status badge-status-pending"><i class="fa-solid fa-spinner fa-spin me-2"></i>อยู่ระหว่างตรวจสอบ</span>
                            <?php elseif ($club['status'] === 'approved'): ?>
                                <span class="badge badge-status badge-status-approved"><i class="fa-solid fa-circle-check me-2"></i>อนุมัติสำเร็จ</span>
                            <?php else: ?>
                                <span class="badge badge-status badge-status-rejected"><i class="fa-solid fa-circle-xmark me-2"></i>ปฏิเสธข้อเสนอ</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Academic Notice (Disclaimer) -->
                <div class="academic-notice mb-5">
                    <div class="d-flex align-items-start">
                        <div class="notice-icon-wrapper me-3">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <h5 class="notice-title">ชี้แจงบทบาทและข้อกำหนดของระบบ</h5>
                            <p class="notice-text mb-0">
                                <strong>หมายเหตุสำคัญ:</strong> ระบบออนไลน์นี้เป็นเพียงช่องทางเสนอขอเพิ่มข้อมูลรายละเอียดชมรมเข้าสู่ระบบสารสนเทศของมหาวิทยาลัยเท่านั้น <strong>ไม่ใช่การจัดตั้งชมรมอย่างเป็นทางการ</strong> โดยการพิจารณาอนุมัติจัดตั้งชมรมจริงตามระเบียบสถาบันจะต้องดำเนินการผ่านทาง <strong>กองพัฒนานักศึกษา</strong> ตามขั้นตอนทางเอกสารของมหาวิทยาลัย
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Stepper Container -->
                <div class="stepper-wrapper-custom mb-5">
                    <div class="stepper-line">
                        <div class="stepper-line-progress" style="width: <?= $progressWidth ?>;"></div>
                    </div>
                    
                    <!-- Step 1 -->
                    <div class="step-node completed">
                        <div class="step-icon-outer">
                            <div class="step-icon-inner"><i class="fa-solid fa-check"></i></div>
                        </div>
                        <div class="step-label">ยื่นเสนอคำขอ</div>
                        <div class="step-date text-muted small mt-1">ส่งคำขอสำเร็จ</div>
                    </div>
                    
                    <!-- Step 2 -->
                    <?php if ($club['status'] === 'pending'): ?>
                        <div class="step-node active pulse">
                            <div class="step-icon-outer">
                                <div class="step-icon-inner"><i class="fa-solid fa-hourglass-half"></i></div>
                            </div>
                            <div class="step-label text-warning-ink fw-bold">รอการตรวจสอบ</div>
                            <div class="step-date text-muted small mt-1">กำลังพิจารณา</div>
                        </div>
                        <div class="step-node">
                            <div class="step-icon-outer">
                                <div class="step-icon-inner"><i class="fa-solid fa-circle-check"></i></div>
                            </div>
                            <div class="step-label">อนุมัติเพิ่มข้อมูล</div>
                            <div class="step-date text-muted small mt-1">ขั้นตอนสุดท้าย</div>
                        </div>
                    <?php elseif ($club['status'] === 'approved'): ?>
                        <div class="step-node completed">
                            <div class="step-icon-outer">
                                <div class="step-icon-inner"><i class="fa-solid fa-check"></i></div>
                            </div>
                            <div class="step-label">ตรวจสอบแล้ว</div>
                            <div class="step-date text-muted small mt-1">ผ่านเกณฑ์</div>
                        </div>
                        <div class="step-node completed success-glow">
                            <div class="step-icon-outer">
                                <div class="step-icon-inner"><i class="fa-solid fa-circle-check"></i></div>
                            </div>
                            <div class="step-label text-success fw-bold">อนุมัติสำเร็จ</div>
                            <div class="step-date text-success small mt-1">บันทึกข้อมูลเข้าระบบ</div>
                        </div>
                    <?php else: // rejected ?>
                        <div class="step-node completed">
                            <div class="step-icon-outer">
                                <div class="step-icon-inner"><i class="fa-solid fa-check"></i></div>
                            </div>
                            <div class="step-label">ตรวจสอบแล้ว</div>
                            <div class="step-date text-muted small mt-1">พิจารณาแล้ว</div>
                        </div>
                        <div class="step-node rejected error-glow">
                            <div class="step-icon-outer">
                                <div class="step-icon-inner"><i class="fa-solid fa-circle-xmark"></i></div>
                            </div>
                            <div class="step-label text-danger fw-bold">ปฏิเสธข้อเสนอ</div>
                            <div class="step-date text-danger small mt-1">ไม่ผ่านการอนุมัติ</div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Description / Action Area -->
                <div class="action-box border-top pt-4 mt-5">
                    <?php if ($club['status'] === 'pending'): ?>
                        <div class="alert alert-info-custom p-4 d-flex align-items-start">
                            <div class="alert-icon-wrapper text-info me-3">
                                <i class="fa-solid fa-circle-info fs-3"></i>
                            </div>
                            <div>
                                <h5 class="alert-title fw-bold text-dark">อยู่ระหว่างการตรวจสอบรายละเอียดข้อมูล</h5>
                                <p class="alert-text text-muted mb-0">คณะกรรมการและผู้ดูแลระบบกำลังดำเนินการตรวจสอบข้อมูลเบื้องต้น วัตถุประสงค์ และภาพลักษณ์ที่เกี่ยวข้องกับชมรม โดยปกติจะใช้เวลา 1-3 วันทำการ โปรดกลับมาตรวจสอบหน้าสถานะนี้อีกครั้งภายหลัง</p>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <a href="<?= url('clubs') ?>" class="btn btn-academic-secondary">
                                <i class="fa-solid fa-circle-chevron-left me-2"></i>กลับสู่หน้ารายชื่อชมรม
                            </a>
                        </div>
                    <?php elseif ($club['status'] === 'approved'): ?>
                        <div class="alert alert-success-custom p-4 d-flex align-items-start mb-4">
                            <div class="alert-icon-wrapper text-success me-3">
                                <i class="fa-solid fa-circle-check fs-3"></i>
                            </div>
                            <div>
                                <h5 class="alert-title fw-bold text-dark">บันทึกข้อมูลและอนุมัติเข้าระบบเรียบร้อยแล้ว!</h5>
                                <p class="alert-text text-muted mb-0">ขอแสดงความยินดี! ข้อเสนอข้อมูลชมรมของคุณผ่านการตรวจสอบและบันทึกเข้าสู่สารสนเทศมหาวิทยาลัยออนไลน์เป็นที่เรียบร้อย และระบบได้อัปเกรดให้บัญชีผู้ใช้งานนี้เป็นผู้ดูแลระบบหลังบ้านของชมรม (ประธานชมรม) ในระบบเรียบร้อยแล้ว</p>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <a href="<?= url('cluboffice') ?>" class="btn btn-academic-primary border-0 px-5 py-3">
                                <i class="fa-solid fa-arrow-right-to-bracket me-2 fs-5"></i>เข้าสู่แผงจัดการหลังบ้านชมรม
                            </a>
                        </div>
                    <?php else: // rejected ?>
                        <div class="alert alert-danger-custom p-4 d-flex align-items-start mb-4">
                            <div class="alert-icon-wrapper text-danger me-3">
                                <i class="fa-solid fa-triangle-exclamation fs-3"></i>
                            </div>
                            <div>
                                <h5 class="alert-title fw-bold text-dark">ข้อเสนอขอเพิ่มข้อมูลชมรมไม่ผ่านการอนุมัติ</h5>
                                <p class="alert-text text-muted mb-0">ขออภัย คณะกรรมการพิจารณาตรวจสอบข้อมูลแล้วพบว่ารายละเอียดข้อมูลบางส่วนไม่สอดคล้องตามเงื่อนไขหรือระเบียบของระบบสารสนเทศนักศึกษา หรือข้อมูลยังไม่สมบูรณ์เพียงพอ</p>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <a href="<?= url('clubs/register/reset') ?>" class="btn btn-academic-danger border-0 px-5 py-3 text-decoration-none" data-confirm="ยืนยันต้องการเคลียร์ประวัติและยื่นเสนอขอเพิ่มข้อมูลชมรมใหม่อีกครั้ง?">
                                <i class="fa-solid fa-rotate-left me-2 fs-5"></i>ยื่นข้อเสนอใหม่อีกครั้ง
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* --- Academic Modern Styles for Status --- */
.academic-card {
    background: var(--surface);
    border-radius: 24px;
    box-shadow: 0 15px 35px rgba(11, 44, 92, 0.08) !important;
    border: 1px solid var(--border) !important;
    margin-bottom: 40px;
}

.academic-header {
    background: linear-gradient(135deg, #0b2c5c 0%, #1a4980 100%);
    position: relative;
    border-bottom: 4px solid var(--accent-gold);
}

.academic-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.15;
    background-image: radial-gradient(circle at 100% 150%, #ffffff 24%, #0b2c5c 24%, #0b2c5c 28%, #ffffff 28%, #ffffff 36%, transparent 36%, transparent),
                      radial-gradient(circle at 0% 150%, #ffffff 24%, #0b2c5c 24%, #0b2c5c 28%, #ffffff 28%, #ffffff 36%, transparent 36%, transparent);
    background-size: 40px 40px;
    z-index: 1;
}

.badge-academic-accent {
    background: rgba(249, 168, 38, 0.15);
    color: var(--accent-gold);
    font-size: 0.75rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 50px;
    border: 1px solid rgba(249, 168, 38, 0.3);
}

/* Info Panel Styling */
.info-panel {
    background: var(--bg-light);
    border-color: var(--border-strong) !important;
    border-left: 5px solid var(--primary-blue) !important;
}

.info-pattern {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 150px;
    opacity: 0.03;
    background: repeating-linear-gradient(45deg, var(--primary-blue), var(--primary-blue) 10px, transparent 10px, transparent 20px);
}

.tracking-wider {
    letter-spacing: 1px;
}

.badge-status {
    padding: 10px 18px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
}

.badge-status-pending {
    background-color: var(--warning-bg);
    color: var(--warning-ink);
    border: 1px solid rgba(180, 83, 9, 0.2);
}

.badge-status-approved {
    background-color: var(--success-bg);
    color: var(--success-ink);
    border: 1px solid rgba(31, 122, 82, 0.2);
}

.badge-status-rejected {
    background-color: var(--danger-bg);
    color: var(--danger-ink);
    border: 1px solid rgba(192, 57, 43, 0.2);
}

/* Academic Notice (Disclaimer) */
.academic-notice {
    background: rgba(249, 168, 38, 0.04);
    border: 1px dashed rgba(249, 168, 38, 0.4);
    border-left: 5px solid var(--accent-gold);
    border-radius: 16px;
    padding: 20px;
}

.notice-icon-wrapper {
    background: rgba(249, 168, 38, 0.12);
    color: var(--accent-gold-deep);
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.notice-title {
    color: var(--text-dark);
    font-weight: 700;
    font-size: 1.05rem;
    margin-bottom: 6px;
}

.notice-text {
    font-size: 0.9rem;
    line-height: 1.6;
    color: var(--text-muted);
}

/* Stepper Modern Custom */
.stepper-wrapper-custom {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    position: relative;
    padding: 20px 10px;
}

.stepper-line {
    position: absolute;
    top: 50px;
    left: 8%;
    right: 8%;
    height: 4px;
    background-color: var(--border-strong);
    z-index: 1;
}

.stepper-line-progress {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-soft) 0%, var(--success) 100%);
    transition: width 0.6s ease;
}

.step-node {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
}

.step-icon-outer {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: var(--surface);
    border: 4px solid var(--border-strong);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.step-icon-inner {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background-color: var(--bg-light);
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    transition: all 0.4s ease;
}

.step-label {
    margin-top: 15px;
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--text-muted);
}

.step-date {
    font-size: 0.8rem;
    color: var(--text-muted);
}

/* completed state */
.step-node.completed .step-icon-outer {
    border-color: var(--success);
    box-shadow: 0 4px 15px rgba(31, 122, 82, 0.2);
}

.step-node.completed .step-icon-inner {
    background-color: var(--success);
    color: #fff;
}

.step-node.completed .step-label {
    color: var(--success-ink);
}

/* active state */
.step-node.active .step-icon-outer {
    border-color: var(--accent-gold);
    box-shadow: 0 4px 15px rgba(249, 168, 38, 0.3);
}

.step-node.active .step-icon-inner {
    background-color: var(--accent-gold);
    color: #fff;
}

.step-node.active .step-label {
    color: var(--warning-ink);
}

/* rejected state */
.step-node.rejected .step-icon-outer {
    border-color: var(--danger);
    box-shadow: 0 4px 15px rgba(192, 57, 43, 0.2);
}

.step-node.rejected .step-icon-inner {
    background-color: var(--danger);
    color: #fff;
}

.step-node.rejected .step-label {
    color: var(--danger-ink);
}

/* Pulse animation for active step */
@keyframes pulseGlow {
    0% {
        box-shadow: 0 0 0 0 rgba(249, 168, 38, 0.5);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(249, 168, 38, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(249, 168, 38, 0);
    }
}
.step-node.active.pulse .step-icon-outer {
    animation: pulseGlow 2s infinite;
}

/* Glow effects for final steps */
@keyframes successGlowAnimation {
    0% { box-shadow: 0 4px 15px rgba(31, 122, 82, 0.2); }
    50% { box-shadow: 0 4px 25px rgba(31, 122, 82, 0.5); }
    100% { box-shadow: 0 4px 15px rgba(31, 122, 82, 0.2); }
}
.step-node.success-glow .step-icon-outer {
    animation: successGlowAnimation 3s infinite;
}

@keyframes errorGlowAnimation {
    0% { box-shadow: 0 4px 15px rgba(192, 57, 43, 0.2); }
    50% { box-shadow: 0 4px 25px rgba(192, 57, 43, 0.5); }
    100% { box-shadow: 0 4px 15px rgba(192, 57, 43, 0.2); }
}
.step-node.error-glow .step-icon-outer {
    animation: errorGlowAnimation 3s infinite;
}

/* Custom Alert Layouts */
.alert-info-custom {
    background: rgba(26, 73, 128, 0.03);
    border: 1px solid rgba(26, 73, 128, 0.15);
    border-left: 4px solid var(--primary-soft);
    border-radius: 16px;
}

.alert-success-custom {
    background: rgba(31, 122, 82, 0.03);
    border: 1px solid rgba(31, 122, 82, 0.15);
    border-left: 4px solid var(--success);
    border-radius: 16px;
}

.alert-danger-custom {
    background: rgba(192, 57, 43, 0.03);
    border: 1px solid rgba(192, 57, 43, 0.15);
    border-left: 4px solid var(--danger);
    border-radius: 16px;
}

.alert-icon-wrapper {
    background: #ffffff;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    flex-shrink: 0;
}

.alert-title {
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: 6px;
}

.alert-text {
    font-size: 0.9rem;
    line-height: 1.6;
}

/* Buttons */
.btn-academic-primary {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-soft) 100%);
    color: #ffffff !important;
    font-weight: 600;
    padding: 14px 36px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(11, 44, 92, 0.2);
    transition: all var(--dur) var(--ease-out);
    display: inline-flex;
    align-items: center;
}

.btn-academic-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(11, 44, 92, 0.3);
    background: linear-gradient(135deg, var(--primary-soft) 0%, #205c9e 100%);
}

.btn-academic-primary:active {
    transform: translateY(1px);
    box-shadow: 0 2px 10px rgba(11, 44, 92, 0.2);
}

.btn-academic-secondary {
    background: transparent;
    color: var(--text-dark) !important;
    border: 1.5px solid var(--border) !important;
    font-weight: 600;
    padding: 12px 28px;
    border-radius: 50px;
    transition: all var(--dur) var(--ease-out);
    display: inline-flex;
    align-items: center;
}

.btn-academic-secondary:hover {
    background: var(--bg-light);
    border-color: var(--text-muted) !important;
    transform: translateY(-1px);
}

.btn-academic-danger {
    background: linear-gradient(135deg, var(--danger) 0%, #d64535 100%);
    color: #ffffff !important;
    font-weight: 600;
    padding: 14px 36px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(192, 57, 43, 0.2);
    transition: all var(--dur) var(--ease-out);
    display: inline-flex;
    align-items: center;
}

.btn-academic-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(192, 57, 43, 0.3);
}

/* Responsive adjustment for stepper */
@media (max-width: 768px) {
    .stepper-wrapper-custom {
        flex-direction: column;
        align-items: flex-start;
        padding-left: 30px;
        gap: 30px;
    }
    
    .stepper-line {
        top: 20px;
        left: 38px;
        width: 4px;
        height: calc(100% - 60px);
    }
    
    .stepper-line-progress {
        width: 100% !important;
        height: 0%;
        transition: height 0.6s ease;
    }
    
    /* We can dynamically inject vertical height using php if needed, but a simple 100% height line under vertical stack is clean enough */
    .stepper-line-progress {
        height: <?= $club['status'] === 'pending' ? '50%' : '100%' ?>;
    }
    
    .step-node {
        flex-direction: row;
        text-align: left;
        width: 100%;
    }
    
    .step-label {
        margin-top: 0;
        margin-left: 20px;
        font-size: 1.05rem;
    }
    
    .step-date {
        margin-left: 20px;
    }
}
</style>
