<?php
/**
 * @var array $users
 * @var array $roles
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="text-primary-custom fw-bold m-0">
            <i class="fa-solid fa-users-gear text-warning me-2" style="color: var(--accent-gold) !important;"></i>จัดการผู้ใช้ในระบบ
        </h4>
        <p class="text-muted small m-0 mt-1">จัดการข้อมูลบัญชีผู้ใช้งานระบบ บทบาทสิทธิ์ และความปลอดภัย</p>
    </div>
    <button class="btn btn-gold-custom rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fa-solid fa-user-plus me-2"></i>เพิ่มผู้ใช้ใหม่
    </button>
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
                                    <form method="POST" action="<?= url('backoffice/users/toggle-status') ?>" class="d-inline-block" data-confirm="ยืนยันความต้องการอัปเดตสถานะบัญชีนี้?">
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
        <?= renderPagination($currentPage, $totalPages, 'backoffice/users', $limit) ?>
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
                <form method="POST" action="<?= url('backoffice/users/update-role') ?>">
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
                <form method="POST" action="<?= url('backoffice/users/reset-password') ?>" data-confirm="ยืนยันรหัสผ่านใหม่ของผู้ใช้รายนี้?">
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

<!-- Modal: Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg);">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-soft) 100%); border-radius: var(--radius-lg) var(--radius-lg) 0 0; border-bottom: none;">
                <h5 class="modal-title fw-bold" id="addUserModalLabel">
                    <i class="fa-solid fa-user-plus me-2 text-warning" style="color: var(--accent-gold) !important;"></i>เพิ่มผู้ใช้งานใหม่
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= url('backoffice/users/store') ?>">
                <div class="modal-body p-4 text-start">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-1">บทบาทสิทธิ์การใช้งาน <span class="text-danger">*</span></label>
                        <select name="role_id" id="add_role_id" class="form-select" onchange="toggleFacultyMajorFields()" required>
                            <option value="">-- เลือกบทบาท --</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= (int)$r['id'] ?>" data-key="<?= e($r['role_key']) ?>">
                                    <?= e($r['role_name']) ?> (<?= e($r['role_key']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-1">รหัสผู้ใช้งาน / รหัสนักศึกษา <span class="text-danger">*</span></label>
                        <div class="field">
                            <input type="text" name="student_id" class="form-control field__control" placeholder="เช่น 660001 (ใช้เป็น Username ในการเข้าสู่ระบบ)" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-1">รหัสผ่านเริ่มต้น <span class="text-danger">*</span></label>
                        <div class="field">
                            <input type="password" name="password" class="form-control field__control" placeholder="อย่างน้อย 6 ตัวอักษร" required minlength="6">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-1">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                        <div class="field">
                            <input type="text" name="name" class="form-control field__control" placeholder="ชื่อ และ นามสกุล" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-1">อีเมล</label>
                        <div class="field">
                            <input type="email" name="email" class="form-control field__control" placeholder="example@domain.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-1">เบอร์โทรศัพท์</label>
                        <div class="field">
                            <input type="text" name="phone" class="form-control field__control" placeholder="เบอร์โทรศัพท์ติดต่อ">
                        </div>
                    </div>

                    <!-- ส่วนข้อมูลสังกัดคณะ/สาขาวิชา (แสดงเฉพาะเมื่อเลือกบทบาทนักศึกษา) -->
                    <div id="student_only_fields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-1">คณะสังกัด <span class="text-danger">*</span></label>
                            <select name="faculty" id="add_faculty" class="form-select" onchange="updateAddMajors()">
                                <option value="">-- เลือกคณะ --</option>
                                <?php foreach (array_keys($majorsData) as $f): ?>
                                    <option value="<?= e($f) ?>"><?= e($f) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-1">สาขาวิชา <span class="text-danger">*</span></label>
                            <select name="major" id="add_major" class="form-select" disabled>
                                <option value="">-- โปรดเลือกคณะก่อน --</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-gold-custom rounded-pill px-4">
                        <i class="fa-solid fa-circle-check me-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const majorsData = <?= json_encode($majorsData, JSON_UNESCAPED_UNICODE) ?>;

function updateAddMajors() {
    const f = document.getElementById('add_faculty');
    const m = document.getElementById('add_major');
    m.innerHTML = '<option value="">-- เลือกสาขาวิชา --</option>';
    if (f.value && majorsData[f.value]) {
        m.disabled = false;
        majorsData[f.value].forEach(v => {
            const o = document.createElement('option');
            o.value = o.text = v;
            m.appendChild(o);
        });
    } else {
        m.disabled = true;
    }
}

function toggleFacultyMajorFields() {
    const roleSelect = document.getElementById('add_role_id');
    const studentFields = document.getElementById('student_only_fields');
    const selectedOption = roleSelect.options[roleSelect.selectedIndex];
    const isStudent = selectedOption ? selectedOption.getAttribute('data-key') === 'student' : false;
    
    const facultySelect = document.getElementById('add_faculty');
    const majorSelect = document.getElementById('add_major');

    if (isStudent) {
        studentFields.style.display = 'block';
        facultySelect.required = true;
        majorSelect.required = true;
    } else {
        studentFields.style.display = 'none';
        facultySelect.required = false;
        majorSelect.required = false;
        facultySelect.value = '';
        majorSelect.value = '';
        majorSelect.disabled = true;
    }
}
</script>
