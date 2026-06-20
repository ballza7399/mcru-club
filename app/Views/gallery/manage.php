<?php
/**
 * @var array $gallery
 * @var array $clubsList
 * @var string $role
 * @var int|null $clubId
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-regular fa-images text-success me-2"></i>จัดการแกลเลอรีภาพกิจกรรม</h4>
    <button class="btn-gold-custom" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
        <i class="fa-solid fa-cloud-arrow-up me-1"></i> อัปโหลดรูปภาพใหม่
    </button>
</div>

<div class="card-custom p-4">
    <?php if (empty($gallery)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-image fs-2 mb-2 opacity-25"></i>
            <p class="m-0">ยังไม่มีข้อมูลรูปภาพที่คุณอัปโหลดไว้ในแกลเลอรี</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($gallery as $photo): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border shadow-sm rounded overflow-hidden position-relative">
                        <img src="<?= asset($photo['image_path']) ?>" class="card-img-top" style="height: 160px; object-fit: cover;" alt="">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-truncate text-primary-custom m-0 mb-1" title="<?= e($photo['title']) ?>"><?= e($photo['title']) ?></h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge <?= $photo['club_id'] ? 'bg-info' : 'bg-primary' ?> text-white" style="font-size: 0.7rem;">
                                    <?= $photo['club_id'] ? e($photo['club_name']) : 'กิจกรรมสถาบัน' ?>
                                </span>
                                <a href="<?= url('gallery/delete/' . (int) $photo['id']) ?>" 
                                   class="text-danger small" 
                                   onclick="return confirm('คุณต้องการลบรูปภาพนี้ออกจากระบบหรือไม่?')">
                                    <i class="fa-regular fa-trash-can fs-6"></i> ลบรูป
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'gallery/manage', $limit) ?>
    <?php endif; ?>
</div>

<!-- Modal: Upload Photo -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white">อัปโหลดภาพกิจกรรมใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('gallery/store') ?>" enctype="multipart/form-data">
                <div class="modal-body text-start">
                    <?php if ($role === 'admin'): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชมรมที่ลงกิจกรรม</label>
                            <select name="club_id" class="form-select">
                                <option value="">-- ส่วนกลาง (กิจกรรมมหาวิทยาลัย) --</option>
                                <?php foreach ($clubsList as $c): ?>
                                    <option value="<?= (int)$c['id'] ?>"><?= e($c['club_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="club_id" value="<?= $clubId ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อกิจกรรม / คำอธิบายภาพ</label>
                        <input type="text" name="title" class="form-control" placeholder="เช่น กิจกรรมลงเรือนกระจกปลูกผักไฮโดรโปนิกส์" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">เลือกไฟล์รูปภาพ</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" required>
                        <small class="text-muted d-block mt-1">* รองรับไฟล์รูปภาพ JPEG, PNG, WEBP และ GIF (ระบบจะปรับปรุงย่อขนาดความจุให้เหมาะสมอัตโนมัติ)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">อัปโหลดภาพ</button>
                </div>
            </form>
        </div>
    </div>
</div>
