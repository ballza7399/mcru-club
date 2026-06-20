<?php
/**
 * @var array $clubs
 */
?>

<!-- Academic Hero Header Banner -->
<div class="academic-hero-banner text-center text-white py-5 px-4 mb-5 position-relative rounded-4 overflow-hidden">
    <div class="academic-pattern"></div>
    <div class="position-relative z-index-2 py-3">
        <span class="badge badge-academic-accent mb-2">MCRU CLUBS DIRECTORY</span>
        <h1 class="display-5 fw-bold mb-2 text-white">ค้นหาและเข้าร่วมชมรม</h1>
        <p class="mb-0 text-white opacity-75 col-md-8 mx-auto fw-light">เลือกชมรมที่ตรงกับความสนใจของคุณ ร่วมกิจกรรม พัฒนาศักยภาพ และสร้างมิตรภาพใหม่ ๆ ไปกับเพื่อนนักศึกษา</p>
    </div>
</div>

<!-- Search and Filter Controls -->
<div class="search-card p-4 mb-5 rounded-4 shadow-sm border" style="background: var(--surface); border-color: var(--border) !important;">
    <div class="row g-3 align-items-center">
        <!-- Search Bar -->
        <div class="col-md-7">
            <div class="search-input-wrapper position-relative">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="clubSearchInput" class="form-control-custom search-input" placeholder="พิมพ์ชื่อชมรม หรือรายละเอียดเพื่อค้นหา..." onkeyup="applyFilters()">
            </div>
        </div>
        <!-- Status Filter Pills -->
        <div class="col-md-5">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end" id="statusFilterGroup">
                <button type="button" class="btn btn-academic-pill active" data-status="all" onclick="setStatusFilter('all', this)">
                    ทั้งหมด (<?= count($clubs) ?>)
                </button>
                <button type="button" class="btn btn-academic-pill-outline" data-status="open" onclick="setStatusFilter('open', this)">
                    <i class="fa-solid fa-circle-check text-success me-1"></i>ยังเปิดรับ
                </button>
                <button type="button" class="btn btn-academic-pill-outline" data-status="full" onclick="setStatusFilter('full', this)">
                    <i class="fa-solid fa-circle-xmark text-danger me-1"></i>เต็มแล้ว
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clubs Grid -->
<div class="row g-4" id="clubsGrid">
    <?php if (empty($clubs)): ?>
        <div class="col-12 text-center py-5 text-muted bg-white rounded shadow-sm border dashed">
            <i class="fa-regular fa-folder-open fs-2 mb-2 text-secondary"></i>
            <p class="m-0">ยังไม่มีข้อมูลชมรมในขณะนี้</p>
        </div>
    <?php else: ?>
        <?php foreach ($clubs as $row): ?>
            <?php
                $cur = (int) $row['current_members'];
                $max = (int) $row['max_members'];
                $ratio = $max > 0 ? $cur / $max : 1;
                $isFull = $ratio >= 1;
                $statusType = $isFull ? 'full' : 'open';
                $tone = $isFull ? 'full' : ($ratio >= 0.8 ? 'warn' : 'open');
            ?>
            <div class="col-md-6 col-lg-4 club-card-wrapper" 
                 data-name="<?= strtolower(e($row['club_name'])) ?>" 
                 data-desc="<?= strtolower(e($row['description'])) ?>"
                 data-status="<?= $statusType ?>">
                <div class="card-custom h-100 text-center d-flex flex-column border-0 shadow-sm" style="border: 1px solid var(--border) !important; border-radius: 20px; overflow: hidden; background: var(--surface);">
                    <div class="club-banner" style="height: 100px; background: linear-gradient(135deg, var(--primary-blue), var(--primary-soft)); position: relative;">
                        <!-- Underline ribbon for visual detail -->
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: var(--accent-gold);"></div>
                    </div>
                    <div class="px-3 pb-4 d-flex flex-column flex-grow-1 align-items-center position-relative">
                        <?php if (assetExists($row['club_logo'])): ?>
                            <img src="<?= asset($row['club_logo']) ?>" class="club-logo-thumb shadow-sm" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 4px solid var(--surface); background: var(--surface); margin-top: -40px; position: relative; z-index: 2;" alt="Logo">
                        <?php else: ?>
                            <div class="club-logo-thumb bg-light d-flex align-items-center justify-content-center text-muted shadow-sm border" style="width: 80px; height: 80px; border-radius: 50%; margin-top: -40px; position: relative; z-index: 2; font-size: 0.75rem; font-weight: bold; background: var(--surface);">No Logo</div>
                        <?php endif; ?>
                        
                        <h5 class="text-primary-custom fw-bold mt-3 mb-2 club-name-title" style="min-height: 48px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= e($row['club_name']) ?></h5>
                        
                        <div class="mb-3">
                            <span class="member-badge member-badge--<?= $tone ?> px-2 py-1 small rounded-pill">
                                <i class="fa-solid fa-users me-1"></i>
                                <?= $cur ?> / <?= $max ?> คน
                            </span>
                            <?php if ($isFull): ?>
                                <span class="badge bg-danger-custom ms-1 px-2 py-1 text-danger fw-bold" style="font-size: 0.75rem; border-radius: 30px;">เต็มแล้ว</span>
                            <?php else: ?>
                                <span class="badge bg-success-custom ms-1 px-2 py-1 text-success fw-bold" style="font-size: 0.75rem; border-radius: 30px;">เปิดรับสมัคร</span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="club-desc flex-grow-1 mb-4 text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6; min-height: 48px;"><?= e($row['description']) ?></p>
                        
                        <a href="<?= url('clubs/detail/' . $row['id']) ?>" class="btn-academic-outline-sm w-100 py-2 text-decoration-none">
                            รายละเอียด / สมัครเข้าชมรม <i class="fa-solid fa-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- No Results Alert -->
<div id="noResultsAlert" class="text-center py-5 text-muted bg-white rounded shadow-sm border dashed d-none mt-4">
    <i class="fa-solid fa-magnifying-glass fs-2 mb-2 text-secondary"></i>
    <p class="m-0">ไม่พบชมรมที่ตรงกับการค้นหาและตัวกรองของคุณ</p>
</div>

<style>
/* --- Academic Modern Styles for list.php --- */
.academic-hero-banner {
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
    opacity: 0.12;
    background-image: radial-gradient(circle at 100% 150%, #ffffff 24%, #0b2c5c 24%, #0b2c5c 28%, #ffffff 28%, #ffffff 36%, transparent 36%, transparent),
                      radial-gradient(circle at 0% 150%, #ffffff 24%, #0b2c5c 24%, #0b2c5c 28%, #ffffff 28%, #ffffff 36%, transparent 36%, transparent);
    background-size: 40px 40px;
    z-index: 1;
}

.badge-academic-accent {
    background: rgba(249, 168, 38, 0.2);
    color: var(--accent-gold);
    font-size: 0.75rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 50px;
    border: 1px solid rgba(249, 168, 38, 0.4);
}

/* Custom Search Input */
.search-input-wrapper .search-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 1.05rem;
    pointer-events: none;
    z-index: 5;
}

.form-control-custom.search-input {
    width: 100%;
    padding: 12px 20px 12px 48px;
    font-size: 0.95rem;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    background-color: var(--bg-light);
    color: var(--text-dark);
    transition: all var(--dur) var(--ease-out);
}

.form-control-custom.search-input:focus {
    border-color: var(--primary-soft);
    outline: none;
    box-shadow: 0 0 0 4px rgba(26, 73, 128, 0.12);
    background-color: #fff;
}

/* Filter Pills */
.btn-academic-pill {
    background: var(--primary-blue);
    color: #fff !important;
    border: 1.5px solid var(--primary-blue);
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all var(--dur) var(--ease-out);
}

.btn-academic-pill:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(11, 44, 92, 0.15);
}

.btn-academic-pill-outline {
    background: transparent;
    color: var(--text-muted);
    border: 1.5px solid var(--border-strong);
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all var(--dur) var(--ease-out);
}

.btn-academic-pill-outline:hover {
    background: var(--bg-light);
    color: var(--text-dark);
    border-color: var(--text-muted);
    transform: translateY(-1px);
}

/* Custom badge colors */
.bg-success-custom {
    background-color: rgba(31, 122, 82, 0.1) !important;
}

.bg-danger-custom {
    background-color: rgba(192, 57, 43, 0.1) !important;
}

/* Outlined button for academic lists */
.btn-academic-outline-sm {
    background: transparent;
    color: var(--primary-soft) !important;
    border: 1.5px solid var(--border-strong);
    font-weight: 600;
    font-size: 0.875rem;
    padding: 8px 16px;
    border-radius: 50px;
    transition: all var(--dur) var(--ease-out);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-academic-outline-sm:hover {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-soft) 100%);
    color: #ffffff !important;
    border-color: transparent;
    transform: translateY(-1.5px);
    box-shadow: 0 4px 12px rgba(11, 44, 92, 0.15);
}

.dashed {
    border-style: dashed !important;
    border-color: var(--border-strong) !important;
}
</style>

<script>
let currentStatusFilter = 'all';

function setStatusFilter(status, element) {
    document.querySelectorAll('#statusFilterGroup button').forEach(btn => {
        btn.className = 'btn btn-academic-pill-outline';
    });
    
    element.className = 'btn btn-academic-pill active';
    
    currentStatusFilter = status;
    applyFilters();
}

function applyFilters() {
    const searchVal = document.getElementById('clubSearchInput').value.toLowerCase().trim();
    const wrappers = document.querySelectorAll('.club-card-wrapper');
    let visibleCount = 0;
    
    wrappers.forEach(card => {
        const name = card.getAttribute('data-name');
        const desc = card.getAttribute('data-desc');
        const status = card.getAttribute('data-status'); // 'open' or 'full'
        
        const matchesSearch = name.includes(searchVal) || desc.includes(searchVal);
        const matchesStatus = (currentStatusFilter === 'all') || (status === currentStatusFilter);
        
        if (matchesSearch && matchesStatus) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    const alertBox = document.getElementById('noResultsAlert');
    if (visibleCount === 0 && wrappers.length > 0) {
        alertBox.classList.remove('d-none');
    } else {
        alertBox.classList.add('d-none');
    }
}
</script>
