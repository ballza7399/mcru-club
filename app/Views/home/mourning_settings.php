<?php
/**
 * @var array $settings
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0">
        <i class="fa-solid fa-ribbon text-warning me-2"></i>จัดการหน้าจอแสดงความไว้อาลัย (Mourning Settings)
    </h4>
</div>

<div class="row g-4">
    <!-- Left Column: Form Settings -->
    <div class="col-lg-6">
        <div class="card-custom p-4 border shadow-sm h-100" style="background: var(--surface); border-color: var(--border);">
            <div class="border-bottom pb-2 mb-4">
                <h5 class="fw-bold text-dark m-0">
                    <i class="fa-solid fa-sliders text-primary me-2"></i>การตั้งค่าการทำงาน
                </h5>
                <p class="text-muted small m-0 mt-1">ตั้งค่าเปิด-ปิด หรือปรับแต่งรายละเอียดของการแสดงหน้าจอก่อนเข้าเว็บไซต์</p>
            </div>

            <form action="<?= url('backoffice/settings/mourning/update') ?>" method="POST">
                <!-- Enabled / Disabled -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark d-block">สถานะการแสดงหน้าจอไว้อาลัย</label>
                    <div class="d-flex gap-4 mt-2">
                        <div class="form-check form-check-inline p-3 border rounded-3 d-flex align-items-center flex-grow-1" style="cursor: pointer; background: rgba(11, 44, 92, 0.02); border-color: var(--border-strong) !important;">
                            <input class="form-check-input ms-0 me-2" type="radio" name="mourning_enabled" id="statusActive" value="1" <?= ($settings['mourning_enabled'] ?? '0') === '1' ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                            <label class="form-check-label text-success fw-bold small mb-0" for="statusActive" style="cursor: pointer;">
                                <i class="fa-solid fa-circle-check me-1"></i> เปิดใช้งานระบบ
                            </label>
                        </div>
                        <div class="form-check form-check-inline p-3 border rounded-3 d-flex align-items-center flex-grow-1" style="cursor: pointer; background: rgba(11, 44, 92, 0.02); border-color: var(--border-strong) !important;">
                            <input class="form-check-input ms-0 me-2" type="radio" name="mourning_enabled" id="statusInactive" value="0" <?= ($settings['mourning_enabled'] ?? '0') === '0' ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                            <label class="form-check-label text-danger fw-bold small mb-0" for="statusInactive" style="cursor: pointer;">
                                <i class="fa-solid fa-circle-xmark me-1"></i> ปิดใช้งานระบบ
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Mourning Image URL -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">ลิงก์ที่อยู่รูปภาพไว้อาลัย (Image URL)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-regular fa-image"></i></span>
                        <input type="url" name="mourning_image_url" id="mourning_image_url" class="form-control border-start-0 ps-0 text-dark" value="<?= e($settings['mourning_image_url'] ?? '') ?>" placeholder="กรอก URL รูปภาพไว้อาลัย..." required>
                    </div>
                    <div class="form-text small text-muted mt-1"><i class="fa-solid fa-circle-info me-1 text-primary"></i>ลิงก์รูปภาพต้องขึ้นต้นด้วย <code>http://</code> หรือ <code>https://</code> และเข้าถึงได้ทางสาธารณะ</div>
                </div>

                <!-- Mourning Duration -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">ระยะเวลาการแสดงผล (หน่วยเป็นวินาที)</label>
                    <div class="input-group" style="max-width: 180px;">
                        <input type="number" name="mourning_duration" class="form-control text-center" min="1" max="60" value="<?= e($settings['mourning_duration'] ?? '3') ?>" required>
                        <span class="input-group-text bg-white">วินาที</span>
                    </div>
                    <div class="form-text small text-muted mt-1">เวลาที่จะบังคับให้นับถอยหลังก่อนจะจางหายไปโดยอัตโนมัติ (ปกติแนะนำที่ 3 - 5 วินาที)</div>
                </div>

                <!-- Twinkling Stars Effect -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark d-block">อนิเมชั่นดวงดาวระยิบระยับรอบหน้าจอ</label>
                    <div class="d-flex gap-4 mt-2">
                        <div class="form-check form-check-inline p-3 border rounded-3 d-flex align-items-center flex-grow-1" style="cursor: pointer; background: rgba(11, 44, 92, 0.02); border-color: var(--border-strong) !important;">
                            <input class="form-check-input ms-0 me-2" type="radio" name="mourning_stars_enabled" id="starsActive" value="1" <?= ($settings['mourning_stars_enabled'] ?? '1') === '1' ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                            <label class="form-check-label text-success fw-bold small mb-0" for="starsActive" style="cursor: pointer;">
                                <i class="fa-solid fa-star me-1"></i> เปิดเอฟเฟกต์ดวงดาว
                            </label>
                        </div>
                        <div class="form-check form-check-inline p-3 border rounded-3 d-flex align-items-center flex-grow-1" style="cursor: pointer; background: rgba(11, 44, 92, 0.02); border-color: var(--border-strong) !important;">
                            <input class="form-check-input ms-0 me-2" type="radio" name="mourning_stars_enabled" id="starsInactive" value="0" <?= ($settings['mourning_stars_enabled'] ?? '1') === '0' ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                            <label class="form-check-label text-danger fw-bold small mb-0" for="starsInactive" style="cursor: pointer;">
                                <i class="fa-solid fa-star-slash me-1"></i> ปิดเอฟเฟกต์ดวงดาว
                            </label>
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

    <!-- Right Column: Live Preview -->
    <div class="col-lg-6">
        <div class="card-custom p-4 border shadow-sm h-100" style="background: var(--surface); border-color: var(--border);">
            <div class="border-bottom pb-2 mb-4">
                <h5 class="fw-bold text-dark m-0">
                    <i class="fa-solid fa-eye text-success me-2"></i>ตัวอย่างรูปภาพที่แสดง (Live Preview)
                </h5>
                <p class="text-muted small m-0 mt-1">รูปภาพจะถูกจัดวางอย่างสมดุลบนพื้นหลังสีดำสนิทเพื่อร่วมลงนามความอาลัยแบบสุภาพสูงสุด</p>
            </div>

            <div class="preview-area-container p-3 rounded-4 border bg-dark d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 380px; position: relative;">
                <img id="mourningPreview" src="<?= e($settings['mourning_image_url'] ?? '') ?>" class="img-fluid rounded-3 shadow" style="max-height: 350px; object-fit: contain; z-index: 10;" alt="ตัวอย่างรูปภาพ">
                <div class="preview-backdrop" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; background: rgba(0, 0, 0, 0.4); pointer-events: none; z-index: 1;"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const urlInput = document.getElementById('mourning_image_url');
    const previewImg = document.getElementById('mourningPreview');
    
    if (urlInput && previewImg) {
        urlInput.addEventListener('input', () => {
            const val = urlInput.value.trim();
            if (val.startsWith('http://') || val.startsWith('https://')) {
                previewImg.src = val;
            }
        });
    }
});
</script>
