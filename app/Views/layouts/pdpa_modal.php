<?php
/**
 * ไฟล์ควบคุม Modal ด้าน PDPA & TOS
 * 1. บังคับยอมรับเมื่อเนื้อหามีการแก้ไขหรืออัปเดตเวอร์ชัน (needsPdpaConsent() == true)
 * 2. หน้าต่าง Modal แสดงผลนโยบายและประวัติความยินยอมย้อนหลังได้ตลอดเวลา (pdpaViewerModal)
 */

$activePolicies = getActivePolicies();
$tosTitle = 'เงื่อนไขและข้อตกลงการใช้งาน (Terms of Service)';
$tosContent = '';
$tosVersion = '1.0';
$tosUpdated = '';
$privacyTitle = 'นโยบายความเป็นส่วนตัว (Privacy Policy)';
$privacyContent = '';
$privacyVersion = '1.0';
$privacyUpdated = '';

foreach ($activePolicies as $p) {
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

$myConsents = getMyConsents();
?>

<?php if (needsPdpaConsent()): ?>
<!-- 1. PDPA Consent Modal (สำหรับบังคับกดยอมรับ - ปิดไม่ได้จนกว่าจะกดยอมรับหรือออกระบบ) -->
<div class="modal fade" id="pdpaConsentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="pdpaConsentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--surface);">
            <!-- Top Gradient Accent -->
            <div style="height: 5px; background: linear-gradient(90deg, var(--primary-blue) 0%, var(--accent-gold) 50%, var(--primary-soft) 100%);"></div>
            
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title fw-bold text-primary-custom d-flex align-items-center" id="pdpaConsentModalLabel">
                    <i class="fa-solid fa-shield-halved text-warning me-2 fs-4"></i>การคุ้มครองข้อมูลส่วนบุคคล (PDPA & TOS)
                </h5>
            </div>
            
            <div class="modal-body p-4">
                <p class="text-muted small mb-4">
                    เพื่อความปลอดภัยของข้อมูลนักศึกษาและประสิทธิภาพในการใช้งานระบบจัดการชมรม มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง โปรดอ่านรายละเอียดเงื่อนไขข้อตกลงและนโยบายความเป็นส่วนตัว (PDPA) ด้านล่างนี้ และกดยินยอมเพื่อดำเนินการเข้าใช้งานระบบต่อไป
                </p>
                
                <!-- Tab Controls for TOS & Privacy -->
                <ul class="nav nav-pills gap-2 mb-3 justify-content-center" id="pdpaTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-academic-pill active px-4" id="tos-tab" data-bs-toggle="tab" data-bs-target="#tos-content-pane" type="button" role="tab" aria-controls="tos-content-pane" aria-selected="true">
                            <i class="fa-solid fa-file-contract me-1"></i>ข้อตกลงการใช้งาน (TOS)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-academic-pill-outline px-4" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy-content-pane" type="button" role="tab" aria-controls="privacy-content-pane" aria-selected="false">
                            <i class="fa-solid fa-user-shield me-1"></i>นโยบายความเป็นส่วนตัว (Privacy Policy)
                        </button>
                    </li>
                </ul>
                
                <!-- Tab Contents -->
                <div class="tab-content border rounded-4 p-3 bg-light" id="pdpaTabContent" style="max-height: 320px; overflow-y: auto; border-color: var(--border-strong) !important;">
                    <!-- TOS Pane -->
                    <div class="tab-pane fade show active" id="tos-content-pane" role="tabpanel" aria-labelledby="tos-tab" tabindex="0">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <h6 class="fw-bold text-dark m-0"><?= e($tosTitle) ?></h6>
                            <span class="badge bg-white text-muted border font-monospace small">v<?= e($tosVersion) ?></span>
                        </div>
                        <div class="text-muted small" style="line-height: 1.8; white-space: pre-wrap; text-align: justify;"><?= e($tosContent) ?></div>
                    </div>
                    <!-- Privacy Pane -->
                    <div class="tab-pane fade" id="privacy-content-pane" role="tabpanel" aria-labelledby="privacy-tab" tabindex="0">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <h6 class="fw-bold text-dark m-0"><?= e($privacyTitle) ?></h6>
                            <span class="badge bg-white text-muted border font-monospace small">v<?= e($privacyVersion) ?></span>
                        </div>
                        <div class="text-muted small" style="line-height: 1.8; white-space: pre-wrap; text-align: justify;"><?= e($privacyContent) ?></div>
                    </div>
                </div>
                
                <!-- Consent Checkbox -->
                <div class="form-check mt-4 p-3 rounded-3 border d-flex align-items-center gap-2" style="background: rgba(11, 44, 92, 0.02); border-color: var(--border-strong) !important; cursor: pointer;" onclick="togglePdpaCheck()">
                    <input class="form-check-input ms-0 me-2" type="checkbox" id="pdpaCheck" style="width: 20px; height: 20px; cursor: pointer;" onclick="event.stopPropagation(); checkPdpaStatus()">
                    <label class="form-check-label text-dark fw-medium small mb-0" for="pdpaCheck" style="cursor: pointer;" onclick="event.stopPropagation(); togglePdpaCheck()">
                        ฉันได้อ่านรายละเอียดและตกลงยอมรับเงื่อนไขการใช้งานและนโยบายความเป็นส่วนตัวดังกล่าวข้างต้นทุกประการ
                    </label>
                </div>
            </div>
            
            <div class="modal-footer border-0 p-4 bg-light">
                <a href="<?= url('auth/logout') ?>" class="btn btn-outline-secondary px-4 rounded-pill me-auto">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> ออกจากระบบ
                </a>
                <button type="button" class="btn btn-academic-primary px-5 py-2 border-0" id="btnAcceptPdpa" disabled onclick="submitPdpaConsent()">
                    <i class="fa-solid fa-circle-check me-1"></i> กดยินยอมและเข้าสู่ระบบ
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 2. PDPA Viewer Modal (สำหรับกดอ่านย้อนหลังได้ตลอดเวลาจากปุ่มหรือลิงก์ต่าง ๆ) -->
<div class="modal fade" id="pdpaViewerModal" tabindex="-1" aria-labelledby="pdpaViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--surface);">
            <!-- Top Gradient Accent -->
            <div style="height: 5px; background: linear-gradient(90deg, var(--primary-blue) 0%, var(--accent-gold) 50%, var(--primary-soft) 100%);"></div>
            
            <div class="modal-header border-0 bg-light p-4 justify-content-between align-items-center">
                <h5 class="modal-title fw-bold text-primary-custom d-flex align-items-center m-0" id="pdpaViewerModalLabel">
                    <i class="fa-solid fa-shield-halved text-success me-2 fs-4"></i>นโยบายความเป็นส่วนตัวและข้อตกลงผู้ใช้
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <!-- Tab Controls for TOS, Privacy & Personal Consent Logs -->
                <ul class="nav nav-pills gap-2 mb-3 justify-content-center" id="pdpaViewerTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-academic-pill active px-4" id="tos-viewer-tab" data-bs-toggle="tab" data-bs-target="#tos-viewer-pane" type="button" role="tab" aria-selected="true">
                            <i class="fa-solid fa-file-contract me-1"></i>ข้อตกลงการใช้งาน (TOS)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-academic-pill-outline px-4" id="privacy-viewer-tab" data-bs-toggle="tab" data-bs-target="#privacy-viewer-pane" type="button" role="tab" aria-selected="false">
                            <i class="fa-solid fa-user-shield me-1"></i>นโยบายความเป็นส่วนตัว
                        </button>
                    </li>
                    <?php if (!empty($_SESSION['user_id'])): ?>
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-academic-pill-outline px-4" id="log-viewer-tab" data-bs-toggle="tab" data-bs-target="#log-viewer-pane" type="button" role="tab" aria-selected="false">
                            <i class="fa-solid fa-clock-rotate-left me-1"></i>ประวัติการยินยอมของคุณ
                        </button>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Tab Contents -->
                <div class="tab-content border rounded-4 p-4 bg-light" id="pdpaViewerTabContent" style="max-height: 380px; overflow-y: auto; border-color: var(--border-strong) !important;">
                    <!-- TOS Pane -->
                    <div class="tab-pane fade show active" id="tos-viewer-pane" role="tabpanel" aria-labelledby="tos-viewer-tab" tabindex="0">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="fw-bold text-dark m-0"><?= e($tosTitle) ?></h5>
                            <div class="d-flex gap-2">
                                <span class="badge bg-white text-muted border font-monospace">Version: <?= e($tosVersion) ?></span>
                                <?php if ($tosUpdated): ?>
                                    <span class="badge bg-white text-muted border">อัปเดต: <?= date('d/m/Y', strtotime($tosUpdated)) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-dark py-2" style="font-size: 0.95rem; line-height: 1.8; white-space: pre-wrap; text-align: justify;"><?= e($tosContent) ?></div>
                    </div>
                    
                    <!-- Privacy Pane -->
                    <div class="tab-pane fade" id="privacy-viewer-pane" role="tabpanel" aria-labelledby="privacy-viewer-tab" tabindex="0">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="fw-bold text-dark m-0"><?= e($privacyTitle) ?></h5>
                            <div class="d-flex gap-2">
                                <span class="badge bg-white text-muted border font-monospace">Version: <?= e($privacyVersion) ?></span>
                                <?php if ($privacyUpdated): ?>
                                    <span class="badge bg-white text-muted border">อัปเดต: <?= date('d/m/Y', strtotime($privacyUpdated)) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-dark py-2" style="font-size: 0.95rem; line-height: 1.8; white-space: pre-wrap; text-align: justify;"><?= e($privacyContent) ?></div>
                    </div>
                    
                    <!-- Log Pane (Only for Logged-in Users) -->
                    <?php if (!empty($_SESSION['user_id'])): ?>
                    <div class="tab-pane fade" id="log-viewer-pane" role="tabpanel" aria-labelledby="log-viewer-tab" tabindex="0">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-signature text-success me-2"></i>ประวัติการกดยอมรับนโยบาย MCRU Clubs</h5>
                        
                        <?php if (empty($myConsents)): ?>
                            <div class="text-center py-5 text-muted bg-white rounded-4 border" style="border-style: dashed !important;">
                                <i class="fa-solid fa-signature fs-2 mb-2 opacity-50"></i>
                                <p class="m-0 small">ไม่พบประวัติการยินยอมในระบบ</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle table-sm border-0 bg-white rounded-3 overflow-hidden shadow-sm" style="font-size: 0.85rem;">
                                    <thead class="table-light">
                                        <tr class="border-bottom">
                                            <th class="py-2 ps-3 text-dark fw-bold">นโยบาย/ข้อตกลง</th>
                                            <th class="py-2 text-dark fw-bold">เวอร์ชัน</th>
                                            <th class="py-2 text-dark fw-bold">วันที่ยอมรับ</th>
                                            <th class="py-2 pe-3 text-dark fw-bold">บันทึก IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($myConsents as $c): ?>
                                            <tr class="border-bottom">
                                                <td class="py-2 ps-3">
                                                    <?php if ($c['policy_key'] === 'terms_of_service'): ?>
                                                        <span class="badge bg-primary-custom text-primary small px-2 py-1 rounded"><i class="fa-solid fa-file-contract me-1"></i>TOS</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success-custom text-success small px-2 py-1 rounded"><i class="fa-solid fa-user-shield me-1"></i>Privacy Policy</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-2 font-monospace fw-bold text-dark">v<?= e($c['version']) ?></td>
                                                <td class="py-2 text-muted"><?= date('d/m/Y H:i น.', strtotime($c['consented_at'])) ?></td>
                                                <td class="py-2 pe-3 text-muted font-monospace"><?= e($c['ip_address'] ?? '-') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. จัดการ Modal บังคับยอมรับ (ถ้ามี)
    const consentModalEl = document.getElementById('pdpaConsentModal');
    if (consentModalEl) {
        const pdpaModal = new bootstrap.Modal(consentModalEl, {
            backdrop: 'static',
            keyboard: false
        });
        pdpaModal.show();
        
        // สลับสไตล์แท็บ
        const tosTab = document.getElementById('tos-tab');
        const privacyTab = document.getElementById('privacy-tab');
        
        if (tosTab && privacyTab) {
            tosTab.addEventListener('shown.bs.tab', () => {
                tosTab.className = 'btn btn-academic-pill active px-4';
                privacyTab.className = 'btn btn-academic-pill-outline px-4';
            });
            
            privacyTab.addEventListener('shown.bs.tab', () => {
                privacyTab.className = 'btn btn-academic-pill active px-4';
                tosTab.className = 'btn btn-academic-pill-outline px-4';
            });
        }
    }
    
    // 2. จัดการสไตล์แท็บของ Viewer Modal (กดอ่านย้อนหลัง)
    const tosViewerTab = document.getElementById('tos-viewer-tab');
    const privacyViewerTab = document.getElementById('privacy-viewer-tab');
    const logViewerTab = document.getElementById('log-viewer-tab');
    
    const tabs = [tosViewerTab, privacyViewerTab, logViewerTab].filter(t => t !== null);
    
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', () => {
            tabs.forEach(t => {
                if (t === tab) {
                    t.className = 'btn btn-academic-pill active px-4';
                } else {
                    t.className = 'btn btn-academic-pill-outline px-4';
                }
            });
        });
    });
});

// ฟังก์ชันสำหรับเรียกเปิด Modal ย้อนหลัง
function openPdpaViewerModal(event) {
    if (event) {
        event.preventDefault();
    }
    const modalEl = document.getElementById('pdpaViewerModal');
    if (modalEl) {
        const pdpaViewerModal = new bootstrap.Modal(modalEl);
        pdpaViewerModal.show();
    }
}

function togglePdpaCheck() {
    const chk = document.getElementById('pdpaCheck');
    if (chk) {
        chk.checked = !chk.checked;
        checkPdpaStatus();
    }
}

function checkPdpaStatus() {
    const chk = document.getElementById('pdpaCheck');
    const btn = document.getElementById('btnAcceptPdpa');
    if (chk && btn) {
        btn.disabled = !chk.checked;
    }
}

function submitPdpaConsent() {
    const btn = document.getElementById('btnAcceptPdpa');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึกความยินยอม...';
    }
    
    fetch('<?= url("api/pdpa/consent") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'บันทึกสำเร็จ!',
                text: 'ขอบคุณที่ให้ความยินยอมการเข้าใช้งานระบบ',
                icon: 'success',
                confirmButtonColor: '#0b2c5c',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                text: data.message || 'โปรดลองใหม่อีกครั้งในภายหลัง',
                icon: 'error',
                confirmButtonColor: '#0b2c5c'
            });
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> กดยินยอมและเข้าสู่ระบบ';
            }
        }
    })
    .catch(err => {
        console.error('Error recording PDPA consent:', err);
        Swal.fire({
            title: 'เชื่อมต่อล้มเหลว',
            text: 'กรุณาตรวจสอบการเชื่อมต่ออินเทอร์เน็ตของคุณ',
            icon: 'error',
            confirmButtonColor: '#0b2c5c'
        });
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> กดยินยอมและเข้าสู่ระบบ';
        }
    });
}
</script>
