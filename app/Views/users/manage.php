<?php
/**
 * @var array $users
 * @var array $roles
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary-custom fw-bold m-0">
        <i class="fa-solid fa-users-gear text-warning me-2" style="color: var(--accent-gold) !important;"></i>จัดการผู้ใช้ในระบบ
    </h4>
</div>

<div class="card-custom p-4 border shadow-sm" style="background: var(--surface); border-color: var(--border);">
    <?php if (empty($users)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-folder-open fs-2 mb-2"></i>
            <p class="m-0">ไม่พบข้อมูลผู้ใช้ในระบบ</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>รหัสนักศึกษา / Username</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>คณะ / สาขาวิชา</th>
                        <th>สิทธิ์ใช้งาน</th>
                        <th>สถานะบัญชี</th>
                        <th class="text-end" style="min-width: 250px;">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <?php 
                            $isSelf = ((int)$u['id'] === (int)$_SESSION['user_id']);
                            $statusTone = ($u['status'] === 'active') ? 'success' : 'danger';
                            $statusLabel = ($u['status'] === 'active') ? 'ปกติ (Active)' : 'ระงับการใช้งาน';
                        ?>
                        <tr>
                            <td><?= (int)$u['id'] ?></td>
                            <td>
                                <strong class="text-primary-custom"><?= e($u['student_id']) ?></strong>
                                <?php if ($isSelf): ?>
                                    <span class="badge bg-secondary ms-1">คุณ (Self)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?= e($u['name']) ?></div>
                                <div class="text-muted small"><i class="fa-regular fa-envelope me-1"></i><?= e($u['email']) ?></div>
                                <?php if (!empty($u['phone'])): ?>
                                    <div class="text-muted small"><i class="fa-solid fa-phone me-1"></i><?= e($u['phone']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small"><?= e($u['faculty']) ?></div>
                                <div class="text-muted small font-italic"><?= e($u['major']) ?></div>
                            </td>
                            <td>
                                <span class="badge <?= $u['role_key'] === 'admin' ? 'bg-danger text-white' : 'bg-primary text-white' ?>">
                                    <?= e($u['role_name']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $statusTone ?> text-white">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <!-- ปุ่มเปลี่ยนสิทธิ์ -->
                                <button class="btn btn-sm btn-outline-primary me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#roleModal<?= $u['id'] ?>">
                                    <i class="fa-solid fa-user-shield me-1"></i>สิทธิ์
                                </button>

                                <!-- ปุ่มรีเซ็ตรหัสผ่าน -->
                                <button class="btn btn-sm btn-outline-warning text-dark me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#passwordModal<?= $u['id'] ?>">
                                    <i class="fa-solid fa-key me-1"></i>รหัสผ่าน
                                </button>

                                <!-- ปุ่มเปิด/ปิดบัญชี -->
                                <?php if ($isSelf): ?>
                                    <button class="btn btn-sm btn-secondary" disabled title="ไม่สามารถปิดการใช้งานตัวเองได้">
                                        <i class="fa-solid fa-ban me-1"></i>ระงับ
                                    </button>
                                <?php else: ?>
                                    <form method="POST" action="<?= url('users/toggle-status') ?>" class="d-inline-block" onsubmit="return confirm('ยืนยันความต้องการอัปเดตสถานะบัญชีนี้?')">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <input type="hidden" name="status" value="<?= $u['status'] === 'active' ? 'disabled' : 'active' ?>">
                                        
                                        <?php if ($u['status'] === 'active'): ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-ban me-1"></i>ระงับ
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fa-solid fa-circle-check me-1"></i>เปิดใช้
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= renderPagination($currentPage, $totalPages, 'users/manage') ?>
    <?php endif; ?>
</div>

<!-- =========================================================
     Modals Section (Placed outside tables to prevent flickering)
     ========================================================= -->

<?php foreach ($users as $u): ?>
    <?php $isSelf = ((int)$u['id'] === (int)$_SESSION['user_id']); ?>

    <!-- Modal: Update Role -->
    <div class="modal fade" id="roleModal<?= $u['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header--brand">
                    <h5 class="modal-title text-white"><i class="fa-solid fa-user-shield me-2"></i>เปลี่ยนบทบาทสิทธิ์: <?= e($u['name']) ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= url('users/update-role') ?>">
                    <div class="modal-body text-start">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">บทบาทของระบบหลัก</label>
                            <select name="role_id" class="form-select" required <?= $isSelf ? 'disabled' : '' ?>>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= (int)$r['id'] ?>" <?= (int)$u['role_id'] === (int)$r['id'] ? 'selected' : '' ?>>
                                        <?= e($r['role_name']) ?> (<?= e($r['role_key']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($isSelf): ?>
                                <small class="text-danger mt-1 d-block">* คุณไม่สามารถลดตำแหน่งผู้ดูแลระบบของคุณเองได้เพื่อป้องกันการล็อกเอาต์</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn-primary-custom" <?= $isSelf ? 'disabled' : '' ?>>บันทึกสิทธิ์ใหม่</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Reset Password -->
    <div class="modal fade" id="passwordModal<?= $u['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header--gold">
                    <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-key me-2"></i>รีเซ็ตรหัสผ่านใหม่: <?= e($u['name']) ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= url('users/reset-password') ?>" onsubmit="return confirm('ยืนยันรหัสผ่านใหม่ของผู้ใช้รายนี้?')">
                    <div class="modal-body text-start">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">รหัสผ่านใหม่ (New Password)</label>
                            <input type="password" name="new_password" class="form-control" placeholder="อย่างน้อย 6 ตัวขึ้นไป" required minlength="6">
                            <small class="text-muted mt-1 d-block">ระบบจะทำการเข้ารหัสความปลอดภัยด้วย Bcrypt ก่อนบันทึกลงฐานข้อมูล</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn-primary-custom">รีเซ็ตรหัสผ่าน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
