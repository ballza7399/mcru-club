<?php
/**
 * @var array $announcements
 * @var array $clubsList
 * @var string $role
 * @var int|null $clubId
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-bullhorn text-warning me-2"></i>จัดการข่าวประชาสัมพันธ์</h4>
    <button class="btn-gold-custom" data-bs-toggle="modal" data-bs-target="#addNewsModal">
        <i class="fa-solid fa-plus me-1"></i> เขียนข่าวประชาสัมพันธ์ใหม่
    </button>
</div>

<div class="card-custom p-4">
    <?php if (empty($announcements)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-folder-open fs-2 mb-2"></i>
            <p class="m-0">ยังไม่มีข่าวสารที่คุณเขียนไว้ในระบบ</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>รูปปก</th>
                        <th>หัวข้อข่าว</th>
                        <th>ประเภท/ชมรม</th>
                        <th>ผู้เขียนข่าว</th>
                        <th>วันที่ประกาศ</th>
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
                            <td class="fw-bold text-primary-custom" style="max-width: 250px;"><?= e($news['title']) ?></td>
                            <td>
                                <span class="badge <?= $news['club_id'] ? 'bg-info' : 'bg-primary' ?> text-white">
                                    <?= $news['club_id'] ? e($news['club_name']) : 'ข่าวสารกลาง' ?>
                                </span>
                            </td>
                            <td><?= e($news['author_name']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($news['created_at'])) ?> น.</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editNewsModal<?= $news['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square me-1"></i>แก้ไข
                                </button>
                                <a href="<?= url('announcements/delete/' . (int) $news['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('ยืนยันที่จะลบข่าวประชาสัมพันธ์นี้?')">
                                    <i class="fa-solid fa-trash me-1"></i>ลบ
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- =========================================================
     Modals Section (Placed outside tables to prevent flickering)
     ========================================================= -->

<!-- Modal: Add News -->
<div class="modal fade" id="addNewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header--gold">
                <h5 class="modal-title fw-bold text-white">เขียนข่าวประชาสัมพันธ์ใหม่</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('announcements/store') ?>" enctype="multipart/form-data">
                <div class="modal-body text-start">
                    <?php if ($role === 'admin'): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ลงประกาศในนาม</label>
                            <select name="club_id" class="form-select">
                                <option value="">-- ส่วนกลาง (มหาวิทยาลัย) --</option>
                                <?php foreach ($clubsList as $c): ?>
                                    <option value="<?= (int)$c['id'] ?>"><?= e($c['club_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="club_id" value="<?= $clubId ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">หัวข้อข่าว</label>
                        <input type="text" name="title" class="form-control" placeholder="พิมพ์หัวข้อข่าวเด่นของคุณ..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">เนื้อหาข่าว</label>
                        <textarea name="content" class="form-control ckeditor-replace" rows="8" placeholder="พิมพ์เนื้อหาหรือรายละเอียดการประชาสัมพันธ์อย่างละเอียด..."></textarea>
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
                <form method="POST" action="<?= url('announcements/update') ?>" enctype="multipart/form-data">
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

<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<style>
/* แก้ไขหน้าต่างตั้งค่าลิงก์/ป๊อปอัพของ CKEditor ไม่ให้แสดงผลซ้อนใต้ Bootstrap Modal */
.ck-rounded-corners .ck.ck-balloon-panel, 
.ck.ck-balloon-panel {
    z-index: 10055 !important;
}
/* ปรับขนาดความสูงขั้นต่ำของส่วนแก้ไขข้อความ */
.ck-editor__editable {
    min-height: 250px;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.ckeditor-replace').forEach(textarea => {
        ClassicEditor
            .create(textarea, {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
            })
            .catch(error => {
                console.error(error);
            });
    });
});
</script>
