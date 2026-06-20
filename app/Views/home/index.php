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
    <div class="col-lg-8 order-2 order-lg-1">
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

    <!-- Event Calendar (Academic Modern Design) -->
    <div class="col-lg-4 order-1 order-lg-2">
        <h4 class="text-primary-custom fw-bold mb-4"><i class="fa-regular fa-calendar-check text-primary me-2"></i>ปฏิทินกิจกรรม</h4>
        
        <!-- Monthly Calendar Grid Card -->
        <div class="card-custom border shadow-sm rounded-4 overflow-hidden mb-4" style="background: var(--surface); border-color: var(--border);">
            <!-- Calendar Header -->
            <div class="d-flex justify-content-between align-items-center p-3 text-white" style="background: var(--primary-blue);">
                <button class="btn btn-sm text-white-50 hover-text-white border-0" id="prevMonthBtn" onclick="changeMonth(-1)">
                    <i class="fa-solid fa-chevron-left fs-5"></i>
                </button>
                <h6 class="m-0 fw-bold font-kanit text-white" id="calendarMonthYearLabel" style="font-size: 1rem;">-</h6>
                <button class="btn btn-sm text-white-50 hover-text-white border-0" id="nextMonthBtn" onclick="changeMonth(1)">
                    <i class="fa-solid fa-chevron-right fs-5"></i>
                </button>
            </div>
            
            <!-- Calendar Days Header -->
            <div class="p-3 pb-0">
                <div class="row g-0 text-center fw-bold text-muted mb-2" style="font-size: 0.8rem;">
                    <div class="col" style="width: 14.28%;">อา</div>
                    <div class="col" style="width: 14.28%;">จ</div>
                    <div class="col" style="width: 14.28%;">อ</div>
                    <div class="col" style="width: 14.28%;">พ</div>
                    <div class="col" style="width: 14.28%;">พฤ</div>
                    <div class="col" style="width: 14.28%;">ศ</div>
                    <div class="col" style="width: 14.28%;">ส</div>
                </div>
                <!-- Days Grid -->
                <div class="row g-0 text-center pb-3" id="calendarDaysGrid">
                    <!-- Populated dynamically by JS -->
                </div>
            </div>
        </div>

        <!-- Selected Day Events Panel -->
        <div class="card-custom p-4 border shadow-sm rounded-4" style="background: var(--surface); border-color: var(--border); min-height: 250px;">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center justify-content-between" style="font-size: 0.95rem;">
                <span><i class="fa-regular fa-calendar-star text-warning me-2"></i>กิจกรรมในวันที่เลือก</span>
                <span class="badge bg-light text-primary border" id="selectedDateLabel" style="font-size: 0.75rem;">-</span>
            </h5>
            
            <div id="selectedEventsList" class="d-flex flex-column gap-3">
                <!-- Populated dynamically by JS -->
            </div>
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
            <div class="card-custom h-100 text-center d-flex flex-column" style="position: relative;">
                <?php
                    $cur = (int) $row['current_members'];
                    $max = (int) $row['max_members'];
                    $ratio = $max > 0 ? $cur / $max : 1;
                    $tone = $ratio >= 1 ? 'full' : ($ratio >= 0.8 ? 'warn' : 'open');
                    $isFull = $ratio >= 1;
                ?>
                <!-- Badge Overlay Group -->
                <div class="club-badges-group">
                    <span class="member-badge member-badge--<?= $tone ?>">
                        <i class="fa-solid fa-users me-1"></i><?= $cur ?> / <?= $max ?> คน
                    </span>
                    <?php if ($isFull): ?>
                        <span class="badge bg-danger text-white fw-bold">เต็มแล้ว</span>
                    <?php else: ?>
                        <span class="badge bg-success text-white fw-bold">เปิดรับสมัคร</span>
                    <?php endif; ?>
                </div>

                <div class="club-banner"></div>
                <div class="px-3 pb-4 d-flex flex-column flex-grow-1 align-items-center">
                    <?php if (assetExists($row['club_logo'])): ?>
                        <img src="<?= asset($row['club_logo']) ?>" class="club-logo-thumb" alt="Logo">
                    <?php else: ?>
                        <div class="club-logo-thumb bg-light d-flex align-items-center justify-content-center text-muted">No Image</div>
                    <?php endif; ?>
                    <h5 class="text-primary-custom fw-bold mt-3 mb-2 club-name-title" style="min-height: 48px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= e($row['club_name']) ?></h5>
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

<!-- Event Detail Modal (Academic Modern) -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--surface);">
            <div style="height: 5px; background: linear-gradient(90deg, var(--primary-blue) 0%, var(--accent-gold) 50%, var(--primary-soft) 100%);"></div>
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title fw-bold text-primary-custom d-flex align-items-center" id="eventDetailModalLabel">
                    <i class="fa-regular fa-calendar-check text-warning me-2 fs-4"></i>รายละเอียดกิจกรรม
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-dark">
                <h5 class="fw-bold text-primary-custom mb-3" id="modalEventTitle">-</h5>
                <div class="mb-3">
                    <span class="badge bg-primary px-3 py-2 rounded" id="modalEventClub">-</span>
                </div>
                
                <div class="p-3 bg-light rounded-4 border mb-4" style="font-size: 0.9rem; border-color: var(--border-strong) !important;">
                    <div class="mb-2 d-flex align-items-center"><i class="fa-regular fa-calendar-days text-muted me-2" style="width: 16px;"></i><strong class="me-1">วันที่จัดงาน:</strong> <span id="modalEventDate">-</span></div>
                    <div class="mb-2 d-flex align-items-center"><i class="fa-regular fa-clock text-muted me-2" style="width: 16px;"></i><strong class="me-1">เวลา:</strong> <span id="modalEventTime">-</span></div>
                    <div class="d-flex align-items-center"><i class="fa-solid fa-location-dot text-muted me-2" style="width: 16px;"></i><strong class="me-1">สถานที่:</strong> <span id="modalEventLocation">-</span></div>
                </div>

                <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-align-left text-muted me-1"></i> รายละเอียดกิจกรรม:</h6>
                <div class="text-muted small p-3 rounded-4 bg-light border" style="line-height: 1.7; white-space: pre-wrap; min-height: 100px; border-color: var(--border-strong) !important;" id="modalEventDescription">
                    -
                </div>
            </div>
            <div class="modal-footer border-0 p-4 bg-light">
                <button type="button" class="btn btn-academic-secondary px-4 py-2 border-0 rounded-pill" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-1"></i> ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* --- Calendar Layout & Styling --- */
.calendar-day-cell {
    width: 14.28%;
    padding: 6px 0;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}
.calendar-day-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: transparent;
    color: var(--text-dark, #333333);
    font-size: 0.82rem;
    font-weight: 600;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.25s ease;
    position: relative;
    cursor: pointer;
    font-family: 'Kanit', sans-serif;
}
.calendar-day-btn:hover {
    background-color: rgba(11, 44, 92, 0.08);
    color: var(--primary-blue);
}
.calendar-day-btn.empty-day {
    cursor: default;
    pointer-events: none;
    color: #cccccc;
}
.calendar-day-btn.today {
    border: 2px solid var(--primary-blue);
    color: var(--primary-blue);
    font-weight: bold;
}
.calendar-day-btn.has-event::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 5px;
    height: 5px;
    background-color: var(--accent-gold);
    border-radius: 50%;
    box-shadow: 0 0 5px var(--accent-gold);
}
.calendar-day-btn.active {
    background-color: var(--primary-blue) !important;
    color: #ffffff !important;
}
.calendar-day-btn.active::after {
    background-color: #ffffff;
    box-shadow: 0 0 5px #ffffff;
}

/* --- Mini Cards for selected events --- */
.event-mini-card {
    border-left: 4px solid var(--primary-blue);
    background: rgba(11, 44, 92, 0.02);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 8px;
    border-top: 1px solid var(--border);
    border-right: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}
.event-mini-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(11, 44, 92, 0.08);
    background: rgba(11, 44, 92, 0.04);
}
.event-mini-card.event-institution {
    border-left-color: var(--accent-gold);
}

/* --- Mobile Responsive Calendar Styles --- */
@media (max-width: 576px) {
    .calendar-day-btn {
        width: 30px;
        height: 30px;
        font-size: 0.78rem;
    }
    .calendar-day-cell {
        padding: 4px 0;
    }
    #calendarMonthYearLabel {
        font-size: 0.9rem !important;
    }
    .card-custom {
        padding: 1.25rem !important; /* ลดระยะขอบการ์ดในมือถือเพื่อให้มีเนื้อที่แสดงตารางเยอะขึ้น */
    }
    .event-mini-card {
        padding: 1rem !important;
    }
}
</style>

<script>
/** ฟังก์ชันเปิด Lightbox สำหรับแกลเลอรี */
function openLightbox(imagePath, captionText) {
    const myModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
    document.getElementById('lightboxImage').src = imagePath;
    document.getElementById('lightboxCaption').innerText = captionText;
    myModal.show();
}

// ----------------------------------------------------
// ระบบปฏิทินรายเดือน Interactive Event Calendar
// ----------------------------------------------------

// ข้อมูลกิจกรรมส่งตรงมาจาก PHP
const rawEvents = <?= json_encode($events) ?>;

// แปลงข้อมูลกิจกรรมให้เป็น Object Map ยึดวันที่ YYYY-MM-DD เป็น Key
const eventsMap = {};
rawEvents.forEach(ev => {
    const dateStr = ev.event_date;
    if (!eventsMap[dateStr]) {
        eventsMap[dateStr] = [];
    }
    eventsMap[dateStr].push(ev);
});

// สถานะวันที่ปฏิทินปัจจุบัน
let currentCalDate = new Date();

// ฟังก์ชันล้างข้อมูล HTML เพื่อความปลอดภัยป้องกัน XSS (Client-side escaping helper)
function jsEscape(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// สั่งวาดปฏิทิน
function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth(); // 0 - 11
    
    // รายชื่อเดือนภาษาไทย
    const thaiMonthsFull = [
        'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
        'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    ];
    
    const monthLabel = document.getElementById('calendarMonthYearLabel');
    if (monthLabel) {
        // เพิ่ม 543 ปีเป็น พ.ศ.
        monthLabel.innerText = `${thaiMonthsFull[month]} ${year + 543}`;
    }
    
    const daysGrid = document.getElementById('calendarDaysGrid');
    if (!daysGrid) return;
    daysGrid.innerHTML = '';
    
    // หาวันแรกของสัปดาห์ (0 = อาทิตย์, 6 = เสาร์)
    const firstDayIndex = new Date(year, month, 1).getDay();
    // หาจำนวนวันทั้งหมดในเดือนนั้น
    const totalDays = new Date(year, month + 1, 0).getDate();
    
    // สร้างช่องว่างช่วงต้นของเดือนก่อนวันแรก
    for (let i = 0; i < firstDayIndex; i++) {
        const cell = document.createElement('div');
        cell.className = 'calendar-day-cell';
        cell.innerHTML = '<button class="calendar-day-btn empty-day"></button>';
        daysGrid.appendChild(cell);
    }
    
    const today = new Date();
    
    // สร้างปุ่มวันที่ตามจำนวนจริงในเดือน
    for (let day = 1; day <= totalDays; day++) {
        const cell = document.createElement('div');
        cell.className = 'calendar-day-cell';
        
        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const hasEvent = !!eventsMap[dateString];
        
        let classes = 'calendar-day-btn';
        if (hasEvent) classes += ' has-event';
        if (today.getFullYear() === year && today.getMonth() === month && today.getDate() === day) {
            classes += ' today';
        }
        
        cell.innerHTML = `<button class="${classes}" data-date="${dateString}" onclick="selectDate('${dateString}', this)">${day}</button>`;
        daysGrid.appendChild(cell);
    }
}

// กดสลับเปลี่ยนเดือนย้อนหลัง/ถัดไป
function changeMonth(direction) {
    currentCalDate.setMonth(currentCalDate.getMonth() + direction);
    renderCalendar(currentCalDate);
    
    // เคลียร์วันที่เคยเลือก
    const selectedDateLabel = document.getElementById('selectedDateLabel');
    if (selectedDateLabel) selectedDateLabel.innerText = '-';
    
    // ดึงกิจกรรมที่จะถึงมาแสดง
    showUpcomingEventsForMonth(currentCalDate.getFullYear(), currentCalDate.getMonth());
}

// เลือกวันที่ในปฏิทิน
function selectDate(dateString, element) {
    // ลบคลาส active เดิมออก
    const activeBtns = document.querySelectorAll('.calendar-day-btn.active');
    activeBtns.forEach(btn => btn.classList.remove('active'));
    
    // ใส่คลาส active ให้วันที่เลือกใหม่
    if (element) {
        element.classList.add('active');
    }
    
    const [year, month, day] = dateString.split('-');
    const formattedDate = `${parseInt(day)}/${parseInt(month)}/${parseInt(year) + 543}`;
    
    const dateLabel = document.getElementById('selectedDateLabel');
    if (dateLabel) {
        dateLabel.innerText = formattedDate;
    }
    
    const events = eventsMap[dateString] || [];
    displaySelectedEvents(events);
}

// แสดงรายการกิจกรรมของวันที่เลือก
function displaySelectedEvents(events) {
    const listContainer = document.getElementById('selectedEventsList');
    if (!listContainer) return;
    listContainer.innerHTML = '';
    
    if (events.length === 0) {
        listContainer.innerHTML = `
            <div class="text-center py-5 text-muted bg-light rounded-4 border" style="border-style: dashed !important; border-color: var(--border-strong) !important;">
                <i class="fa-solid fa-calendar-minus fs-3 mb-2 opacity-50"></i>
                <p class="m-0 small">ไม่มีกิจกรรมในวันที่เลือก</p>
            </div>
        `;
        return;
    }
    
    events.forEach(ev => {
        const borderClass = ev.club_id ? '' : 'event-institution';
        const clubLabel = ev.club_id ? ev.club_name : 'กิจกรรมสถาบัน';
        const badgeColor = ev.club_id ? 'bg-primary' : 'bg-warning text-dark';
        
        let timeStr = 'ไม่ระบุเวลา';
        if (ev.start_time) {
            timeStr = ev.start_time.substring(0, 5);
            if (ev.end_time) {
                timeStr += ' - ' + ev.end_time.substring(0, 5);
            }
            timeStr += ' น.';
        }
        
        const locStr = ev.location ? ev.location : 'ไม่ระบุสถานที่';
        
        const card = document.createElement('div');
        card.className = `event-mini-card p-3 ${borderClass}`;
        card.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge ${badgeColor} small">${jsEscape(clubLabel)}</span>
                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>${timeStr}</small>
            </div>
            <h6 class="fw-bold text-primary-custom mb-2">${jsEscape(ev.title)}</h6>
            <div class="text-muted small mb-3">
                <i class="fa-solid fa-location-dot me-1 text-danger"></i> ${jsEscape(locStr)}
            </div>
            <div class="text-end">
                <button class="btn btn-sm btn-academic-primary px-3 py-1 rounded-pill border-0" onclick="openEventDetails(${ev.id})" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>ดูรายละเอียด
                </button>
            </div>
        `;
        listContainer.appendChild(card);
    });
}

// แสดงรายการกิจกรรมที่สำคัญที่จะเกิดขึ้นในเดือนนี้ (Upcoming Events)
function showUpcomingEventsForMonth(year, month) {
    const listContainer = document.getElementById('selectedEventsList');
    if (!listContainer) return;
    listContainer.innerHTML = '';
    
    // กรองเอากิจกรรมที่เกิดเฉพาะเดือนที่เลือก
    const monthStr = String(month + 1).padStart(2, '0');
    const prefix = `${year}-${monthStr}`;
    
    const monthEvents = rawEvents.filter(ev => ev.event_date.startsWith(prefix));
    
    if (monthEvents.length === 0) {
        listContainer.innerHTML = `
            <div class="text-center py-5 text-muted bg-light rounded-4 border" style="border-style: dashed !important; border-color: var(--border-strong) !important;">
                <i class="fa-solid fa-calendar-xmark fs-3 mb-2 opacity-50"></i>
                <p class="m-0 small">ไม่มีกิจกรรมในเดือนนี้</p>
            </div>
        `;
        return;
    }
    
    // หัวข้อชี้แจง
    const info = document.createElement('div');
    info.className = 'text-muted small mb-2';
    info.innerHTML = `กิจกรรมทั้งหมดในเดือนนี้ (${monthEvents.length} รายการ):`;
    listContainer.appendChild(info);
    
    // แสดงสูงสุด 4 งาน
    const limitEvents = monthEvents.slice(0, 4);
    
    limitEvents.forEach(ev => {
        const borderClass = ev.club_id ? '' : 'event-institution';
        const clubLabel = ev.club_id ? ev.club_name : 'กิจกรรมสถาบัน';
        const badgeColor = ev.club_id ? 'bg-primary' : 'bg-warning text-dark';
        
        const dateParts = ev.event_date.split('-');
        const dateStr = `${parseInt(dateParts[2])}/${parseInt(dateParts[1])}/${parseInt(dateParts[0]) + 543}`;
        
        let timeStr = '';
        if (ev.start_time) {
            timeStr = ' | ' + ev.start_time.substring(0, 5) + ' น.';
        }
        
        const card = document.createElement('div');
        card.className = `event-mini-card p-3 ${borderClass}`;
        card.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge ${badgeColor} small">${jsEscape(clubLabel)}</span>
                <small class="text-muted fw-bold"><i class="fa-regular fa-calendar-days me-1"></i>${dateStr}${timeStr}</small>
            </div>
            <h6 class="fw-bold text-primary-custom mb-2">${jsEscape(ev.title)}</h6>
            <div class="text-muted small mb-3">
                <i class="fa-solid fa-location-dot me-1 text-danger"></i> ${jsEscape(ev.location || 'ไม่ระบุสถานที่')}
            </div>
            <div class="text-end">
                <button class="btn btn-sm btn-academic-primary px-3 py-1 rounded-pill border-0" onclick="openEventDetails(${ev.id})" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>ดูรายละเอียด
                </button>
            </div>
        `;
        listContainer.appendChild(card);
    });
}

// เปิดดู Modal รายละเอียดกิจกรรมแบบเต็ม
function openEventDetails(eventId) {
    const ev = rawEvents.find(x => parseInt(x.id) === parseInt(eventId));
    if (!ev) return;
    
    document.getElementById('modalEventTitle').innerText = ev.title;
    document.getElementById('modalEventClub').innerText = ev.club_id ? ev.club_name : 'กิจกรรมสถาบัน';
    document.getElementById('modalEventClub').className = ev.club_id ? 'badge bg-primary px-3 py-2 fs-7' : 'badge bg-warning text-dark px-3 py-2 fs-7';
    
    const dateParts = ev.event_date.split('-');
    const dateStr = `${parseInt(dateParts[2])} ${getThaiMonthName(dateParts[1])} ${parseInt(dateParts[0]) + 543}`;
    document.getElementById('modalEventDate').innerText = dateStr;
    
    let timeStr = 'ไม่ระบุเวลา';
    if (ev.start_time) {
        timeStr = ev.start_time.substring(0, 5);
        if (ev.end_time) {
            timeStr += ' - ' + ev.end_time.substring(0, 5);
        }
        timeStr += ' น.';
    }
    document.getElementById('modalEventTime').innerText = timeStr;
    document.getElementById('modalEventLocation').innerText = ev.location || 'ไม่ระบุสถานที่';
    document.getElementById('modalEventDescription').innerText = ev.description || 'ไม่มีรายละเอียดเพิ่มเติม';
    
    const modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
    modal.show();
}

// ตัวแปลงเลขเดือนเป็นชื่อเต็มภาษาไทย
function getThaiMonthName(monthNumStr) {
    const months = {
        '01': 'มกราคม', '02': 'กุมภาพันธ์', '03': 'มีนาคม', '04': 'เมษายน',
        '05': 'พฤษภาคม', '06': 'มิถุนายน', '07': 'กรกฎาคม', '08': 'สิงหาคม',
        '09': 'กันยายน', '10': 'ตุลาคม', '11': 'พฤศจิกายน', '12': 'ธันวาคม'
    };
    return months[monthNumStr] || monthNumStr;
}

// เริ่มต้นทำงานเมื่อเพจโหลดเรียบร้อย
document.addEventListener('DOMContentLoaded', () => {
    renderCalendar(currentCalDate);
    showUpcomingEventsForMonth(currentCalDate.getFullYear(), currentCalDate.getMonth());
});
</script>
