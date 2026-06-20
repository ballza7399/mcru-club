<?php
/**
 * @var array $members
 * @var array $roles
 * @var array $club
 * @var int $currentPage
 * @var int $totalPages
 * @var int $limit
 */
$clubIdQuery = '?club_id=' . (int)$club['id'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-users text-warning me-2"></i>จัดการสมาชิกชมรม</h4>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <?php if (empty($members)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-users fs-1 mb-3 opacity-25"></i>
            <p class="m-0">ยังไม่มีรายชื่อสมาชิกชมรมนี้ในระบบ</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>รหัสนักศึกษา</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>คณะ / สาขา</th>
                        <th>ตำแหน่งชมรม</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $row): ?>
                        <tr>
                            <td><strong><?= e($row['student_id']) ?></strong></td>
                            <td><?= e($row['name']) ?></td>
                            <td>
                                <small><?= e($row['faculty']) ?></small><br>
                                <small class="text-muted"><?= e($row['major']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-info text-white"><?= e($row['role_name'] ?: 'สมาชิกทั่วไป') ?></span>
                            </td>
                            <td class="text-end">
                                <!-- Edit role button -->
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#roleModal<?= $row['id'] ?>">
                                    <i class="fa-solid fa-user-shield me-1"></i>แต่งตั้ง
                                </button>
                                
                                <!-- Kick member button -->
                                <?php if ($row['role_key'] !== 'president'): ?>
                                    <a href="<?= url('cluboffice/members/remove/' . (int)$row['id']) . $clubIdQuery ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       data-confirm="ยืนยันที่จะคัดนักศึกษา <?= e($row['name']) ?> ออกจากชมรม?">
                                        <i class="fa-solid fa-ban me-1"></i>คัดออก
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled title="ไม่สามารถคัดประธานชมรมออกได้">
                                        <i class="fa-solid fa-ban me-1"></i>คัดออก
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'cluboffice/members', $limit) ?>
    <?php endif; ?>
</div>

<!-- Modal แต่งตั้งบทบาทตำแหน่ง -->
<?php foreach ($members as $row): ?>
<div class="modal fade" id="roleModal<?= $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header--brand">
                <h5 class="modal-title">แต่งตั้งตำแหน่งสมาชิก: <?= e($row['name']) ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('cluboffice/members/assign-role') . $clubIdQuery ?>">
                <div class="modal-body">
                    <input type="hidden" name="club_id" value="<?= (int)$club['id'] ?>">
                    <input type="hidden" name="user_id" value="<?= (int)$row['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">เลือกตำแหน่ง</label>
                        <select name="role_id" class="form-select" required>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= (int)$r['id'] ?>" <?= (int)$row['role_id'] === (int)$r['id'] ? 'selected' : '' ?>>
                                    <?= e($r['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">* สิทธิ์การเข้าถึงเมนูและการอนุมัติต่างๆ จะถูกจำกัดตามสิทธิ์ของตำแหน่งที่แต่งตั้ง</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-primary-custom">บันทึกตำแหน่ง</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
