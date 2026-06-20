<?php
/**
 * @var array $club
 * @var string|null $appStatus
 * @var bool $isFull
 */
?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Academic Club Detail Card -->
        <div class="academic-card shadow-lg border-0 overflow-hidden mb-5">
            <!-- Club Banner with Title and Metadata inside -->
            <div class="club-detail-banner position-relative d-flex align-items-end p-4 p-md-5" style="min-height: 240px; padding-bottom: 80px !important;">
                <div class="academic-pattern"></div>
                
                <!-- Back Button in Banner -->
                <a href="<?= url('clubs') ?>" class="btn-banner-back text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-2"></i>กลับหน้ารายชื่อชมรม
                </a>

                <!-- Title and Metadata inside Banner for perfect contrast -->
                <div class="position-relative z-index-2 w-100 text-white mt-5 text-center text-md-start">
                    <span class="badge badge-academic-accent mb-2">MCRU APPROVED CLUB</span>
                    <h2 class="fw-bold m-0 text-white text-shadow-sm" style="font-size: 2.2rem; letter-spacing: -0.5px;"><?= e($club['club_name']) ?></h2>
                    <p class="m-0 mt-2 text-white-50">
                        <i class="fa-solid fa-user-tie text-warning me-2"></i>ประธานชมรม: 
                        <strong class="text-white"><?= $club['pres_name'] ? e($club['pres_name']) : '<span class="text-danger">ยังไม่แต่งตั้ง</span>' ?></strong>
                    </p>
                </div>
            </div>

            <!-- Floating Logo Area -->
            <div class="club-profile-header px-4 px-md-5">
                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end gap-3" style="margin-top: -60px; margin-bottom: 20px;">
                    <div class="club-logo-wrapper">
                        <?php if (assetExists($club['club_logo'])): ?>
                            <img src="<?= asset($club['club_logo']) ?>" class="club-detail-logo" alt="Logo">
                        <?php else: ?>
                            <div class="club-detail-logo bg-light d-flex align-items-center justify-content-center text-muted fw-bold border">NO LOGO</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="academic-body p-4 p-md-5 pt-0">
                <div class="row g-4">
                    <!-- Left Column: Description and Objectives -->
                    <div class="col-lg-7">
                        <h5 class="section-title-custom mb-4">
                            <i class="fa-solid fa-file-invoice text-primary me-2"></i>รายละเอียดและข้อมูลทั่วไป
                        </h5>
                        <div class="club-description-box p-4 rounded-4 mb-4">
                            <?= nl2br(e($club['description'])) ?>
                        </div>
                    </div>

                    <!-- Right Column: Membership, Actions, and Contact -->
                    <div class="col-lg-5">
                        <!-- Quick Stats & Actions Card -->
                        <div class="side-widget p-4 rounded-4 mb-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="fa-solid fa-chart-simple text-warning me-2"></i>สถานะและการรับสมัคร
                            </h6>
                            
                            <!-- Capacity progress bar -->
                            <?php
                                $cur = (int) $club['current_members'];
                                $max = (int) $club['max_members'];
                                $ratio = $max > 0 ? $cur / $max : 1;
                                $percentage = min(100, round($ratio * 100));
                                $progressClass = $percentage >= 100 ? 'bg-danger' : ($percentage >= 80 ? 'bg-warning' : 'bg-success');
                            ?>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold text-muted">จำนวนสมาชิกในชมรม</span>
                                    <span class="small fw-bold text-primary-custom"><?= $cur ?> / <?= $max ?> คน</span>
                                </div>
                                <div class="progress rounded-pill shadow-sm" style="height: 10px; background-color: var(--border);">
                                    <div class="progress-bar <?= $progressClass ?> rounded-pill" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-end mt-1">
                                    <small class="text-muted font-monospace"><?= $percentage ?>% ของความจุชมรม</small>
                                </div>
                            </div>

                            <!-- Action / Join Status -->
                            <div class="action-status-container">
                                <?php if (empty($_SESSION['user_id'])): ?>
                                    <div class="alert alert-warning-custom p-3 text-start small mb-0">
                                        <i class="fa-solid fa-circle-info text-warning me-2 fs-5"></i>
                                        กรุณา <a href="<?= url('auth/login') ?>" class="fw-bold text-primary text-decoration-underline">เข้าสู่ระบบ</a> หรือ <a href="<?= url('auth/register') ?>" class="fw-bold text-primary text-decoration-underline">สมัครสมาชิก</a> เพื่อสมัครเข้าชมรมนี้
                                    </div>
                                <?php elseif ($_SESSION['role'] === 'student'): ?>
                                    <?php if ($appStatus === null): ?>
                                        <?php if ($isFull): ?>
                                            <div class="alert alert-danger-custom p-3 text-start small mb-0">
                                                <i class="fa-solid fa-circle-xmark text-danger me-2 fs-5"></i>
                                                ชมรมนี้เต็มแล้ว ไม่สามารถรับสมาชิกเพิ่มได้ในขณะนี้
                                            </div>
                                        <?php else: ?>
                                            <form action="<?= url('applications/apply') ?>" method="POST">
                                                <input type="hidden" name="club_id" value="<?= (int) $club['id'] ?>">
                                                <button type="submit" class="btn btn-academic-primary w-100 py-3 border-0">
                                                    <i class="fa-solid fa-user-plus me-2"></i>ส่งคำขอสมัครเข้าชมรมนี้
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php elseif ($appStatus === 'pending'): ?>
                                        <div class="alert alert-warning-custom p-3 text-start small mb-0">
                                            <i class="fa-solid fa-hourglass-half text-warning me-2 fs-5"></i>
                                            ยื่นคำขอเรียบร้อยแล้ว อยู่ระหว่างผู้ดูแลหรือประธานชมรมพิจารณา
                                        </div>
                                    <?php elseif ($appStatus === 'approved'): ?>
                                        <div class="alert alert-success-custom p-3 text-start small mb-0">
                                            <i class="fa-solid fa-circle-check text-success me-2 fs-5"></i>
                                            คุณได้รับการอนุมัติและเป็นสมาชิกของชมรมนี้เรียบร้อยแล้ว
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-danger-custom p-3 text-start small mb-0">
                                            <i class="fa-solid fa-circle-xmark text-danger me-2 fs-5"></i>
                                            คำขอสมัครเข้าชมรมของคุณถูกปฏิเสธ
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-info-custom p-3 text-start small mb-0">
                                        <i class="fa-solid fa-circle-info text-info me-2 fs-5"></i>
                                        สิทธิ์การใช้งานระดับสูง (แอดมิน/ประธาน) ไม่สามารถร่วมสมัครได้
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Contact & Group QR Card -->
                        <div class="side-widget p-4 rounded-4 text-center">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 text-start">
                                <i class="fa-solid fa-qrcode text-primary me-2"></i>กลุ่มติดต่อสื่อสารชมรม
                            </h6>
                            <?php if (assetExists($club['qr_code'])): ?>
                                <div class="qr-code-frame mx-auto mb-3 p-2 bg-white rounded-3 shadow-sm border">
                                    <img src="<?= asset($club['qr_code']) ?>" alt="Group QR Code" class="img-fluid rounded-3" style="max-height: 180px; width: 100%; object-fit: contain;">
                                </div>
                                <p class="text-muted small m-0"><i class="fa-solid fa-magnifying-glass-plus me-1"></i>สแกนเพื่อเข้ากลุ่มไลน์/ติดต่อสื่อสารของสมาชิก</p>
                            <?php else: ?>
                                <div class="p-4 bg-light rounded-3 text-muted text-center border" style="border-style: dashed !important; border-color: var(--border-strong) !important;">
                                    <i class="fa-solid fa-qrcode fs-2 mb-2 opacity-50 text-secondary"></i>
                                    <p class="small m-0">ชมรมนี้ยังไม่ได้อัปโหลดคิวอาร์โค้ดติดต่อ</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* --- Academic Modern Detail View Styles --- */
.academic-card {
    background: var(--surface);
    border-radius: 24px;
    box-shadow: 0 15px 35px rgba(11, 44, 92, 0.08) !important;
    border: 1px solid var(--border) !important;
}

.club-detail-banner {
    background: linear-gradient(135deg, #0b2c5c 0%, #1a4980 100%);
    border-bottom: 4px solid var(--accent-gold);
}

.academic-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.12;
    background-image: radial-gradient(circle at 100% 150%, #ffffff 24%, #0b2c5c 24%, #0b2c5c 28%, #ffffff 28%, #ffffff 36%, transparent 36%, transparent),
                      radial-gradient(circle at 0% 150%, #ffffff 24%, #0b2c5c 24%, #0b2c5c 28%, #ffffff 28%, #ffffff 36%, transparent 36%, transparent);
    background-size: 40px 40px;
    z-index: 1;
}

.btn-banner-back {
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 10;
    background: rgba(255, 255, 255, 0.15);
    color: #fff !important;
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    transition: all var(--dur) var(--ease-out);
}

.btn-banner-back:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateX(-3px);
}

/* Profile header overlay layout */
.club-profile-header {
    position: relative;
    z-index: 5;
    margin-bottom: 20px;
}

.club-logo-wrapper {
    flex-shrink: 0;
}

.club-detail-logo {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 5px solid var(--surface);
    background: var(--surface);
    box-shadow: 0 10px 25px rgba(11, 44, 92, 0.12);
}

.badge-academic-accent {
    background: rgba(249, 168, 38, 0.2);
    color: var(--accent-gold);
    font-size: 0.75rem;
    letter-spacing: 1px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 50px;
    border: 1px solid rgba(249, 168, 38, 0.4);
}

.text-shadow-sm {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* General Layout Elements */
.section-title-custom {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--primary-blue);
    display: flex;
    align-items: center;
    border-bottom: 2px solid var(--border);
    padding-bottom: 8px;
}

.club-description-box {
    background: rgba(11, 44, 92, 0.015);
    border: 1px solid var(--border);
    line-height: 1.8;
    color: var(--text-dark);
    font-size: 1rem;
    text-align: justify;
    text-justify: inter-word;
}

/* Side Widget Styling */
.side-widget {
    background: var(--bg-light);
    border: 1px solid var(--border-strong);
    box-shadow: 0 4px 10px rgba(11, 44, 92, 0.02);
}

.qr-code-frame {
    width: 200px;
    background: #fff;
    border-color: var(--border-strong) !important;
}

/* Custom Alert Layouts */
.alert-info-custom {
    background: rgba(26, 73, 128, 0.05);
    border: 1px solid rgba(26, 73, 128, 0.2);
    border-left: 4px solid var(--primary-soft);
    border-radius: 12px;
    color: var(--info-ink);
}

.alert-success-custom {
    background: rgba(31, 122, 82, 0.05);
    border: 1px solid rgba(31, 122, 82, 0.2);
    border-left: 4px solid var(--success);
    border-radius: 12px;
    color: var(--success-ink);
}

.alert-warning-custom {
    background: rgba(249, 168, 38, 0.05);
    border: 1px solid rgba(249, 168, 38, 0.2);
    border-left: 4px solid var(--accent-gold);
    border-radius: 12px;
    color: var(--warning-ink);
}

.alert-danger-custom {
    background: rgba(192, 57, 43, 0.05);
    border: 1px solid rgba(192, 57, 43, 0.2);
    border-left: 4px solid var(--danger);
    border-radius: 12px;
    color: var(--danger-ink);
}

/* Button Custom Styles */
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
    justify-content: center;
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
</style>
