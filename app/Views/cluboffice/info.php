<?php
/**
 * @var array $club
 */
$clubIdQuery = '?club_id=' . (int)$club['id'];
$role = $_SESSION['role'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-circle-info text-warning me-2"></i>แก้ไขข้อมูลชมรม</h4>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <form method="POST" action="<?= url('cluboffice/info/update') . $clubIdQuery ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label fw-bold">ชื่อชมรม</label>
            <input type="text" name="club_name" class="form-control" value="<?= e($club['club_name']) ?>" required <?= $role === 'admin' ? '' : 'disabled' ?>>
            <?php if ($role !== 'admin'): ?>
                <small class="text-muted">* ชื่อชมรมจะกำหนดสิทธิ์ในการแก้ไขเฉพาะผู้ดูแลระบบหลักเท่านั้น หากต้องการเปลี่ยนกรุณาติดต่อผู้ดูแลระบบ</small>
            <?php endif; ?>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">รายละเอียด / คำอธิบายชมรม</label>
            <textarea name="description" class="form-control" rows="5" required><?= e($club['description']) ?></textarea>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">จำนวนสมาชิกสูงสุดที่เปิดรับ (คน)</label>
                <input type="number" name="max_members" class="form-control" value="<?= (int)$club['max_members'] ?>" min="5" required>
            </div>
        </div>

        <div class="row border-top pt-3 mt-3">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">เปลี่ยนโลโก้ชมรม</label>
                <?php if (assetExists($club['club_logo'])): ?>
                    <div class="mb-2">
                        <img src="<?= asset($club['club_logo']) ?>" class="img-thumbnail" style="max-height:100px; object-fit:contain;" alt="">
                    </div>
                <?php endif; ?>
                <input type="file" name="logo" class="form-control" accept="image/*">
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">เปลี่ยน QR Code ติดต่อรับสมัคร</label>
                <?php if (assetExists($club['qr_code'])): ?>
                    <div class="mb-2">
                        <img src="<?= asset($club['qr_code']) ?>" class="img-thumbnail" style="max-height:100px; object-fit:contain;" alt="">
                    </div>
                <?php endif; ?>
                <input type="file" name="qr_code" class="form-control" accept="image/*">
            </div>
        </div>

        <div class="border-top pt-3 text-end">
            <button type="submit" class="btn-primary-custom px-4 py-2 border-0 rounded-pill"><i class="fa-solid fa-floppy-disk me-1"></i>บันทึกข้อมูลชมรม</button>
        </div>
    </form>
</div>
