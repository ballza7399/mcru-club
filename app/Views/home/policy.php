<?php
/**
 * @var array $policies
 */
$tosTitle = 'เงื่อนไขและข้อตกลงการใช้งาน (Terms of Service)';
$tosContent = '';
$tosVersion = '1.0';
$tosUpdated = '';
$privacyTitle = 'นโยบายความเป็นส่วนตัว (Privacy Policy)';
$privacyContent = '';
$privacyVersion = '1.0';
$privacyUpdated = '';

foreach ($policies as $p) {
    if ($p['policy_key'] === 'terms_of_service') {
        $tosTitle = $p['title'];
        $tosContent = $p['content'];
        $tosVersion = $p['version'];
        $tosUpdated = $p['updated_at'];
    } elseif ($p['policy_key'] === 'privacy_policy') {
        $privacyTitle = $p['title'];
        $privacyContent = $p['content'];
        $privacyVersion = $p['version'];
        $privacyUpdated = $p['updated_at'];
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- Academic Hero Header Banner (Mini version) -->
        <div class="academic-hero-banner text-center text-white py-4 px-4 mb-5 position-relative rounded-4 overflow-hidden" style="min-height: 120px;">
            <div class="academic-pattern"></div>
            <div class="position-relative z-index-2">
                <span class="badge badge-academic-accent mb-2">PDPA COMPLIANCE</span>
                <h2 class="fw-bold mb-0 text-white" style="font-size: 1.8rem;">ข้อตกลงผู้ใช้และนโยบายความเป็นส่วนตัว</h2>
            </div>
        </div>

        <!-- Policy Card container -->
        <div class="card-custom p-4 p-md-5 border shadow-sm rounded-4" style="background: var(--surface); border-color: var(--border);">
            <div class="d-flex flex-column align-items-center mb-4">
                <ul class="nav nav-pills gap-2 justify-content-center" id="policyPageTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-academic-pill active px-4 py-2" id="tos-tab" data-bs-toggle="tab" data-bs-target="#tos-content" type="button" role="tab" aria-selected="true">
                            <i class="fa-solid fa-file-contract me-2"></i>ข้อตกลงการใช้งาน (TOS)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-academic-pill-outline px-4 py-2" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy-content" type="button" role="tab" aria-selected="false">
                            <i class="fa-solid fa-user-shield me-2"></i>นโยบายความเป็นส่วนตัว
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content pt-3 border-top" id="policyPageTabsContent" style="border-color: var(--border-strong) !important;">
                <!-- TOS Tab -->
                <div class="tab-pane fade show active" id="tos-content" role="tabpanel" aria-labelledby="tos-tab">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h4 class="fw-bold text-primary-custom m-0"><?= e($tosTitle) ?></h4>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-muted border">Version <?= e($tosVersion) ?></span>
                            <?php if ($tosUpdated): ?>
                                <span class="badge bg-light text-muted border">อัปเดตเมื่อ: <?= date('d/m/Y', strtotime($tosUpdated)) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-dark py-2" style="font-size: 1rem; line-height: 1.9; white-space: pre-wrap; text-align: justify;">
                        <?= e($tosContent) ?>
                    </div>
                </div>

                <!-- Privacy Policy Tab -->
                <div class="tab-pane fade" id="privacy-content" role="tabpanel" aria-labelledby="privacy-tab">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h4 class="fw-bold text-primary-custom m-0"><?= e($privacyTitle) ?></h4>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-muted border">Version <?= e($privacyVersion) ?></span>
                            <?php if ($privacyUpdated): ?>
                                <span class="badge bg-light text-muted border">อัปเดตเมื่อ: <?= date('d/m/Y', strtotime($privacyUpdated)) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-dark py-2" style="font-size: 1rem; line-height: 1.9; white-space: pre-wrap; text-align: justify;">
                        <?= e($privacyContent) ?>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 pt-4 border-top" style="border-color: var(--border) !important;">
                <a href="<?= url() ?>" class="btn-academic-outline-sm px-4 py-2 text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-2"></i> กลับหน้าหลักระบบ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Handle tab styling switches
    const tosTab = document.getElementById('tos-tab');
    const privacyTab = document.getElementById('privacy-tab');
    
    if (tosTab && privacyTab) {
        tosTab.addEventListener('shown.bs.tab', () => {
            tosTab.className = 'btn btn-academic-pill active px-4 py-2';
            privacyTab.className = 'btn btn-academic-pill-outline px-4 py-2';
        });
        
        privacyTab.addEventListener('shown.bs.tab', () => {
            privacyTab.className = 'btn btn-academic-pill active px-4 py-2';
            tosTab.className = 'btn btn-academic-pill-outline px-4 py-2';
        });
    }
});
</script>
