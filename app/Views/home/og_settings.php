<?php
/**
 * @var array $settings
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0">
        <i class="fa-solid fa-share-nodes text-warning me-2"></i>จัดการการแชร์และ Open Graph Meta Tags
    </h4>
</div>

<div class="row g-4">
    <!-- Left Column: Form Settings -->
    <div class="col-lg-6">
        <div class="card-custom p-4 border shadow-sm h-100" style="background: var(--surface); border-color: var(--border);">
            <div class="border-bottom pb-2 mb-4">
                <h5 class="fw-bold text-dark m-0">
                    <i class="fa-solid fa-sliders text-primary me-2"></i>การตั้งค่า Open Graph
                </h5>
                <p class="text-muted small m-0 mt-1">กำหนดข้อมูล meta tags เพื่อให้แสดงผลได้อย่างถูกต้องสวยงามเมื่อนำลิงก์เว็บไซต์ไปแชร์ในสื่อโซเชียลต่าง ๆ</p>
            </div>

            <form action="<?= url('backoffice/settings/og/update') ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="og_image_old" value="<?= e($settings['og_image'] ?? '') ?>">

                <!-- OG Title -->
                <div class="mb-4">
                    <label for="og_title" class="form-label fw-bold text-dark">หัวข้อสำหรับการแชร์ (og:title) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-solid fa-heading"></i></span>
                        <input type="text" name="og_title" id="og_title" class="form-control border-start-0 ps-0 text-dark" value="<?= e($settings['og_title'] ?? '') ?>" placeholder="กรอกหัวข้อการแชร์..." required maxlength="90" oninput="syncPreview()">
                    </div>
                    <div class="form-text small text-muted mt-1">แนะนำความยาวระหว่าง 50-60 ตัวอักษร เพื่อไม่ให้ข้อความโดนตัดท้าย</div>
                </div>

                <!-- OG Description -->
                <div class="mb-4">
                    <label for="og_description" class="form-label fw-bold text-dark">คำอธิบายสำหรับการแชร์ (og:description) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0 align-items-start pt-2"><i class="fa-solid fa-align-left"></i></span>
                        <textarea name="og_description" id="og_description" class="form-control border-start-0 ps-0 text-dark" rows="4" placeholder="กรอกรายละเอียดสำหรับแสดงผลขณะแชร์..." required maxlength="200" oninput="syncPreview()"><?= e($settings['og_description'] ?? '') ?></textarea>
                    </div>
                    <div class="form-text small text-muted mt-1">แนะนำความยาวไม่เกิน 150-160 ตัวอักษร เพื่อสรุปเนื้อหาที่กระชับและดึงดูดใจ</div>
                </div>

                <!-- OG Image -->
                <div class="mb-4">
                    <label for="og_image" class="form-label fw-bold text-dark">รูปภาพสำหรับการแชร์ (og:image)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-regular fa-image"></i></span>
                        <input type="file" name="og_image" id="og_image" class="form-control border-start-0 ps-0 text-dark" accept="image/jpeg,image/png,image/webp" onchange="previewOgImage(event)">
                    </div>
                    <div class="form-text small text-muted mt-1">
                        <i class="fa-solid fa-circle-info me-1 text-primary"></i>แนะนำขนาดรูปภาพ <strong>1200 x 630 พิกเซล</strong> (สัดส่วน 1.91:1) รองรับไฟล์ JPG, PNG และ WEBP ไม่เกิน 2MB
                    </div>
                </div>

                <div class="text-end mt-4 pt-2 border-top">
                    <button type="submit" class="btn btn-gold-custom rounded-pill px-4 py-2 fw-bold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Live Preview Card -->
    <div class="col-lg-6">
        <div class="card-custom p-4 border shadow-sm h-100" style="background: var(--surface-alt); border-color: var(--border);">
            <div class="border-bottom pb-2 mb-4">
                <h5 class="fw-bold text-dark m-0">
                    <i class="fa-solid fa-eye text-primary me-2"></i>ตัวอย่างการแสดงผลบนโซเชียล (Live Preview)
                </h5>
                <p class="text-muted small m-0 mt-1">ตัวอย่างลักษณะที่ลิงก์เว็บไซต์ของท่านจะแสดงบน Facebook, LINE, Twitter หรือ Discord เมื่อถูกแชร์</p>
            </div>

            <!-- Social Card Mockup -->
            <div class="social-share-mockup shadow-sm rounded-3 overflow-hidden border bg-white mx-auto" style="max-width: 480px; transition: all 0.3s ease; border-color: #cbd5e1 !important;">
                <!-- Card Image Box -->
                <div class="mockup-image-container position-relative bg-light d-flex align-items-center justify-content-center" style="height: 250px; overflow: hidden; border-bottom: 1px solid #e2e8f0;">
                    <?php if (!empty($settings['og_image'])): ?>
                        <img id="mockup-img" src="<?= asset($settings['og_image']) ?>" alt="OG Share Image" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <img id="mockup-img" src="" alt="OG Share Image" class="d-none" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div id="mockup-placeholder" class="text-center text-muted p-4 <?= !empty($settings['og_image']) ? 'd-none' : '' ?>">
                        <i class="fa-regular fa-image fs-1 opacity-40 mb-2"></i>
                        <p class="small m-0">อัปโหลดรูปภาพเพื่อดูตัวอย่าง</p>
                    </div>
                </div>

                <!-- Card Content Box -->
                <div class="p-3 bg-light" style="background-color: #f8fafc !important;">
                    <div class="text-uppercase font-monospace text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <?= parse_url(url(), PHP_URL_HOST) ?: 'mcru-clubs.com' ?>
                    </div>
                    <h6 id="mockup-title" class="fw-bold text-dark mb-1 text-truncate-2" style="font-size: 0.95rem; line-height: 1.35; min-height: 2.6em;">
                        <?= e($settings['og_title'] ?? 'หัวข้อการแชร์ตัวอย่าง...') ?>
                    </h6>
                    <p id="mockup-desc" class="text-muted small mb-0 text-truncate-2" style="font-size: 0.8rem; line-height: 1.4; min-height: 2.8em;">
                        <?= e($settings['og_description'] ?? 'รายละเอียดข้อมูลการแชร์ตัวอย่าง...') ?>
                    </p>
                </div>
            </div>
            
            <div class="alert alert-info mt-4 small border-0 mb-0">
                <i class="fa-solid fa-lightbulb me-2 text-warning fs-5"></i>
                <strong>เคล็ดลับ:</strong> หลังจากทำการแก้ไขการตั้งค่าแล้ว หากนำลิงก์ไปแชร์ใน Facebook แล้วยังคงแสดงข้อมูลเก่า คุณสามารถใช้เครื่องมือล้างแคชการแชร์ <a href="https://developers.facebook.com/tools/debug/" target="_blank" class="fw-bold text-primary-custom text-decoration-underline">Facebook Sharing Debugger</a> เพื่อบังคับให้ดึงข้อมูลใหม่ล่าสุดได้ทันที
            </div>
        </div>
    </div>
</div>

<style>
/* CSS text truncations */
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
.btn-gold-custom:hover {
    box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3) !important;
}
</style>

<script>
function syncPreview() {
    const titleInput = document.getElementById('og_title');
    const descInput = document.getElementById('og_description');
    
    const mockupTitle = document.getElementById('mockup-title');
    const mockupDesc = document.getElementById('mockup-desc');
    
    mockupTitle.innerText = titleInput.value.trim() !== '' ? titleInput.value.trim() : 'หัวข้อการแชร์ตัวอย่าง...';
    mockupDesc.innerText = descInput.value.trim() !== '' ? descInput.value.trim() : 'รายละเอียดข้อมูลการแชร์ตัวอย่าง...';
}

function previewOgImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                title: 'ไฟล์ไม่ถูกต้อง',
                text: 'กรุณาอัปโหลดรูปภาพประเภท JPG, PNG หรือ WEBP เท่านั้น',
                icon: 'error',
                confirmButtonColor: '#0b2c5c',
                confirmButtonText: 'ตกลง'
            });
            input.value = '';
            return;
        }
        
        // Validate size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                title: 'ไฟล์ใหญ่เกินไป',
                text: 'ขนาดรูปภาพสำหรับการแชร์ต้องไม่เกิน 2MB',
                icon: 'error',
                confirmButtonColor: '#0b2c5c',
                confirmButtonText: 'ตกลง'
            });
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('mockup-img');
            const placeholder = document.getElementById('mockup-placeholder');
            
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
            }
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        };
        reader.readAsDataURL(file);
    }
}
</script>
