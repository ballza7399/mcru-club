<?php
/**
 * @var array $clubs
 */
?>

<div class="mb-5 text-center">
    <span class="badge bg-warning text-dark mb-2 px-3 py-2 fw-bold text-uppercase" style="border-radius: 30px; letter-spacing: 1px;">MCRU Clubs</span>
    <h2 class="display-6 fw-bold text-primary-custom">ค้นหาและเข้าร่วมชมรม</h2>
    <p class="text-muted col-md-8 mx-auto">เลือกชมรมที่ตรงกับความสนใจของคุณ พัฒนาทักษะและสร้างความสัมพันธ์ใหม่ๆ ไปกับเพื่อนนักศึกษา</p>
</div>

<!-- Search and Filter Controls -->
<div class="card-custom p-4 mb-4 shadow-sm">
    <div class="row g-3 align-items-center">
        <!-- Search Bar -->
        <div class="col-md-6 col-lg-7">
            <div class="search-input-group m-0" style="max-width: 100%;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="clubSearchInput" class="form-control border-0 bg-transparent shadow-none" placeholder="พิมพ์ชื่อชมรม หรือรายละเอียดเพื่อค้นหา..." onkeyup="applyFilters()">
            </div>
        </div>
        <!-- Status Filter Pills -->
        <div class="col-md-6 col-lg-5">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end" id="statusFilterGroup">
                <button type="button" class="btn btn-sm btn-gold-custom active px-3 rounded-pill filter-pill" data-status="all" onclick="setStatusFilter('all', this)">
                    ทั้งหมด (<?= count($clubs) ?>)
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill filter-pill" data-status="open" onclick="setStatusFilter('open', this)">
                    <i class="fa-solid fa-circle-check text-success me-1"></i>ยังเปิดรับ
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill filter-pill" data-status="full" onclick="setStatusFilter('full', this)">
                    <i class="fa-solid fa-circle-xmark text-danger me-1"></i>เต็มแล้ว
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clubs Grid -->
<div class="row g-4" id="clubsGrid">
    <?php if (empty($clubs)): ?>
        <div class="col-12 text-center py-5 text-muted bg-white rounded shadow-sm">
            <i class="fa-regular fa-folder-open fs-2 mb-2"></i>
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
                <div class="card-custom h-100 text-center d-flex flex-column">
                    <div class="club-banner"></div>
                    <div class="px-3 pb-4 d-flex flex-column flex-grow-1 align-items-center">
                        <?php if (assetExists($row['club_logo'])): ?>
                            <img src="<?= asset($row['club_logo']) ?>" class="club-logo-thumb" alt="Logo">
                        <?php else: ?>
                            <div class="club-logo-thumb bg-light d-flex align-items-center justify-content-center text-muted">No Image</div>
                        <?php endif; ?>
                        
                        <h5 class="text-primary-custom fw-bold mt-3 mb-1 club-name-title"><?= e($row['club_name']) ?></h5>
                        
                        <div class="mb-2">
                            <span class="member-badge member-badge--<?= $tone ?>">
                                <i class="fa-solid fa-users"></i>
                                <?= $cur ?> / <?= $max ?>
                            </span>
                            <?php if ($isFull): ?>
                                <span class="badge bg-danger ms-1 px-2 py-1" style="font-size: 0.75rem; border-radius: 30px;">เต็มแล้ว</span>
                            <?php else: ?>
                                <span class="badge bg-success ms-1 px-2 py-1" style="font-size: 0.75rem; border-radius: 30px;">เปิดรับสมัคร</span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="club-desc flex-grow-1 mb-4"><?= mb_substr(e($row['description']), 0, 90) . '...' ?></p>
                        <a href="<?= url('clubs/detail/' . $row['id']) ?>" class="btn-outline-custom w-100 py-2">รายละเอียด / สมัคร</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- No Results Alert -->
<div id="noResultsAlert" class="text-center py-5 text-muted bg-white rounded shadow-sm d-none mt-4">
    <i class="fa-solid fa-magnifying-glass fs-2 mb-2"></i>
    <p class="m-0">ไม่พบชมรมที่ตรงกับการค้นหาและตัวกรองของคุณ</p>
</div>

<script>
let currentStatusFilter = 'all';

function setStatusFilter(status, element) {
    // Update active visual class on filter pills
    document.querySelectorAll('.filter-pill').forEach(btn => {
        btn.classList.remove('btn-gold-custom', 'active');
        btn.classList.add('btn-outline-secondary');
    });
    
    element.classList.remove('btn-outline-secondary');
    element.classList.add('btn-gold-custom', 'active');
    
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
        
        // Matches search query?
        const matchesSearch = name.includes(searchVal) || desc.includes(searchVal);
        
        // Matches status filter?
        const matchesStatus = (currentStatusFilter === 'all') || (status === currentStatusFilter);
        
        if (matchesSearch && matchesStatus) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Toggle No Results View
    const alertBox = document.getElementById('noResultsAlert');
    if (visibleCount === 0 && wrappers.length > 0) {
        alertBox.classList.remove('d-none');
    } else {
        alertBox.classList.add('d-none');
    }
}
</script>
