<?php
/**
 * @var string|null $error
 */
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
                    <h2 class="fw-bold mb-2 text-white">ยื่นเสนอขอเพิ่มข้อมูลชมรมเข้าระบบ</h2>
                    <p class="mb-0 text-white opacity-75 fw-light">กรอกข้อมูลพื้นฐานเพื่อนำข้อมูลชมรมเข้าสู่ฐานข้อมูลออนไลน์ของมหาวิทยาลัย</p>
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
                            <h5 class="notice-title">ชี้แจงบทบาทและข้อกำหนดของระบบ</h5>
                            <p class="notice-text mb-0">
                                <strong>หมายเหตุสำคัญ:</strong> ระบบออนไลน์นี้เป็นเพียงช่องทางเสนอขอเพิ่มข้อมูลรายละเอียดชมรมเข้าสู่ระบบสารสนเทศของมหาวิทยาลัยเท่านั้น <strong>ไม่ใช่การจัดตั้งชมรมอย่างเป็นทางการ</strong> โดยการพิจารณาอนุมัติจัดตั้งชมรมจริงตามระเบียบสถาบันจะต้องดำเนินการผ่านทาง <strong>กองพัฒนานักศึกษา</strong> ตามขั้นตอนทางเอกสารของมหาวิทยาลัย
                            </p>
                        </div>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger-custom d-flex align-items-center mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-3 fs-4"></i>
                        <div><?= e($error) ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= url('clubs/register') ?>" enctype="multipart/form-data">
                    <!-- Form sections -->
                    <div class="form-section-title mb-4">
                        <span class="section-number">01</span> ข้อมูลพื้นฐานของชมรม
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom" for="club_name">
                            <i class="fa-solid fa-signature label-icon"></i>ชื่อชมรม <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="club_name" name="club_name" class="form-control-custom" placeholder="ระบุชื่อชมรมของคุณ เช่น ชมรมคอมพิวเตอร์และนวัตกรรม" required>
                        <div class="form-text-custom">กรุณาระบุชื่อชมรมภาษาไทยที่ต้องการเสนอข้อมูลเข้าระบบ</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom" for="description">
                            <i class="fa-solid fa-rectangle-list label-icon"></i>รายละเอียดและวัตถุประสงค์ชมรม <span class="text-danger">*</span>
                        </label>
                        <textarea id="description" name="description" class="form-control-custom" rows="5" placeholder="อธิบายประวัติ วัตถุประสงค์ หรือกิจกรรมหลักของชมรม..." required></textarea>
                        <div class="form-text-custom">ระบุวัตถุประสงค์ ข้อตกลง หรือเงื่อนไขของชมรมให้ครบถ้วนเพื่อผลการพิจารณาที่รวดเร็ว</div>
                    </div>
                    
                    <div class="form-section-title mb-4 mt-5">
                        <span class="section-number">02</span> การตั้งค่าและการรับสมัคร
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label-custom" for="max_members">
                                <i class="fa-solid fa-users label-icon"></i>จำนวนสมาชิกสูงสุดที่เปิดรับ
                            </label>
                            <div class="input-group-custom">
                                <input type="number" id="max_members" name="max_members" class="form-control-custom pe-5" value="50" min="5" max="500" required>
                                <span class="input-group-text-custom">คน</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title mb-4 mt-4">
                        <span class="section-number">03</span> สื่อประชาสัมพันธ์และการติดต่อ
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label-custom">
                                <i class="fa-solid fa-image label-icon"></i>รูปภาพโลโก้ชมรม (ถ้ามี)
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="logo" class="file-upload-input" accept="image/*">
                                <div class="file-upload-trigger text-center">
                                    <i class="fa-solid fa-cloud-arrow-up upload-icon mb-2"></i>
                                    <span class="upload-text d-block fw-bold mb-1">เลือกรูปภาพโลโก้</span>
                                    <span class="upload-hint d-block text-muted small">รองรับ PNG, JPG ขนาดไม่เกิน 2MB</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label-custom">
                                <i class="fa-solid fa-qrcode label-icon"></i>QR Code กลุ่มสำหรับติดต่อ (ถ้ามี)
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="qr_code" class="file-upload-input" accept="image/*">
                                <div class="file-upload-trigger text-center">
                                    <i class="fa-solid fa-qrcode upload-icon mb-2"></i>
                                    <span class="upload-text d-block fw-bold mb-1">เลือกไฟล์ QR Code</span>
                                    <span class="upload-hint d-block text-muted small">คิวอาร์โค้ดกลุ่ม LINE, Facebook หรือดิสคอร์ด</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-5">
                        <a href="<?= url('clubs') ?>" class="btn btn-academic-secondary">
                            <i class="fa-solid fa-arrow-left me-2"></i>ย้อนกลับ
                        </a>
                        <button type="submit" class="btn btn-academic-primary border-0">
                            <i class="fa-solid fa-paper-plane me-2"></i>ส่งข้อเสนอขอเพิ่มข้อมูลชมรม
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
    background: rgba(249, 168, 38, 0.04);
    border: 1px dashed rgba(249, 168, 38, 0.4);
    border-left: 5px solid var(--accent-gold);
    border-radius: 16px;
    padding: 20px;
}

.notice-icon-wrapper {
    background: rgba(249, 168, 38, 0.12);
    color: var(--accent-gold-deep);
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

/* Section Title */
.form-section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--primary-blue);
    display: flex;
    align-items: center;
    border-bottom: 2px solid var(--border);
    padding-bottom: 8px;
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
}

.btn-academic-secondary:hover {
    background: var(--bg-light);
    border-color: var(--text-muted) !important;
    transform: translateY(-1px);
}
</style>

<script>
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
        } else {
            trigger.querySelector('.upload-text').innerText = 'เลือกไฟล์';
            trigger.querySelector('.upload-hint').innerText = 'รองรับรูปภาพหรือเอกสาร';
            trigger.classList.remove('has-file');
        }
    });
});
</script>
