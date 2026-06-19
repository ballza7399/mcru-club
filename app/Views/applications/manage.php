<h4 class="text-primary-custom fw-bold mb-4">จัดการคำขอเข้าชมรม (สมาชิก)</h4>
<div class="card-custom p-4">
    <div class="table-responsive">
        <table class="table align-middle text-nowrap">
            <thead class="table-light">
                <tr>
                    <th>รหัสนักศึกษา</th><th>ชื่อ-นามสกุล</th><th>คณะ/สาขา</th>
                    <th>เบอร์โทร</th><th>ชมรม</th><th>สถานะ</th><th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($apps as $row): ?>
                <tr>
                    <td class="fw-bold text-primary-custom"><?= e($row['student_id']) ?></td>
                    <td><?= e($row['name']) ?></td>
                    <td><?= e($row['faculty']) ?><br><small class="text-muted"><?= e($row['major']) ?></small></td>
                    <td><?= e($row['phone']) ?></td>
                    <td><span class="badge bg-light text-dark border px-2 py-1"><?= e($row['club_name']) ?></span></td>
                    <td>
                        <?php match ($row['status']) {
                            'pending'  => print '<span class="status-badge status-badge--pending"><i class="fa-solid fa-clock"></i> รอตรวจสอบ</span>',
                            'approved' => print '<span class="status-badge status-badge--approved"><i class="fa-solid fa-circle-check"></i> อนุมัติแล้ว</span>',
                            default    => print '<span class="status-badge status-badge--rejected"><i class="fa-solid fa-circle-xmark"></i> ปฏิเสธ</span>',
                        }; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'pending'): ?>
                            <a href="<?= url('applications/approve/' . (int) $row['id']) ?>" class="btn btn-sm btn-success px-3">อนุมัติ</a>
                            <a href="<?= url('applications/reject/' . (int) $row['id']) ?>" class="btn btn-sm btn-outline-danger px-3">ปฏิเสธ</a>
                        <?php else: ?>
                            <span class="text-muted small">- ดำเนินการแล้ว -</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($apps)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">ไม่มีคำขอสมัครในขณะนี้</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
