<?php
/**
 * @var string|null $error
 * @var array|null $club
 * @var bool $isEdit
 */
$isEdit = $isEdit ?? false;
$club = $club ?? null;
?>
<div class="row justify-content-center">
    <div class="col-lg-9 col-md-10">
        <!-- Academic Modern Card -->
        <div class="academic-card shadow-lg border-0 overflow-hidden">
            <!-- Header Banner with Gradient and Gold Ribbon -->
            <div class="academic-header text-center text-white py-5 px-4 position-relative">
                <div class="academic-pattern"></div>
                <div class="position-relative z-index-2">
                    <span class="badge badge-academic-accent mb-2">MCRU ONLINE DIRECTORY</span>
                    <h2 class="fw-bold mb-2 text-white"><?= $isEdit ? 'แก้ไขข้อมูลการเสนอขอก่อตั้งชมรม' : 'ยื่นเสนอขอก่อตั้งชมรมเข้าระบบ' ?></h2>
                    <p class="mb-0 text-white opacity-75 fw-light">กรอกข้อมูลรายละเอียดเพื่อทำการเสนอขอก่อตั้งชมรมผ่านทางกองพัฒนานักศึกษา</p>
                </div>
            </div>

            <div class="academic-body p-4 p-md-5">
                <!-- Academic Notice (Disclaimer) -->
                <div class="academic-notice mb-5">
                    <div class="d-flex align-items-start">
                        <div class="notice-icon-wrapper me-3">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <h5 class="notice-title">ชี้แจงขั้นตอนและข้อกำหนดของระบบ</h5>
                            <p class="notice-text mb-0">
                                <strong>ขั้นตอนขอก่อตั้ง (ระยะที่ 1):</strong> กรอกข้อมูลพื้นฐาน พร้อมระบุวัตถุประสงค์ อาจารย์ที่ปรึกษา และแนบไฟล์เอกสารขอก่อตั้งชมรม (.doc, .docx, .pdf) เจ้าหน้าที่จะทำการตรวจสอบ หากอนุมัติแล้วจึงจะเข้าสู่ระยะที่ 2 (รับสมัครสมาชิกให้ครบ 50 คนจากอย่างน้อย 3 คณะที่ต่างกัน)
                            </p>
                        </div>
                    </div>
                </div>

                <?php if ($isEdit && $club && $club['rejection_reason']): ?>
                    <div class="alert alert-warning-custom p-4 d-flex align-items-start mb-4 text-start">
                        <div class="alert-icon-wrapper text-warning me-3">
                            <i class="fa-solid fa-triangle-exclamation fs-3"></i>
                        </div>
                        <div>
                            <h5 class="alert-title fw-bold text-dark">หมายเหตุที่ต้องแก้ไขจากเจ้าหน้าที่</h5>
                            <p class="alert-text text-muted mb-0 font-monospace" style="white-space: pre-wrap; color: #b45309 !important;"><?= e($club['rejection_reason']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger-custom d-flex align-items-center mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-3 fs-4"></i>
                        <div><?= e($error) ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= url('clubs/register') ?>" enctype="multipart/form-data">
                    <!-- Form sections -->
                    <div class="form-section-title mb-4">
                        <span class="section-number">01</span> ข้อมูลพื้นฐานและรายละเอียดผู้เสนอจัดตั้ง
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom" for="club_name">
                            <i class="fa-solid fa-signature label-icon"></i>ชื่อชมรมที่ต้องการจัดตั้ง <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="club_name" name="club_name" class="form-control-custom" placeholder="ระบุชื่อชมรมของคุณ เช่น ชมรมคอมพิวเตอร์และนวัตกรรม" value="<?= $club ? e($club['club_name']) : '' ?>" required>
                        <div class="form-text-custom">กรุณาระบุชื่อชมรมภาษาไทยที่ต้องการเสนอจัดตั้ง</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom" for="description">
                            <i class="fa-solid fa-rectangle-list label-icon"></i>รายละเอียดชมรมเบื้องต้น <span class="text-danger">*</span>
                        </label>
                        <textarea id="description" name="description" class="form-control-custom" rows="4" placeholder="อธิบายประวัติ หรือรายละเอียดเกี่ยวกับชมรมเบื้องต้น..." required><?= $club ? e($club['description']) : '' ?></textarea>
                        <div class="form-text-custom">ระบุรายละเอียดสั้นๆ เพื่อให้เจ้าหน้าที่และนักศึกษาคนอื่นๆ อ่านทำความเข้าใจได้ง่าย</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom" for="advisor_name">
                            <i class="fa-solid fa-user-tie label-icon"></i>ชื่อ-นามสกุลอาจารย์ที่ปรึกษาชมรม <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="advisor_name" name="advisor_name" class="form-control-custom" placeholder="ระบุชื่ออาจารย์ที่ปรึกษาชมรม เช่น อ.ดร.สมชาย ใจดี" value="<?= $club ? e($club['advisor_name']) : '' ?>" required>
                        <div class="form-text-custom">ระบุชื่อและนามสกุลจริงของอาจารย์ที่ปรึกษาประจำชมรม</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">
                            <i class="fa-solid fa-bullseye label-icon"></i>วัตถุประสงค์ของการจัดตั้งชมรม (Dynamic List) <span class="text-danger">*</span>
                        </label>
                        <div id="objectives-container">
                            <?php 
                            $objs = [];
                            if ($club && !empty($club['objectives'])) {
                                $objs = json_decode($club['objectives'], true) ?: [];
                            }
                            if (empty($objs)) {
                                $objs = [''];
                            }
                            foreach ($objs as $index => $obj):
                            ?>
                                <div class="objective-row d-flex align-items-center mb-2 gap-2">
                                    <input type="text" name="objectives[]" class="form-control-custom" placeholder="ระบุวัตถุประสงค์ข้อที่ <?= $index + 1 ?>" value="<?= e($obj) ?>" required>
                                    <?php if ($index > 0): ?>
                                        <button type="button" class="btn btn-outline-danger btn-remove-objective" style="border-radius: 12px; padding: 12px 15px;">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="btn-add-objective" class="btn btn-academic-secondary btn-sm mt-2">
                            <i class="fa-solid fa-plus me-1"></i> เพิ่มวัตถุประสงค์
                        </button>
                        <div class="form-text-custom">คุณสามารถเพิ่มหัวข้อวัตถุประสงค์ได้แบบไดนามิกโดยกดปุ่มด้านบน</div>
                    </div>
                    
                    <div class="form-section-title mb-4 mt-5">
                        <span class="section-number">02</span> การจำกัดจำนวนและเอกสารจัดตั้ง
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label-custom" for="max_members">
                                <i class="fa-solid fa-users label-icon"></i>จำนวนสมาชิกสูงสุดที่สามารถเปิดรับได้
                            </label>
                            <div class="input-group-custom">
                                <input type="number" id="max_members" name="max_members" class="form-control-custom pe-5" value="<?= $club ? (int)$club['max_members'] : 50 ?>" min="5" max="500" required>
                                <span class="input-group-text-custom">คน</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">
                            <i class="fa-solid fa-file-pdf label-icon"></i>เอกสารคำขอเสนอจัดตั้งชมรม <span class="text-danger">*</span>
                        </label>
                        <div class="file-upload-wrapper" style="height: 130px;">
                            <input type="file" name="establishment_document" class="file-upload-input" accept=".doc,.docx,.pdf" <?= !$isEdit ? 'required' : '' ?>>
                            <div class="file-upload-trigger text-center">
                                <i class="fa-solid fa-file-signature upload-icon mb-2"></i>
                                <span class="upload-text d-block fw-bold mb-1">
                                    <?= ($club && $club['establishment_document']) ? basename($club['establishment_document']) : 'เลือกไฟล์เอกสารขอก่อตั้งชมรม' ?>
                                </span>
                                <span class="upload-hint d-block text-muted small">รองรับ Word (.doc, .docx) หรือ PDF (.pdf) ขนาดไม่เกิน 5MB</span>
                            </div>
                        </div>
                        <?php if ($club && $club['establishment_document']): ?>
                            <div class="mt-2 text-start">
                                <a href="<?= url($club['establishment_document']) ?>" target="_blank" class="small text-primary text-decoration-none">
                                    <i class="fa-solid fa-download me-1"></i> ดาวน์โหลดเอกสารเสนอจัดตั้งเดิมที่เคยแนบไว้
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="form-text-custom">กรุณาแนบไฟล์คำขอก่อตั้งที่ลงชื่อเรียบร้อยแล้วเป็นไฟล์ Word หรือ PDF</div>
                    </div>

                    <div class="form-section-title mb-4 mt-5">
                        <span class="section-number">03</span> สื่อประชาสัมพันธ์และการติดต่อ (ไม่จำเป็นต้องใส่ในขั้นแรก)
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label-custom">
                                <i class="fa-solid fa-image label-icon"></i>รูปภาพโลโก้ชมรม
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="logo" class="file-upload-input" accept="image/*">
                                <div class="file-upload-trigger text-center">
                                    <i class="fa-solid fa-cloud-arrow-up upload-icon mb-2"></i>
                                    <span class="upload-text d-block fw-bold mb-1">
                                        <?= ($club && $club['club_logo']) ? basename($club['club_logo']) : 'เลือกรูปภาพโลโก้' ?>
                                    </span>
                                    <span class="upload-hint d-block text-muted small">รองรับ PNG, JPG ขนาดไม่เกิน 2MB</span>
                                </div>
                            </div>
                            <?php if ($club && $club['club_logo']): ?>
                                <div class="mt-2 text-start">
                                    <a href="<?= url($club['club_logo']) ?>" target="_blank" class="small text-primary text-decoration-none">
                                        <i class="fa-solid fa-eye me-1"></i> ดูโลโก้เดิม
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label-custom">
                                <i class="fa-solid fa-qrcode label-icon"></i>QR Code ช่องทางติดต่อ
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="qr_code" class="file-upload-input" accept="image/*">
                                <div class="file-upload-trigger text-center">
                                    <i class="fa-solid fa-qrcode upload-icon mb-2"></i>
                                    <span class="upload-text d-block fw-bold mb-1">
                                        <?= ($club && $club['qr_code']) ? basename($club['qr_code']) : 'เลือกไฟล์ QR Code' ?>
                                    </span>
                                    <span class="upload-hint d-block text-muted small">คิวอาร์โค้ด LINE หรือกลุ่มสำหรับตอบคำถามสมาชิก</span>
                                </div>
                            </div>
                            <?php if ($club && $club['qr_code']): ?>
                                <div class="mt-2 text-start">
                                    <a href="<?= url($club['qr_code']) ?>" target="_blank" class="small text-primary text-decoration-none">
                                        <i class="fa-solid fa-eye me-1"></i> ดู QR Code เดิม
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-5">
                        <a href="<?= $isEdit ? url('clubs/register') : url('clubs') ?>" class="btn btn-academic-secondary">
                            <i class="fa-solid fa-arrow-left me-2"></i>ย้อนกลับ
                        </a>
                        <button type="submit" class="btn btn-academic-primary border-0">
                            <i class="fa-solid fa-paper-plane me-2"></i><?= $isEdit ? 'บันทึกการแก้ไขและส่งใหม่' : 'ส่งคำขอเสนอตั้งชมรม' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* --- Academic Modern Styles --- */
.academic-card {
    background: var(--surface);
    border-radius: 24px;
    box-shadow: 0 15px 35px rgba(11, 44, 92, 0.08) !important;
    border: 1px solid var(--border) !important;
    margin-bottom: 40px;
}

.academic-header {
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
    opacity: 0.15;
    background-image: radial-gradient(circle at 100% 150%, #ffffff 24%, #0b2c5c 24%, #0b2c5c 28%, #ffffff 28%, #ffffff 36%, transparent 36%, transparent),
                      radial-gradient(circle at 0% 150%, #ffffff 24%, #0b2c5c 24%, #0b2c5c 28%, #ffffff 28%, #ffffff 36%, transparent 36%, transparent);
    background-size: 40px 40px;
    z-index: 1;
}

.badge-academic-accent {
    background: rgba(249, 168, 38, 0.15);
    color: var(--accent-gold);
    font-size: 0.75rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 50px;
    border: 1px solid rgba(249, 168, 38, 0.3);
}

/* Academic Notice (Disclaimer) */
.academic-notice {
    background: rgba(26, 73, 128, 0.03);
    border: 1px dashed rgba(26, 73, 128, 0.3);
    border-left: 5px solid var(--primary-soft);
    border-radius: 16px;
    padding: 20px;
    text-align: left;
}

.notice-icon-wrapper {
    background: rgba(26, 73, 128, 0.12);
    color: var(--primary-blue);
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.notice-title {
    color: var(--text-dark);
    font-weight: 700;
    font-size: 1.05rem;
    margin-bottom: 6px;
}

.notice-text {
    font-size: 0.9rem;
    line-height: 1.6;
    color: var(--text-muted);
}

/* Alert Custom */
.alert-danger-custom {
    background: rgba(192, 57, 43, 0.05);
    border: 1px solid rgba(192, 57, 43, 0.2);
    border-left: 4px solid var(--danger);
    border-radius: 12px;
    padding: 15px 20px;
    color: var(--danger-ink);
}

.alert-warning-custom {
    background: rgba(249, 168, 38, 0.03);
    border: 1px solid rgba(249, 168, 38, 0.15);
    border-left: 4px solid var(--accent-gold) !important;
    border-radius: 16px;
}

/* Section Title */
.form-section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--primary-blue);
    display: flex;
    align-items: center;
    border-bottom: 2px solid var(--border);
    padding-bottom: 8px;
    text-align: left;
}

.section-number {
    background: var(--primary-blue);
    color: #fff;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
    margin-right: 10px;
}

/* Custom Controls */
.form-label-custom {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.label-icon {
    color: var(--primary-soft);
    margin-right: 8px;
    font-size: 0.95rem;
}

.form-control-custom {
    width: 100%;
    padding: 12px 18px;
    font-size: 0.95rem;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    background-color: var(--surface);
    color: var(--text-dark);
    transition: all var(--dur) var(--ease-out);
}

.form-control-custom:focus {
    border-color: var(--primary-soft);
    outline: none;
    box-shadow: 0 0 0 4px rgba(26, 73, 128, 0.12);
    background-color: #fff;
}

.form-text-custom {
    font-size: 0.825rem;
    color: var(--text-muted);
    margin-top: 6px;
    padding-left: 4px;
    text-align: left;
}

/* Input Group Custom */
.input-group-custom {
    position: relative;
    display: flex;
    align-items: stretch;
    width: 100%;
}

.input-group-custom .form-control-custom {
    flex: 1 1 auto;
}

.input-group-text-custom {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-weight: 600;
    pointer-events: none;
}

/* File Upload Premium Widget */
.file-upload-wrapper {
    position: relative;
    width: 100%;
    height: 120px;
}

.file-upload-input {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
}

.file-upload-trigger {
    position: absolute;
    width: 100%;
    height: 100%;
    border: 2px dashed var(--border);
    border-radius: 16px;
    background: var(--bg-light);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px;
    transition: all var(--dur) var(--ease-out);
}

.file-upload-wrapper:hover .file-upload-trigger {
    border-color: var(--primary-soft);
    background: rgba(26, 73, 128, 0.04);
}

.file-upload-wrapper .file-upload-input:focus + .file-upload-trigger {
    border-color: var(--primary-soft);
    box-shadow: 0 0 0 4px rgba(26, 73, 128, 0.12);
}

.upload-icon {
    font-size: 1.8rem;
    color: var(--primary-soft);
    transition: transform var(--dur) var(--ease-out);
}

.file-upload-wrapper:hover .upload-icon {
    transform: translateY(-3px);
}

.file-upload-trigger.has-file {
    border-color: var(--success);
    background: rgba(31, 122, 82, 0.03);
}

.file-upload-trigger.has-file .upload-icon {
    color: var(--success);
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
    text-decoration: none;
    border: none;
}

.btn-academic-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(11, 44, 92, 0.3);
    background: linear-gradient(135deg, var(--primary-soft) 0%, #205c9e 100%);
}

.btn-academic-secondary {
    background: transparent;
    color: var(--text-dark) !important;
    border: 1.5px solid var(--border) !important;
    font-weight: 600;
    padding: 12px 24px;
    border-radius: 50px;
    transition: all var(--dur) var(--ease-out);
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.btn-academic-secondary:hover {
    background: var(--bg-light);
    border-color: var(--text-muted) !important;
    transform: translateY(-1px);
}
</style>

<script>
// File inputs label updater
document.querySelectorAll('.file-upload-input').forEach(input => {
    input.addEventListener('change', function() {
        const file = this.files[0];
        const trigger = this.nextElementSibling;
        if (file) {
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            trigger.querySelector('.upload-text').innerText = fileName;
            trigger.querySelector('.upload-hint').innerText = `ขนาดไฟล์: ${fileSize} MB (คลิกเพื่อเปลี่ยนไฟล์)`;
            trigger.classList.add('has-file');
        }
    });
});

// Dynamic objectives logic
document.getElementById('btn-add-objective').addEventListener('click', function() {
    const container = document.getElementById('objectives-container');
    const rows = container.getElementsByClassName('objective-row');
    const newIndex = rows.length + 1;
    
    const div = document.createElement('div');
    div.className = 'objective-row d-flex align-items-center mb-2 gap-2';
    div.innerHTML = `
        <input type="text" name="objectives[]" class="form-control-custom" placeholder="ระบุวัตถุประสงค์ข้อที่ ${newIndex}" required>
        <button type="button" class="btn btn-outline-danger btn-remove-objective" style="border-radius: 12px; padding: 12px 15px;">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    `;
    container.appendChild(div);
    
    // Add remove event to the new button
    div.querySelector('.btn-remove-objective').addEventListener('click', function() {
        div.remove();
        reindexObjectives();
    });
});

function reindexObjectives() {
    const container = document.getElementById('objectives-container');
    const inputs = container.querySelectorAll('.objective-row input');
    inputs.forEach((input, index) => {
        input.placeholder = `ระบุวัตถุประสงค์ข้อที่ ${index + 1}`;
    });
}

// Bind remove event to initial buttons
document.querySelectorAll('.btn-remove-objective').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.objective-row').remove();
        reindexObjectives();
    });
});
</script>
