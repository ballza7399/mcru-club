<?php
/**
 * @var array $announcements
 * @var array $club
 * @var int $currentPage
 * @var int $totalPages
 * @var int $limit
 */
$clubIdQuery = '?club_id=' . (int)$club['id'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-bullhorn text-warning me-2"></i>จัดการข่าวสารชมรม</h4>
    <button class="btn-gold-custom" data-bs-toggle="modal" data-bs-target="#addNewsModal">
        <i class="fa-solid fa-plus me-1"></i> เขียนข่าวสารใหม่
    </button>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <?php if (empty($announcements)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-folder-open fs-2 mb-2"></i>
            <p class="m-0">ยังไม่มีข่าวประชาสัมพันธ์จากชมรมนี้ในระบบ</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>รูปปก</th>
                        <th>หัวข้อข่าว</th>
                        <th>ผู้ประกาศ</th>
                        <th>วันที่ลงข่าว</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($announcements as $news): ?>
                        <tr>
                            <td>
                                <?php if (!empty($news['thumbnail']) && assetExists($news['thumbnail'])): ?>
                                    <img src="<?= asset($news['thumbnail']) ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: var(--radius-sm);" alt="">
                                <?php else: ?>
                                    <div style="width: 60px; height: 40px; background: var(--surface-alt); border-radius: var(--radius-sm);" class="d-flex align-items-center justify-content-center text-muted"><i class="fa-regular fa-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-primary-custom" style="max-width: 300px;"><?= e($news['title']) ?></td>
                            <td><?= e($news['author_name']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($news['created_at'])) ?> น.</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editNewsModal<?= $news['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square me-1"></i>แก้ไข
                                </button>
                                <a href="<?= url('cluboffice/announcements/delete/' . (int)$news['id']) . $clubIdQuery ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   data-confirm="ยืนยันที่จะลบข่าวประชาสัมพันธ์ชมรมนี้?">
                                    <i class="fa-solid fa-trash me-1"></i>ลบ
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'cluboffice/announcements', $limit) ?>
    <?php endif; ?>
</div>

<!-- Modal: Add News -->
<div class="modal fade" id="addNewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white">เขียนข่าวสารประชาสัมพันธ์ใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('cluboffice/announcements/store') . $clubIdQuery ?>" enctype="multipart/form-data">
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">หัวข้อข่าว</label>
                        <input type="text" name="title" class="form-control" placeholder="พิมพ์หัวข้อข่าวชมรม..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">เนื้อหาข่าว</label>
                        <textarea name="content" class="form-control ckeditor-replace" rows="8" placeholder="พิมพ์เนื้อหารายละเอียดข่าว..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">อัปโหลดภาพปกข่าว</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">ลงประกาศข่าว</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals: Edit News -->
<?php foreach ($announcements as $news): ?>
    <div class="modal fade" id="editNewsModal<?= $news['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header--brand">
                    <h5 class="modal-title text-white">แก้ไขข่าวประชาสัมพันธ์</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= url('cluboffice/announcements/update') . $clubIdQuery ?>" enctype="multipart/form-data">
                    <div class="modal-body text-start">
                        <input type="hidden" name="id" value="<?= $news['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">หัวข้อข่าว</label>
                            <input type="text" name="title" class="form-control" value="<?= e($news['title']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">เนื้อหาข่าว</label>
                            <textarea name="content" class="form-control ckeditor-replace" rows="8"><?= e($news['content']) ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">เปลี่ยนภาพปกใหม่ (ถ้าต้องการ)</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn-primary-custom">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<style>
.ck-rounded-corners .ck.ck-balloon-panel, 
.ck.ck-balloon-panel {
    z-index: 10055 !important;
}
.ck-editor__editable {
    min-height: 250px;
}
</style>
