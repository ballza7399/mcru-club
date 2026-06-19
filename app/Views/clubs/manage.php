<?php $role = $_SESSION['role']; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0">จัดการข้อมูลชมรม</h4>
    <?php if ($role === 'admin'): ?>
        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#addClubModal">+ สร้างชมรมใหม่</button>
    <?php endif; ?>
</div>

<div class="card-custom p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr><th>โลโก้</th><th>ชื่อชมรม</th><th>ประธาน</th><th>รับสูงสุด</th><th>จัดการ</th></tr>
            </thead>
            <tbody>
                <?php foreach ($clubs as $row): ?>
                <tr>
                    <td>
                        <?php if (assetExists($row['club_logo'])): ?>
                            <img src="<?= asset($row['club_logo']) ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;" alt="">
                        <?php else: ?>
                            <div style="width:40px;height:40px;border-radius:50%;background:#eee;"></div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold text-primary-custom"><?= e($row['club_name']) ?></td>
                    <td><?= $row['pres_name'] ? e($row['pres_name']) . '<br><small class="text-muted">(' . e($row['pres_id']) . ')</small>' : '<span class="badge bg-danger">ยังไม่ระบุ</span>' ?></td>
                    <td><?= (int) $row['max_members'] ?> คน</td>
                    <td>
                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#editModal<?= (int) $row['id'] ?>">แก้ไข</button>
                        <?php if ($role === 'admin'): ?>
                            <a href="<?= url('clubs/delete/' . (int) $row['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบชมรมและข้อมูลการสมัครทั้งหมดของชมรมนี้?')">ลบ</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modals แก้ไข (วางนอกตาราง) -->
<?php foreach ($clubs as $row): ?>
<div class="modal fade" id="editModal<?= (int) $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">แก้ไขข้อมูลชมรม</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('clubs/update') ?>" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="club_id" value="<?= (int) $row['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อชมรม</label>
                        <input type="text" name="club_name" class="form-control" value="<?= e($row['club_name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รายละเอียด</label>
                        <textarea name="description" class="form-control" rows="4" required><?= e($row['description']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">จำกัดจำนวนสมาชิก (คน)</label>
                            <input type="number" name="max_members" class="form-control" value="<?= (int) $row['max_members'] ?>" required>
                        </div>
                        <?php if ($role === 'admin'): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-danger">รหัสนักศึกษาประธานชมรม</label>
                            <input type="text" name="pres_student_id" class="form-control" value="<?= e($row['pres_id']) ?>" placeholder="เว้นว่างถ้าไม่มี">
                            <small class="text-muted">ระบบจะอัปเดตยศให้รหัสนี้เป็นประธานอัตโนมัติ</small>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="row border-top pt-3 mt-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">อัปโหลดโลโก้ใหม่ (ถ้าต้องการเปลี่ยน)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">อัปโหลด QR Code ใหม่</label>
                            <input type="file" name="qr_code" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php if ($role === 'admin'): ?>
<!-- Modal เพิ่มชมรม -->
<div class="modal fade" id="addClubModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold">สร้างชมรมใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('clubs/store') ?>" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อชมรม</label>
                        <input type="text" name="club_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รายละเอียด</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">จำกัดจำนวนสมาชิก (คน)</label>
                        <input type="number" name="max_members" class="form-control" value="50" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">โลโก้ชมรม</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">QR Code</label>
                            <input type="file" name="qr_code" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">สร้างชมรม</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
