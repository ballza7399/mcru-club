<?php
/**
 * @var array $images
 * @var array $club
 * @var int $currentPage
 * @var int $totalPages
 * @var int $limit
 */
$clubIdQuery = '?club_id=' . (int)$club['id'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-images text-warning me-2"></i>คลังรูปภาพกิจกรรมของชมรม</h4>
    <button class="btn-gold-custom" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
        <i class="fa-solid fa-cloud-arrow-up me-1"></i> อัปโหลดรูปภาพใหม่
    </button>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <?php if (empty($images)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-image fs-2 mb-2"></i>
            <p class="m-0">ยังไม่มีรูปภาพกิจกรรมของชมรมนี้ในแกลเลอรี</p>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($images as $img): ?>
                <div class="col">
                    <div class="card h-100 border shadow-sm overflow-hidden" style="background: var(--surface); border-color: var(--border);">
                        <div class="position-relative" style="height: 180px;">
                            <img src="<?= asset($img['image_path']) ?>" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="">
                            <div class="position-absolute top-0 end-0 p-2">
                                <a href="<?= url('cluboffice/gallery/delete/' . (int)$img['id']) . $clubIdQuery ?>" 
                                   class="btn btn-danger btn-sm p-1 rounded-circle d-flex align-items-center justify-content-center"
                                   style="width: 28px; height: 28px; opacity: 0.85;"
                                   data-confirm="ยืนยันลบรูปภาพกิจกรรมนี้ถาวร?">
                                    <i class="fa-solid fa-trash fs-6"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <p class="card-text text-truncate small fw-bold m-0" title="<?= e($img['title']) ?>"><?= e($img['title']) ?></p>
                            <small class="text-muted" style="font-size:0.75rem;"><?= date('d/m/Y H:i', strtotime($img['created_at'])) ?> น.</small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'cluboffice/gallery', $limit) ?>
    <?php endif; ?>
</div>

<!-- Modal: Upload Image -->
<div class="modal fade" id="uploadImageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white">อัปโหลดรูปภาพกิจกรรมใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('cluboffice/gallery/store') . $clubIdQuery ?>" enctype="multipart/form-data">
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">คำอธิบายภาพกิจกรรม <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="พิมพ์คำอธิบายรูปภาพ..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">เลือกไฟล์รูปภาพ <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted">แนะนำรูปภาพสัดส่วน 4:3 หรือ 16:9 ขนาดไม่เกิน 5MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">อัปโหลดรูปภาพ</button>
                </div>
            </form>
        </div>
    </div>
</div>
