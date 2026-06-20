<?php
/**
 * @var array $club
 * @var array $members
 * @var array $roles
 * @var array $allClubsList
 * @var int $currentClubId
 */
$role = $_SESSION['role'];
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="text-primary-custom fw-bold m-0"><i class="fa-solid fa-users-gear me-2"></i>จัดการสมาชิกและตำแหน่งในชมรม</h4>
        <p class="text-muted m-0">ชมรม: <strong class="text-primary"><?= e($club['club_name']) ?></strong></p>
    </div>
    
    <?php if ($role === 'admin' && !empty($allClubsList)): ?>
        <div class="d-flex align-items-center gap-2">
            <label class="form-label m-0 fw-bold text-nowrap">เลือกชมรม:</label>
            <select class="form-select shadow-sm" onchange="window.location.href='<?= url('clubs/members?club_id=') ?>' + this.value">
                <?php foreach ($allClubsList as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= $currentClubId === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['club_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
</div>

<div class="card-custom p-4">
    <?php if (empty($members)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-users-slash fs-2 mb-2"></i>
            <p class="m-0">ยังไม่มีสมาชิกที่ผ่านการอนุมัติในชมรมนี้</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>รหัสนักศึกษา</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>คณะ / สาขาวิชา</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>ตำแหน่งชมรม</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $mem): ?>
                        <tr>
                            <td class="fw-bold"><?= e($mem['student_id']) ?></td>
                            <td><?= e($mem['name']) ?></td>
                            <td>
                                <small class="text-muted d-block"><?= e($mem['faculty']) ?></small>
                                <small class="text-muted"><?= e($mem['major']) ?></small>
                            </td>
                            <td><?= e($mem['phone']) ?></td>
                            <td>
                                <?php if ($mem['role_key'] === 'president'): ?>
                                    <span class="badge bg-warning text-dark px-3 py-2"><i class="fa-solid fa-crown me-1"></i><?= e($mem['role_name']) ?></span>
                                <?php elseif ($mem['role_key'] === 'officer'): ?>
                                    <span class="badge bg-info text-white px-3 py-2"><i class="fa-solid fa-user-shield me-1"></i><?= e($mem['role_name']) ?></span>
                                <?php elseif ($mem['role_id']): ?>
                                    <span class="badge bg-secondary text-white px-3 py-2"><i class="fa-solid fa-user-tie me-1"></i><?= e($mem['role_name']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted px-3 py-2 border">สมาชิกทั่วไป</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <!-- Assign Role Button -->
                                <button class="btn btn-sm btn-outline-primary me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#assignRoleModal<?= $mem['user_id'] ?>">
                                    <i class="fa-solid fa-user-tag me-1"></i> มอบหมายตำแหน่ง
                                </button>
                                
                                <!-- Remove Button -->
                                <?php if ($mem['role_key'] !== 'president' || $role === 'admin'): ?>
                                    <a href="<?= url('clubs/members/remove/' . $currentClubId . '/' . $mem['user_id']) ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('ยืนยันที่จะคัดนักศึกษาออกจากชมรม?')">
                                        <i class="fa-solid fa-user-minus me-1"></i> คัดออก
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'clubs/members') ?>
    <?php endif; ?>
</div>

<!-- =========================================================
     Modals Section (Placed outside tables to prevent flickering)
     ========================================================= -->

<?php if (!empty($members)): ?>
    <?php foreach ($members as $mem): ?>
        <!-- Modal: Assign Role -->
        <div class="modal fade" id="assignRoleModal<?= $mem['user_id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header--brand">
                        <h5 class="modal-title text-white">มอบหมายตำแหน่งในชมรม</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="<?= url('clubs/members/assign-role') ?>">
                        <div class="modal-body">
                            <input type="hidden" name="club_id" value="<?= $currentClubId ?>">
                            <input type="hidden" name="user_id" value="<?= $mem['user_id'] ?>">
                            
                            <div class="mb-3 text-start">
                                <p>เลือกตำแหน่งให้กับ: <strong><?= e($mem['name']) ?></strong> (รหัส: <?= e($mem['student_id']) ?>)</p>
                            </div>

                            <div class="mb-3 text-start">
                                <label class="form-label fw-bold">ตำแหน่งหน้าที่</label>
                                <select name="role_id" class="form-select">
                                    <option value="">-- สมาชิกทั่วไป --</option>
                                    <?php foreach ($roles as $r): ?>
                                        <?php 
                                            // นักศึกษาทั่วไปไม่สามารถเป็น President ได้ นอกเสียจากว่าแอดมินหรือระบบเป็นคนตั้ง
                                            if ($r['role_key'] === 'president' && $role !== 'admin' && $mem['role_key'] !== 'president') {
                                                continue;
                                            }
                                        ?>
                                        <option value="<?= (int) $r['id'] ?>" <?= $mem['role_id'] === (int) $r['id'] ? 'selected' : '' ?>><?= e($r['role_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted d-block mt-2">
                                    * หากเลือกประธานชมรม ตำแหน่งประธานคนเก่าของชมรมนี้จะถูกเปลี่ยนสถานะเป็นสมาชิกทั่วไปโดยอัตโนมัติ
                                </small>
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
<?php endif; ?>
