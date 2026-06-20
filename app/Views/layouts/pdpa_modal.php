<?php
if (needsPdpaConsent()):
    $policies = getActivePolicies();
    $tosTitle = 'เงื่อนไขและข้อตกลงการใช้งาน (Terms of Service)';
    $tosContent = '';
    $privacyTitle = 'นโยบายความเป็นส่วนตัว (Privacy Policy)';
    $privacyContent = '';
    
    foreach ($policies as $p) {
        if ($p['policy_key'] === 'terms_of_service') {
            $tosTitle = $p['title'];
            $tosContent = $p['content'];
        } elseif ($p['policy_key'] === 'privacy_policy') {
            $privacyTitle = $p['title'];
            $privacyContent = $p['content'];
        }
    }
?>
<!-- PDPA Consent Modal -->
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
                        <h6 class="fw-bold text-dark mb-2"><?= e($tosTitle) ?></h6>
                        <div class="text-muted small" style="line-height: 1.8; white-space: pre-wrap; text-align: justify;"><?= e($tosContent) ?></div>
                    </div>
                    <!-- Privacy Pane -->
                    <div class="tab-pane fade" id="privacy-content-pane" role="tabpanel" aria-labelledby="privacy-tab" tabindex="0">
                        <h6 class="fw-bold text-dark mb-2"><?= e($privacyTitle) ?></h6>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Show modal automatically
    const modalEl = document.getElementById('pdpaConsentModal');
    if (modalEl) {
        const pdpaModal = new bootstrap.Modal(modalEl, {
            backdrop: 'static',
            keyboard: false
        });
        pdpaModal.show();
        
        // Handle tab styling switches
        const tosTab = document.getElementById('tos-tab');
        const privacyTab = document.getElementById('privacy-tab');
        
        tosTab.addEventListener('shown.bs.tab', () => {
            tosTab.className = 'btn btn-academic-pill active px-4';
            privacyTab.className = 'btn btn-academic-pill-outline px-4';
        });
        
        privacyTab.addEventListener('shown.bs.tab', () => {
            privacyTab.className = 'btn btn-academic-pill active px-4';
            tosTab.className = 'btn btn-academic-pill-outline px-4';
        });
    }
});

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
<?php endif; ?>
