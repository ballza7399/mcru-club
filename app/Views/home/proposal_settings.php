<?php
/**
 * @var array $settings
 */
$startVal = !empty($settings['club_proposal_period_start']) ? date('Y-m-d\TH:i', strtotime($settings['club_proposal_period_start'])) : '';
$endVal = !empty($settings['club_proposal_period_end']) ? date('Y-m-d\TH:i', strtotime($settings['club_proposal_period_end'])) : '';
$isEnabled = ($settings['club_proposal_period_enabled'] ?? 'false') === 'true';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0">
        <i class="fa-solid fa-clock text-warning me-2"></i>จัดการช่วงเวลาเปิดรับเสนอจัดตั้งชมรม
    </h4>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card-custom p-4 border shadow-sm h-100" style="background: var(--surface); border-color: var(--border);">
            <div class="border-bottom pb-2 mb-4">
                <h5 class="fw-bold text-dark m-0">
                    <i class="fa-solid fa-sliders text-primary me-2"></i>การกำหนดช่วงเวลาเปิดรับข้อเสนอ
                </h5>
                <p class="text-muted small m-0 mt-1">ตั้งค่าเปิดรับเสนอจัดตั้งชมรมใหม่ในระยะแรก (Phase I) สำหรับนักศึกษาทั่วไป</p>
            </div>

            <form action="<?= url('backoffice/settings/proposal/update') ?>" method="POST">
                <!-- Enabled / Disabled Toggle -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark d-block">ระบบการเปิดตรวจสอบช่วงเวลา</label>
                    <div class="d-flex gap-4 mt-2">
                        <div class="form-check form-check-inline p-3 border rounded-3 d-flex align-items-center flex-grow-1" style="cursor: pointer; background: rgba(11, 44, 92, 0.02); border-color: var(--border-strong) !important;">
                            <input class="form-check-input ms-0 me-2" type="radio" name="club_proposal_period_enabled" id="proposalActive" value="true" <?= $isEnabled ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                            <label class="form-check-label text-success fw-bold small mb-0" for="proposalActive" style="cursor: pointer;">
                                <i class="fa-solid fa-circle-check me-1"></i> เปิดตรวจสอบช่วงเวลา (Timeline)
                            </label>
                        </div>
                        <div class="form-check form-check-inline p-3 border rounded-3 d-flex align-items-center flex-grow-1" style="cursor: pointer; background: rgba(11, 44, 92, 0.02); border-color: var(--border-strong) !important;">
                            <input class="form-check-input ms-0 me-2" type="radio" name="club_proposal_period_enabled" id="proposalInactive" value="false" <?= !$isEnabled ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                            <label class="form-check-label text-secondary fw-bold small mb-0" for="proposalInactive" style="cursor: pointer;">
                                <i class="fa-solid fa-circle-xmark me-1"></i> ปิดตรวจสอบ (เสนอตั้งได้ตลอดเวลา)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Date Range Inputs -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark" for="club_proposal_period_start">วัน-เวลาเริ่มต้นเปิดรับสมัคร</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-calendar-plus"></i></span>
                                <input type="datetime-local" name="club_proposal_period_start" id="club_proposal_period_start" class="form-control text-dark" value="<?= e($startVal) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark" for="club_proposal_period_end">วัน-เวลาสิ้นสุดเปิดรับสมัคร</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-calendar-minus"></i></span>
                                <input type="datetime-local" name="club_proposal_period_end" id="club_proposal_period_end" class="form-control text-dark" value="<?= e($endVal) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end border-top pt-4" style="border-color: var(--border-strong) !important;">
                    <a href="<?= url('backoffice') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill me-2">
                        <i class="fa-solid fa-xmark me-1"></i> ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-academic-primary px-5 py-2 border-0">
                        <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Info & Explanation -->
    <div class="col-lg-5">
        <div class="card-custom p-4 border shadow-sm h-100" style="background: var(--surface); border-color: var(--border);">
            <div class="border-bottom pb-2 mb-4">
                <h5 class="fw-bold text-dark m-0">
                    <i class="fa-solid fa-circle-question text-info me-2"></i>คำชี้แจงและการเปิดรับจัดตั้ง
                </h5>
            </div>
            
            <div class="p-3 bg-light rounded-3 border mb-4">
                <h6 class="fw-bold text-primary-custom mb-2">เงื่อนไขการตรวจสอบช่วงเวลา:</h6>
                <ul class="small text-muted ps-3 mb-0" style="line-height: 1.7;">
                    <li>หากเลือก <strong>"เปิดตรวจสอบช่วงเวลา (Timeline)"</strong>: หน้าการขอก่อตั้งชมรมใหม่ (<a href="<?= url('clubs/register') ?>" target="_blank">/clubs/register</a>) จะเปิดให้เข้ากรอกข้อมูลเฉพาะในช่วงวันเวลาที่ระบุเท่านั้น</li>
                    <li>หากนักศึกษาเข้าใช้งานระบบจัดตั้งชมรมก่อนหรือหลังวันที่กำหนด ระบบจะบล็อกการสมัครและขึ้นข้อความแจ้งเตือน</li>
                    <li>หากเลือก <strong>"ปิดตรวจสอบ"</strong>: จะอนุญาตให้นักศึกษาสามารถเข้ามายื่นคำขอก่อตั้งได้ตลอดเวลาโดยไม่มีผลเรื่องกำหนดเวลา</li>
                </ul>
            </div>

            <div class="alert alert-info border-info-subtle d-flex align-items-start mb-0">
                <i class="fa-solid fa-clock-rotate-left fs-5 me-3 text-info mt-1"></i>
                <div class="small">
                    <strong>คำแนะนำสำหรับการเปิดระบบ:</strong><br>
                    ควรแนบไฟล์เทมเพลตคำขอจัดตั้งชมรมที่เป็นฟอร์มทางการจากสถาบัน และประสานงานตรวจสอบคุณสมบัติของผู้รับผิดชอบก่อนการรับสมัครจริง
                </div>
            </div>
        </div>
    </div>
</div>
