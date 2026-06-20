<?php
/**
 * @var array $club
 * @var string|null $appStatus
 * @var bool $isFull
 * @var array $announcements
 * @var array $events
 * @var array $gallery
 * @var array|null $president
 * @var array $officers
 * @var array $members
 */
?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Academic Club Detail Card -->
        <div class="academic-card shadow-lg border-0 overflow-hidden mb-5">
            <!-- Club Banner with Title and Metadata inside -->
            <div class="club-detail-banner position-relative d-flex align-items-end p-4 p-md-5" style="min-height: 240px; padding-bottom: 30px !important;">
                <div class="academic-pattern"></div>
                
                <!-- Back Button in Banner -->
                <a href="<?= url('clubs') ?>" class="btn-banner-back text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-2"></i>กลับหน้ารายชื่อชมรม
                </a>

                <!-- Title and Metadata inside Banner for perfect contrast -->
                <div class="position-relative z-index-2 w-100 text-white mt-5 text-center text-md-start club-title-container">
                    <span class="badge badge-academic-accent mb-2">MCRU APPROVED CLUB</span>
                    <h2 class="fw-bold m-0 text-white text-shadow-sm club-name-header" style="font-size: 2.2rem; letter-spacing: -0.5px;"><?= e($club['club_name']) ?></h2>
                    <p class="m-0 mt-2 text-white-50">
                        <i class="fa-solid fa-user-tie text-warning me-2"></i>ประธานชมรม: 
                        <strong class="text-white"><?= $club['pres_name'] ? e($club['pres_name']) : '<span class="text-danger">ยังไม่แต่งตั้ง</span>' ?></strong>
                    </p>
                </div>
            </div>

            <!-- Floating Logo Area -->
            <div class="club-profile-header px-4 px-md-5">
                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end gap-3 club-logo-row">
                    <div class="club-logo-wrapper shadow-sm" style="border-radius: 50%;">
                        <?php if (assetExists($club['club_logo'])): ?>
                            <img src="<?= asset($club['club_logo']) ?>" class="club-detail-logo" alt="Logo">
                        <?php else: ?>
                            <div class="club-detail-logo bg-light d-flex align-items-center justify-content-center text-muted fw-bold border">NO LOGO</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs for Club Homepage Sections -->
            <div class="px-4 px-md-5">
                <div class="tabs-scroll-wrapper mb-5">
                    <ul class="nav nav-tabs nav-tabs-academic justify-content-md-center border-0 gap-2" id="clubTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4 py-2 border-0" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-panel" type="button" role="tab" aria-selected="true">
                                <i class="fa-solid fa-circle-info me-2"></i>ข้อมูลทั่วไป
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 py-2 border-0" id="news-tab" data-bs-toggle="tab" data-bs-target="#news-panel" type="button" role="tab" aria-selected="false">
                                <i class="fa-solid fa-bullhorn me-2"></i>ข่าวสารชมรม (<?= count($announcements) ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 py-2 border-0" id="events-tab" data-bs-toggle="tab" data-bs-target="#events-panel" type="button" role="tab" aria-selected="false">
                                <i class="fa-regular fa-calendar-check me-2"></i>ปฏิทินกิจกรรม (<?= count($events) ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 py-2 border-0" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery-panel" type="button" role="tab" aria-selected="false">
                                <i class="fa-solid fa-images me-2"></i>ภาพกิจกรรม (<?= count($gallery) ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 py-2 border-0" id="hierarchy-tab" data-bs-toggle="tab" data-bs-target="#hierarchy-panel" type="button" role="tab" aria-selected="false">
                                <i class="fa-solid fa-sitemap me-2"></i>โครงสร้างชมรม
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tab Panels Content -->
            <div class="tab-content px-4 px-md-5 pb-5">
                
                <!-- Tab 1: Overview -->
                <div class="tab-pane fade show active" id="overview-panel" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="row g-4">
                        <!-- Left Column: Description -->
                        <div class="col-lg-7">
                            <h5 class="section-title-custom mb-4">
                                <i class="fa-solid fa-file-invoice text-primary me-2"></i>รายละเอียดและวัตถุประสงค์
                            </h5>
                            <div class="club-description-box p-4 rounded-4 mb-4">
                                <?= nl2br(e($club['description'])) ?>
                            </div>
                        </div>

                        <!-- Right Column: Registration & Stats -->
                        <div class="col-lg-5">
                            <!-- Stats & Signup widget -->
                            <div class="side-widget p-4 rounded-4 mb-4">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fa-solid fa-chart-simple text-warning me-2"></i>สถานะและการรับสมัคร
                                </h6>
                                
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
                                        <div class="progress-bar <?= $progressClass ?> rounded-pill" role="progressbar" style="width: <?= $percentage ?>%;"></div>
                                    </div>
                                    <div class="text-end mt-1">
                                        <small class="text-muted font-monospace"><?= $percentage ?>% ของความจุชมรม</small>
                                    </div>
                                </div>

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

                            <!-- QR Code widget -->
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

                <!-- Tab 2: News/Announcements -->
                <div class="tab-pane fade" id="news-panel" role="tabpanel" aria-labelledby="news-tab">
                    <h5 class="section-title-custom mb-4">
                        <i class="fa-solid fa-bullhorn text-primary me-2"></i>ข่าวสารประชาสัมพันธ์ภายในชมรม
                    </h5>
                    <?php if (empty($announcements)): ?>
                        <div class="text-center py-5 text-muted border rounded-4 bg-light" style="border-style: dashed !important;">
                            <i class="fa-regular fa-folder-open fs-2 mb-2 opacity-50"></i>
                            <p class="m-0">ชมรมนี้ยังไม่มีข่าวประชาสัมพันธ์ในขณะนี้</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($announcements as $news): ?>
                                <div class="col-md-6 col-lg-4">
                                    <a href="<?= url('announcements/detail/' . (int)$news['id']) ?>" class="text-decoration-none h-100 d-block">
                                        <div class="news-card h-100 d-flex flex-column border rounded-4 bg-white shadow-sm overflow-hidden" style="transition: all var(--dur) var(--ease-out);">
                                            <div class="news-thumb bg-light d-flex align-items-center justify-content-center text-muted" 
                                                 style="height: 160px; background-size: cover; background-position: center; <?= !empty($news['thumbnail']) ? 'background-image: url(' . asset($news['thumbnail']) . ');' : 'background: linear-gradient(135deg, var(--surface-alt), var(--border));' ?>">
                                                <?php if (empty($news['thumbnail'])): ?>
                                                    <i class="fa-regular fa-image fs-1 opacity-25"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="p-3 d-flex flex-column flex-grow-1">
                                                <h6 class="fw-bold text-primary-custom mb-2 text-line-clamp-2"><?= e($news['title']) ?></h6>
                                                <p class="text-muted small flex-grow-1 text-line-clamp-3" style="line-height: 1.6;"><?= mb_substr(strip_tags($news['content']), 0, 100) . '...' ?></p>
                                                <hr class="my-2 opacity-10">
                                                <div class="d-flex justify-content-between align-items-center text-muted small mt-auto">
                                                    <span><i class="fa-regular fa-user me-1"></i><?= e($news['author_name']) ?></span>
                                                    <span><i class="fa-regular fa-calendar-days me-1"></i><?= date('d/m/Y', strtotime($news['created_at'])) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab 3: Events Calendar -->
                <div class="tab-pane fade" id="events-panel" role="tabpanel" aria-labelledby="events-tab">
                    <h5 class="section-title-custom mb-4">
                        <i class="fa-regular fa-calendar-check text-primary me-2"></i>ปฏิทินและตารางกิจกรรมของชมรม
                    </h5>
                    <?php if (empty($events)): ?>
                        <div class="text-center py-5 text-muted border rounded-4 bg-light" style="border-style: dashed !important;">
                            <i class="fa-regular fa-calendar-times fs-2 mb-2 opacity-50"></i>
                            <p class="m-0">ชมรมนี้ยังไม่มีกำหนดการกิจกรรมในขณะนี้</p>
                        </div>
                    <?php else: ?>
                        <div class="timeline-container mx-auto" style="max-width: 750px;">
                            <?php foreach ($events as $ev): ?>
                                <?php 
                                    $time = strtotime($ev['event_date']);
                                    $day = date('d', $time);
                                    $thaiMonths = [
                                        '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.',
                                        '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.',
                                        '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'
                                    ];
                                    $month = $thaiMonths[date('m', $time)] ?? date('M', $time);
                                ?>
                                <div class="timeline-item d-flex gap-4 mb-4">
                                    <div class="timeline-date-badge text-center flex-shrink-0" style="width: 70px;">
                                        <div class="bg-primary text-white rounded-3 py-2 shadow-sm">
                                            <span class="d-block fw-bold fs-4 m-0 lh-1"><?= $day ?></span>
                                            <span class="small opacity-90"><?= $month ?></span>
                                        </div>
                                    </div>
                                    <div class="timeline-content p-4 rounded-4 bg-light border flex-grow-1" style="border-color: var(--border-strong) !important;">
                                        <h6 class="fw-bold text-primary-custom mb-2"><?= e($ev['title']) ?></h6>
                                        <p class="text-muted small mb-3" style="line-height: 1.6;"><?= nl2br(e($ev['description'])) ?></p>
                                        <div class="d-flex flex-wrap gap-3 text-muted small border-top pt-2" style="border-color: rgba(0,0,0,0.06) !important;">
                                            <?php if ($ev['start_time']): ?>
                                                <span><i class="fa-regular fa-clock me-1 text-primary"></i> <?= date('H:i', strtotime($ev['start_time'])) ?><?= $ev['end_time'] ? ' - ' . date('H:i', strtotime($ev['end_time'])) : '' ?> น.</span>
                                            <?php endif; ?>
                                            <?php if ($ev['location']): ?>
                                                <span><i class="fa-solid fa-location-dot me-1 text-primary"></i> <?= e($ev['location']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab 4: Gallery -->
                <div class="tab-pane fade" id="gallery-panel" role="tabpanel" aria-labelledby="gallery-tab">
                    <h5 class="section-title-custom mb-4">
                        <i class="fa-solid fa-images text-primary me-2"></i>คลังภาพถ่ายและแกลเลอรีรูปภาพกิจกรรม
                    </h5>
                    <?php if (empty($gallery)): ?>
                        <div class="text-center py-5 text-muted border rounded-4 bg-light" style="border-style: dashed !important;">
                            <i class="fa-regular fa-image fs-2 mb-2 opacity-50"></i>
                            <p class="m-0">ชมรมนี้ยังไม่มีการอัปโหลดรูปภาพกิจกรรมในแกลเลอรี</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($gallery as $img): ?>
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="gallery-card rounded-4 overflow-hidden border shadow-sm position-relative" style="transition: all 0.3s ease; aspect-ratio: 4/3; cursor: pointer;">
                                        <img src="<?= asset($img['image_path']) ?>" alt="<?= e($img['title']) ?>" class="img-fluid w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                                        <div class="gallery-hover-desc position-absolute bottom-0 start-0 end-0 p-2 text-white text-center small" style="background: rgba(11, 44, 92, 0.85); transition: all 0.3s ease;">
                                            <?= e($img['title']) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab 5: Hierarchy / Org Structure -->
                <div class="tab-pane fade" id="hierarchy-panel" role="tabpanel" aria-labelledby="hierarchy-tab">
                    <h5 class="section-title-custom mb-4">
                        <i class="fa-solid fa-sitemap text-primary me-2"></i>โครงสร้างคณะกรรมการและรายชื่อสมาชิกในชมรม
                    </h5>
                    
                    <div class="hierarchy-container py-3">
                        <div class="hierarchy-tree-wrapper">
                            <div class="hierarchy-tree">
                                <ul>
                                    <li>
                                        <!-- Tier 1: President Node -->
                                        <div class="node-card president-node mx-auto">
                                            <div class="node-avatar"><i class="fa-solid fa-crown"></i></div>
                                            <h6 class="fw-bold text-dark m-0"><?= $president ? e($president['name']) : 'ยังไม่แต่งตั้งประธาน' ?></h6>
                                            <?php if ($president): ?>
                                                <span class="small text-muted font-monospace d-block my-1"><?= e($president['student_id']) ?></span>
                                            <?php endif; ?>
                                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mt-1" style="font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(249, 168, 38, 0.3);">ประธานชมรม</span>
                                        </div>
                                        
                                        <?php if (!empty($officers)): ?>
                                            <ul>
                                                <?php foreach ($officers as $off): ?>
                                                    <li>
                                                        <div class="node-card officer-node mx-auto">
                                                            <div class="node-avatar"><i class="fa-solid fa-user-shield"></i></div>
                                                            <h6 class="fw-bold text-dark m-0"><?= e($off['name']) ?></h6>
                                                            <span class="small text-muted font-monospace d-block my-1"><?= e($off['student_id']) ?></span>
                                                            <span class="badge bg-primary text-white px-3 py-1 rounded-pill mt-1" style="font-size: 0.75rem;"><?= e($off['role_name']) ?></span>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Tier 3: General Members List -->
                        <div class="mt-5 border-top pt-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-users text-primary me-2"></i>สมาชิกทั่วไป (<?= count($members) ?> คน)</h6>
                            <?php if (empty($members)): ?>
                                <div class="text-center py-4 text-muted bg-light rounded-3 border">
                                    <p class="m-0 small">ยังไม่มีสมาชิกทั่วไปเข้าชมรมในขณะนี้</p>
                                </div>
                            <?php else: ?>
                                <div class="row g-3">
                                    <?php foreach ($members as $m): ?>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="p-3 bg-white border rounded-3 d-flex align-items-center justify-content-between shadow-sm">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 38px; height: 38px;">
                                                        <i class="fa-solid fa-user text-secondary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-dark m-0 small"><?= e($m['name']) ?></h6>
                                                        <small class="text-muted font-monospace" style="font-size: 0.75rem;"><?= e($m['student_id']) ?></small>
                                                    </div>
                                                </div>
                                                <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">สมาชิก</span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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
    min-height: 240px;
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

/* Horizontal Scrollable Tabs on Mobile */
.tabs-scroll-wrapper {
    overflow-x: auto;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none;  /* IE and Edge */
    padding-bottom: 4px;
    width: 100%;
}
.tabs-scroll-wrapper::-webkit-scrollbar {
    display: none; /* Chrome, Safari and Opera */
}
.nav-tabs-academic {
    flex-wrap: nowrap !important;
}
.nav-tabs-academic .nav-item {
    flex-shrink: 0;
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

@media (min-width: 768px) {
    .club-title-container {
        margin-left: 150px; /* Shift text to the right of the logo */
    }
    
    .club-logo-row {
        margin-top: -90px !important; /* Float logo over banner */
        margin-bottom: 20px !important;
    }
}

@media (max-width: 767.98px) {
    .club-detail-banner {
        min-height: 200px;
        padding-bottom: 20px !important;
    }
    
    .club-logo-row {
        margin-top: 15px !important; /* Stack below banner to prevent overlap on mobile */
        margin-bottom: 15px !important;
        justify-content: center;
    }
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

/* --- News / Announcement Card Premium style --- */
.news-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(11, 44, 92, 0.08) !important;
    border-color: var(--primary-soft) !important;
}

.text-line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.text-line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* --- Events Timeline Premium style --- */
.timeline-item {
    position: relative;
}

.timeline-item::after {
    content: "";
    position: absolute;
    top: 50px;
    left: 34px;
    bottom: -30px;
    width: 2px;
    background-color: var(--border-strong);
    z-index: 1;
}

.timeline-item:last-child::after {
    display: none;
}

.timeline-content:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}

/* --- Gallery premium styles --- */
.gallery-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(11, 44, 92, 0.12) !important;
    border-color: var(--primary-soft) !important;
}

.gallery-hover-desc {
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
}

.gallery-card:hover .gallery-hover-desc {
    opacity: 1;
    transform: translateY(0);
}

/* --- Academic Tabs Premium styling --- */
.nav-tabs-academic .nav-link {
    background: transparent;
    color: var(--text-muted);
    font-weight: 600;
    border: 1px solid var(--border-strong) !important;
    transition: all var(--dur) var(--ease-out);
}

.nav-tabs-academic .nav-link.active, 
.nav-tabs-academic .nav-link:hover {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-soft) 100%) !important;
    color: #ffffff !important;
    border-color: transparent !important;
    box-shadow: 0 4px 12px rgba(11, 44, 92, 0.15);
}

/* --- Club Organizational Structure Hierarchy Tree CSS --- */
.hierarchy-tree-wrapper {
    width: 100%;
    overflow-x: auto;
    padding: 20px 0;
}

.hierarchy-tree {
    display: inline-block;
    min-width: 100%;
    text-align: center;
}

.hierarchy-tree ul {
    padding-top: 20px; 
    position: relative;
    transition: all 0.3s;
    display: flex;
    justify-content: center;
    padding-left: 0;
    margin-bottom: 0;
}

.hierarchy-tree li {
    text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 10px 0 10px;
    transition: all 0.3s;
    flex-grow: 0;
    flex-shrink: 0;
}

/* We will use ::before and ::after to draw the connector lines */
.hierarchy-tree li::before, .hierarchy-tree li::after {
    content: '';
    position: absolute; 
    top: 0; 
    right: 50%;
    border-top: 2px solid var(--border-strong);
    width: 50%; 
    height: 20px;
}
.hierarchy-tree li::after {
    right: auto; 
    left: 50%;
    border-left: 2px solid var(--border-strong);
}

/* Remove left-right connectors from elements without siblings */
.hierarchy-tree li:only-child::after, .hierarchy-tree li:only-child::before {
    display: none;
}

/* Remove space from the top of single children */
.hierarchy-tree li:only-child { 
    padding-top: 0;
}

/* Remove left connector from first child and right connector from last child */
.hierarchy-tree li:first-child::before, .hierarchy-tree li:last-child::after {
    border: 0 none;
}

/* Adding back the vertical connector to the last node */
.hierarchy-tree li:last-child::before {
    border-right: 2px solid var(--border-strong);
    border-radius: 0 8px 0 0;
}
.hierarchy-tree li:first-child::after {
    border-radius: 8px 0 0 0;
}

/* Time to go down from parent to child */
.hierarchy-tree ul ul::before {
    content: '';
    position: absolute; 
    top: 0; 
    left: 50%;
    border-left: 2px solid var(--border-strong);
    width: 0; 
    height: 20px;
    transform: translateX(-50%);
}

.node-card {
    background: var(--surface);
    border-radius: 16px;
    padding: 16px 24px;
    box-shadow: var(--shadow-sm);
    border: 2px solid var(--border);
    transition: all 0.3s ease;
    text-align: center;
    width: 240px;
}

.node-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.node-card.president-node {
    border-color: var(--accent-gold);
    background: linear-gradient(to bottom, var(--surface) 0%, rgba(249, 168, 38, 0.02) 100%);
}

.node-card.officer-node {
    border-color: var(--primary-soft);
    background: linear-gradient(to bottom, var(--surface) 0%, rgba(26, 73, 128, 0.01) 100%);
}

.node-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background-color: var(--bg-light);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 8px;
    color: var(--text-muted);
    border: 2px solid var(--border-strong);
}

.president-node .node-avatar {
    color: var(--accent-gold-deep);
    border-color: var(--accent-gold);
    background: rgba(249, 168, 38, 0.1);
}

.officer-node .node-avatar {
    color: var(--primary-soft);
    border-color: var(--primary-soft);
    background: rgba(26, 73, 128, 0.1);
}

@media (min-width: 768px) {
    .border-end-md {
        border-right: 1px solid var(--border-strong);
    }
}

/* Responsive Hierarchy Tree for Mobile */
@media (max-width: 767.98px) {
    .hierarchy-tree ul {
        flex-direction: column !important;
        align-items: center !important;
        padding-top: 0 !important;
        gap: 20px !important;
    }
    
    .hierarchy-tree li {
        padding: 0 !important;
        width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
    }
    
    /* Hide the horizontal connector lines */
    .hierarchy-tree li::before, 
    .hierarchy-tree li::after,
    .hierarchy-tree ul ul::before {
        display: none !important;
    }
    
    /* Add a clean vertical arrow between hierarchy levels on mobile */
    .hierarchy-tree ul ul {
        position: relative !important;
        padding-top: 30px !important;
        margin-top: 10px !important;
    }
    
    .hierarchy-tree ul ul::before {
        content: "\f063" !important; /* FontAwesome angle-down arrow */
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        color: var(--primary-soft);
        font-size: 1.25rem;
        display: block !important;
        width: auto !important;
        height: auto !important;
        border: none !important;
        background: transparent !important;
    }
    
    /* Add spacing and line indicators between officers on mobile */
    .hierarchy-tree ul ul li:not(:last-child) {
        position: relative !important;
        padding-bottom: 30px !important;
    }
    
    .hierarchy-tree ul ul li:not(:last-child)::after {
        content: "\f063" !important;
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        color: var(--border-strong);
        font-size: 1.1rem;
        display: block !important;
        width: auto;
        height: auto;
        border: none;
    }
}
</style>
