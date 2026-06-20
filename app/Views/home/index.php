<?php
/** 
 * @var array $clubs 
 * @var array $announcements 
 * @var array $events 
 * @var array $gallery 
 * @var array $myClubs
 * @var array $myApplications
 */
?>

<!-- Section: Hero Banner -->
<div class="hero-section text-center text-md-start mb-5">
    <div class="row align-items-center g-4">
        <div class="col-md-7">
            <span class="badge bg-warning text-dark mb-2 px-3 py-2 fw-bold text-uppercase" style="border-radius: 30px; letter-spacing: 1px;">MCRU Clubs Hub</span>
            <h1 class="display-5 fw-bold mb-3 text-white">ระบบจัดการชมรมและกิจกรรมนักศึกษา</h1>
            <p class="lead mb-4 text-white-50">ค้นพบชมรมที่ใช่ เข้าร่วมกิจกรรมที่ชอบ พัฒนาทักษะชีวิตและสร้างมิตรภาพใหม่ ๆ ในมหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง</p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number"><?= count($clubs) ?>+</div>
                    <div class="stat-label">ชมรมทั้งหมด</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">
                        <?php 
                            $totalMembers = 0;
                            foreach ($clubs as $c) {
                                $totalMembers += (int) $c['current_members'];
                            }
                            echo $totalMembers;
                        ?>
                    </div>
                    <div class="stat-label">นักศึกษาในชมรม</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= count($events) ?>+</div>
                    <div class="stat-label">กิจกรรมที่จัดแล้ว</div>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-center d-none d-md-block">
            <i class="fa-solid fa-people-group text-white-50" style="font-size: 10rem; opacity: 0.15;"></i>
        </div>
    </div>
</div>

<?php if (!empty($myClubs) || !empty($myApplications)): ?>
<!-- Section: My Clubs Dashboard -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card-custom p-4 bg-white border-0 shadow-sm rounded-4" style="border: 1px solid var(--border) !important;">
            <h4 class="text-primary-custom fw-bold mb-4">
                <i class="fa-solid fa-graduation-cap text-warning me-2"></i>ชมรมและสถานะการสมัครของฉัน
            </h4>
            
            <div class="row g-4">
                <?php 
                    $hasClubs = !empty($myClubs);
                    $hasApps = !empty($myApplications);
                    $colClass = ($hasClubs && $hasApps) ? 'col-md-6' : 'col-12';
                ?>
                <!-- Joined Clubs (Approved) -->
                <?php if ($hasClubs): ?>
                    <div class="<?= $colClass ?> <?= ($hasClubs && $hasApps) ? 'border-md-end' : '' ?>">
                        <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-circle-check text-success me-2"></i>ชมรมที่เป็นสมาชิก (<?= count($myClubs) ?>)</h6>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($myClubs as $mc): ?>
                                <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between border">
                                    <div class="d-flex align-items-center">
                                        <?php if (assetExists($mc['club_logo'])): ?>
                                            <img src="<?= asset($mc['club_logo']) ?>" class="rounded-circle me-3" style="width:48px;height:48px;object-fit:cover;border:2px solid var(--border);" alt="">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center text-muted me-3" style="width:48px;height:48px;border:2px solid var(--border);">No Lg</div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="fw-bold m-0 text-dark"><?= e($mc['club_name']) ?></h6>
                                            <small class="text-muted"><i class="fa-solid fa-user-tag me-1 text-primary"></i>ตำแหน่ง: <span class="badge bg-secondary"><?= e($mc['member_role']) ?></span></small>
                                        </div>
                                    </div>
                                    <a href="<?= url('clubs/detail/' . (int)$mc['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        เข้าสู่หน้าชมรม <i class="fa-solid fa-circle-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Pending Applications -->
                <?php if ($hasApps): ?>
                    <div class="<?= $colClass ?>">
                        <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-clock-rotate-left text-warning me-2"></i>คำขอที่อยู่ระหว่างพิจารณา (<?= count($myApplications) ?>)</h6>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($myApplications as $ma): ?>
                                <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between border">
                                    <div class="d-flex align-items-center">
                                        <?php if (assetExists($ma['club_logo'])): ?>
                                            <img src="<?= asset($ma['club_logo']) ?>" class="rounded-circle me-3" style="width:48px;height:48px;object-fit:cover;border:2px solid var(--border);" alt="">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center text-muted me-3" style="width:48px;height:48px;border:2px solid var(--border);">No Lg</div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="fw-bold m-0 text-dark"><?= e($ma['club_name']) ?></h6>
                                            <small class="text-muted"><i class="fa-solid fa-spinner fa-spin me-1 text-warning"></i>สถานะ: <span class="text-warning fw-bold">รออนุมัติเข้าชมรม</span></small>
                                        </div>
                                    </div>
                                    <a href="<?= url('clubs/detail/' . (int)$ma['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        ตรวจสอบสิทธิ์ <i class="fa-solid fa-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Section: PR News & Event Calendar -->
<div class="row g-4 mb-5">
    <!-- PR News -->
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-bullhorn text-warning me-2"></i>ข่าวสารประชาสัมพันธ์</h4>
        </div>
        <div class="row g-3">
            <?php if (empty($announcements)): ?>
                <div class="col-12 text-center py-5 text-muted bg-white rounded shadow-sm">
                    <i class="fa-regular fa-folder-open fs-2 mb-2"></i>
                    <p class="m-0">ยังไม่มีข่าวประชาสัมพันธ์ในขณะนี้</p>
                </div>
            <?php else: ?>
                <?php foreach ($announcements as $news): ?>
                    <div class="col-md-6">
                        <a href="<?= url('announcements/detail/' . (int)$news['id']) ?>" class="text-decoration-none h-100 d-block">
                            <div class="news-card h-100 d-flex flex-column">
                                <div class="news-thumb bg-light d-flex align-items-center justify-content-center text-muted" 
                                     style="<?= !empty($news['thumbnail']) ? 'background-image: url(' . asset($news['thumbnail']) . ');' : 'background: linear-gradient(135deg, var(--surface-alt), var(--border));' ?>">
                                    <?php if (empty($news['thumbnail'])): ?>
                                        <i class="fa-regular fa-image fs-1 opacity-25"></i>
                                    <?php endif; ?>
                                    <span class="news-badge <?= $news['club_id'] ? 'news-badge-club' : '' ?>">
                                        <?= $news['club_id'] ? e($news['club_name']) : 'ข่าวสารกลาง' ?>
                                    </span>
                                </div>
                                <div class="p-3 d-flex flex-column flex-grow-1">
                                    <h6 class="fw-bold text-primary-custom mb-2"><?= e($news['title']) ?></h6>
                                    <p class="text-muted small flex-grow-1"><?= mb_substr(strip_tags($news['content']), 0, 100) . '...' ?></p>
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
            <?php endif; ?>
        </div>
    </div>

    <!-- Event Calendar -->
    <div class="col-lg-4">
        <h4 class="text-primary-custom fw-bold mb-4"><i class="fa-regular fa-calendar-check text-primary me-2"></i>ปฏิทินกิจกรรม</h4>
        <div class="calendar-list">
            <?php if (empty($events)): ?>
                <div class="text-center py-5 text-muted bg-white rounded shadow-sm">
                    <i class="fa-regular fa-calendar-times fs-2 mb-2"></i>
                    <p class="m-0">ยังไม่มีกำหนดการกิจกรรมในขณะนี้</p>
                </div>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <?php 
                        $time = strtotime($event['event_date']);
                        $day = date('d', $time);
                        $thaiMonths = [
                            '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.',
                            '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.',
                            '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'
                        ];
                        $month = $thaiMonths[date('m', $time)] ?? date('M', $time);
                    ?>
                    <div class="calendar-item">
                        <div class="calendar-date-badge">
                            <span class="day"><?= $day ?></span>
                            <span class="month"><?= $month ?></span>
                        </div>
                        <div>
                            <h6 class="fw-bold text-primary-custom m-0 mb-1"><?= e($event['title']) ?></h6>
                            <span class="badge bg-light text-primary border mb-2" style="font-size: 0.75rem;">
                                <?= $event['club_id'] ? e($event['club_name']) : 'กิจกรรมสถาบัน' ?>
                            </span>
                            <div class="text-muted small">
                                <?php if ($event['start_time']): ?>
                                    <div><i class="fa-regular fa-clock me-1"></i> <?= date('H:i', strtotime($event['start_time'])) ?><?= $event['end_time'] ? ' - ' . date('H:i', strtotime($event['end_time'])) : '' ?> น.</div>
                                <?php endif; ?>
                                <?php if ($event['location']): ?>
                                    <div><i class="fa-solid fa-location-dot me-1"></i> <?= e($event['location']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Section: Clubs Grid -->
<div class="my-5 border-top pt-5">
    <div class="text-center mb-5">
        <h3 class="text-primary-custom fw-bold">ชมรมที่น่าสนใจ</h3>
        <p class="text-muted">ชมรมต่างๆ ของมหาวิทยาลัยราชภัฏหมู่บ้านจอมบึงที่น่าสนใจ</p>
    </div>

    <div class="row g-4" id="clubsGrid">
        <?php foreach ($clubs as $row): ?>
        <div class="col-md-6 col-lg-4 club-card-wrapper" data-name="<?= strtolower(e($row['club_name'])) ?>" data-desc="<?= strtolower(e($row['description'])) ?>">
            <div class="card-custom h-100 text-center d-flex flex-column">
                <div class="club-banner"></div>
                <div class="px-3 pb-4 d-flex flex-column flex-grow-1 align-items-center">
                    <?php if (assetExists($row['club_logo'])): ?>
                        <img src="<?= asset($row['club_logo']) ?>" class="club-logo-thumb" alt="Logo">
                    <?php else: ?>
                        <div class="club-logo-thumb bg-light d-flex align-items-center justify-content-center text-muted">No Image</div>
                    <?php endif; ?>
                    <h5 class="text-primary-custom fw-bold mt-3 mb-1 club-name-title"><?= e($row['club_name']) ?></h5>
                    <?php
                        $cur = (int) $row['current_members'];
                        $max = (int) $row['max_members'];
                        $ratio = $max > 0 ? $cur / $max : 1;
                        $tone = $ratio >= 1 ? 'full' : ($ratio >= 0.8 ? 'warn' : 'open');
                    ?>
                    <div class="mb-2">
                        <span class="member-badge member-badge--<?= $tone ?>">
                            <i class="fa-solid fa-users"></i>
                            <?= $cur ?> / <?= $max ?>
                        </span>
                    </div>
                    <p class="club-desc flex-grow-1 mb-4"><?= mb_substr(e($row['description']), 0, 90) . '...' ?></p>
                    <a href="<?= url('clubs/detail/' . $row['id']) ?>" class="btn-outline-custom w-100 py-2">รายละเอียด / สมัคร</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-5">
        <a href="<?= url('clubs') ?>" class="btn btn-gold-custom py-3 px-5 fs-5 rounded-pill shadow-sm">
            <i class="fa-solid fa-compass me-2"></i> ค้นหาและดูชมรมทั้งหมด
        </a>
    </div>
</div>

<!-- Section: Activity Gallery -->
<div class="my-5 border-top pt-5">
    <h4 class="text-primary-custom fw-bold mb-4 text-center"><i class="fa-regular fa-images text-warning me-2"></i>ภาพกิจกรรมชมรม</h4>
    <div class="row g-3">
        <?php if (empty($gallery)): ?>
            <div class="col-12 text-center py-5 text-muted bg-white rounded shadow-sm">
                <i class="fa-regular fa-image fs-1 mb-2 opacity-25"></i>
                <p class="m-0">ยังไม่มีภาพกิจกรรมในคลังในขณะนี้</p>
            </div>
        <?php else: ?>
            <?php foreach ($gallery as $photo): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gallery-card" onclick="openLightbox('<?= asset($photo['image_path']) ?>', '<?= e($photo['title']) ?>')">
                        <img src="<?= asset($photo['image_path']) ?>" class="gallery-img" alt="<?= e($photo['title']) ?>">
                        <div class="gallery-card-overlay">
                            <h6 class="m-0 fw-bold text-truncate"><?= e($photo['title']) ?></h6>
                            <small class="text-white-50"><?= $photo['club_id'] ? e($photo['club_name']) : 'กิจกรรมสถาบัน' ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Lightbox Modal for Gallery -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-lg" data-bs-dismiss="modal" aria-label="Close" style="z-index: 10;"></button>
                <img id="lightboxImage" src="" class="img-fluid rounded shadow-lg" style="max-height: 80vh;">
                <div id="lightboxCaption" class="text-white text-center mt-3 bg-dark bg-opacity-75 py-2 px-3 rounded d-inline-block"></div>
            </div>
        </div>
    </div>
</div>

<script>
/** ฟังก์ชันเปิด Lightbox สำหรับแกลเลอรี */
function openLightbox(imagePath, captionText) {
    const myModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
    document.getElementById('lightboxImage').src = imagePath;
    document.getElementById('lightboxCaption').innerText = captionText;
    myModal.show();
}
</script>
