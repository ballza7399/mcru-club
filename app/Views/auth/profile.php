<?php
/** @var array $user */
/** @var array $facultiesData */
?>
<div class="row g-4 mt-2">
    <!-- Left Column: Profile Card -->
    <div class="col-lg-4">
        <div class="profile-card text-center p-4 border border-1 shadow-sm rounded-4 bg-white" style="transition: all 0.3s ease; border-color: var(--border) !important;">
            <form id="profile-form" method="POST" action="<?= url('profile/update') ?>" enctype="multipart/form-data">
                <!-- Avatar Upload Section -->
                <div class="profile-avatar-uploader mx-auto position-relative mb-4" style="width: 140px; height: 140px;">
                    <div class="avatar-circle overflow-hidden rounded-circle border border-4 border-white shadow-md" style="width: 140px; height: 140px; background: var(--surface-alt); display: flex; align-items: center; justify-content: center; position: relative; transition: all 0.3s ease;">
                        <img id="avatar-preview" src="<?= !empty($user['avatar']) ? asset($user['avatar']) : '' ?>" alt="Avatar" class="<?= !empty($user['avatar']) ? '' : 'd-none' ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <i id="avatar-placeholder" class="fa-solid fa-user text-muted <?= !empty($user['avatar']) ? 'd-none' : '' ?>" style="font-size: 3.5rem;"></i>
                        
                        <label for="avatar-input" class="avatar-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white bg-dark bg-opacity-60" style="opacity: 0; cursor: pointer; transition: opacity 0.25s var(--ease-out); border-radius: 50%;">
                            <i class="fa-solid fa-camera mb-1 fs-4"></i>
                            <span style="font-size: 0.75rem; font-weight: 500;">เปลี่ยนรูปโปรไฟล์</span>
                        </label>
                    </div>
                    <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewAvatar(event)">
                    <div class="avatar-badge position-absolute bottom-0 end-0 bg-gold-custom text-white rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border: 3px solid #fff;">
                        <i class="fa-solid fa-pencil" style="font-size: 0.8rem;"></i>
                    </div>
                </div>

                <h4 class="mb-1 text-dark fw-bold"><?= e($user['name']) ?></h4>
                <div class="badge rounded-pill px-3 py-2 mb-3" style="background-color: var(--info-bg); color: var(--info-ink); font-weight: 600; font-size: 0.8rem;">
                    <?php
                        $roleLabel = ['admin' => 'ผู้ดูแลระบบ (Admin)', 'president' => 'ประธานชมรม', 'student' => 'นักศึกษา'];
                        echo $roleLabel[$_SESSION['role'] ?? ''] ?? 'นักศึกษา';
                    ?>
                </div>

                <hr class="my-4 opacity-50">

                <div class="text-start">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-sm rounded-3 me-3 d-flex align-items-center justify-content-center text-primary-custom" style="width: 36px; height: 36px; background-color: var(--info-bg);">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.7rem;">รหัสนักศึกษา / ชื่อผู้ใช้</small>
                            <span class="fw-bold text-dark"><?= e($user['student_id']) ?></span>
                        </div>
                    </div>

                    <?php if (!empty($user['faculty'])): ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-sm rounded-3 me-3 d-flex align-items-center justify-content-center text-primary-custom" style="width: 36px; height: 36px; background-color: var(--info-bg);">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.7rem;">คณะ</small>
                            <span class="fw-semibold text-dark small"><?= e($user['faculty']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($user['major'])): ?>
                    <div class="d-flex align-items-center">
                        <div class="icon-box-sm rounded-3 me-3 d-flex align-items-center justify-content-center text-primary-custom" style="width: 36px; height: 36px; background-color: var(--info-bg);">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.7rem;">สาขาวิชา</small>
                            <span class="fw-semibold text-dark small"><?= e($user['major']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
        </div>
    </div>

    <!-- Right Column: Edit Profile Form -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-box-lg rounded-4 me-3 d-flex align-items-center justify-content-center text-white" style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary-blue), var(--primary-soft));">
                    <i class="fa-solid fa-user-gear fs-5"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">แก้ไขข้อมูลส่วนตัว</h3>
                    <p class="text-muted mb-0 small">จัดการข้อมูลบัญชีผู้ใช้และรหัสผ่านเพื่อความปลอดภัย</p>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom fw-semibold mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-id-card me-1 text-muted"></i> รหัสนักศึกษา / ไอดีล็อกอิน <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light text-muted border-0 py-2.5 px-3 rounded-3" value="<?= e($user['student_id']) ?>" readonly style="cursor: not-allowed; font-weight: 500;">
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;"><i class="fa-solid fa-lock me-1"></i>รหัสนักศึกษาไม่สามารถเปลี่ยนแปลงได้</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label for="name" class="form-label-custom fw-semibold mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-user me-1 text-muted"></i> ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control border-1 py-2.5 px-3 rounded-3" value="<?= e($user['name']) ?>" required style="border-color: var(--border);">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label for="email" class="form-label-custom fw-semibold mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-envelope me-1 text-muted"></i> อีเมล <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control border-1 py-2.5 px-3 rounded-3" value="<?= e($user['email']) ?>" required style="border-color: var(--border);">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label for="phone" class="form-label-custom fw-semibold mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-phone me-1 text-muted"></i> เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" id="phone" class="form-control border-1 py-2.5 px-3 rounded-3" value="<?= e($user['phone']) ?>" style="border-color: var(--border);">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label for="faculty" class="form-label-custom fw-semibold mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-building-columns me-1 text-muted"></i> คณะ</label>
                        <select name="faculty" id="faculty" class="form-select border-1 py-2.5 px-3 rounded-3" onchange="updateMajors()" style="border-color: var(--border);">
                            <option value="">-- เลือกคณะ --</option>
                            <?php foreach (array_keys($facultiesData) as $f): ?>
                                <option value="<?= e($f) ?>"><?= e($f) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label for="major" class="form-label-custom fw-semibold mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-graduation-cap me-1 text-muted"></i> สาขาวิชา</label>
                        <select name="major" id="major" class="form-select border-1 py-2.5 px-3 rounded-3" disabled style="border-color: var(--border);">
                            <option value="">-- โปรดเลือกคณะก่อน --</option>
                        </select>
                    </div>
                </div>

                <!-- Password Reset Section -->
                <div class="col-12 mt-4">
                    <div class="p-3 rounded-3" style="background-color: var(--surface-alt); border: 1px solid var(--border);">
                        <h5 class="fw-bold mb-2 text-dark" style="font-size: 0.95rem;"><i class="fa-solid fa-shield-halved me-1 text-primary-custom"></i> เปลี่ยนรหัสผ่านใหม่</h5>
                        <p class="text-muted mb-3" style="font-size: 0.8rem;">ปล่อยช่องรหัสผ่านว่างไว้ หากคุณไม่ต้องการเปลี่ยนรหัสผ่านเดิม</p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="password" class="form-label-custom fw-semibold mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-key me-1 text-muted"></i> รหัสผ่านใหม่</label>
                                    <input type="password" name="password" id="password" class="form-control border-1 py-2.5 px-3 rounded-3 bg-white" placeholder="อย่างน้อย 6 ตัวอักษร" style="border-color: var(--border);">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="confirm_password" class="form-label-custom fw-semibold mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-lock me-1 text-muted"></i> ยืนยันรหัสผ่านใหม่</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control border-1 py-2.5 px-3 rounded-3 bg-white" placeholder="ยืนยันรหัสผ่านใหม่อีกครั้ง" style="border-color: var(--border);">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-gold-custom rounded-pill px-4 py-2.5 fw-bold shadow-sm" style="transition: all 0.25s var(--ease-out);">
                        <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกข้อมูลส่วนตัว
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<style>
.profile-avatar-uploader:hover .avatar-circle {
    transform: scale(1.02);
}
.profile-avatar-uploader:hover .avatar-overlay {
    opacity: 1 !important;
}
.btn-gold-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3) !important;
}
.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue) !important;
    box-shadow: 0 0 0 3px rgba(11, 44, 92, 0.1) !important;
}
</style>

<script>
// Dynamic Select Logic
const majorsData = <?= json_encode($facultiesData, JSON_UNESCAPED_UNICODE) ?>;
const currentFaculty = <?= json_encode($user['faculty'] ?? '', JSON_UNESCAPED_UNICODE) ?>;
const currentMajor = <?= json_encode($user['major'] ?? '', JSON_UNESCAPED_UNICODE) ?>;

function updateMajors() {
    const f = document.getElementById('faculty');
    const m = document.getElementById('major');
    const selectedFaculty = f.value;
    
    m.innerHTML = '<option value="">-- เลือกสาขาวิชา --</option>';
    
    if (selectedFaculty && majorsData[selectedFaculty]) {
        m.disabled = false;
        majorsData[selectedFaculty].forEach(v => {
            const o = document.createElement('option');
            o.value = o.text = v;
            if (v === currentMajor) {
                o.selected = true;
            }
            m.appendChild(o);
        });
    } else {
        m.disabled = true;
    }
}

function previewAvatar(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                title: 'ไฟล์ไม่ถูกต้อง',
                text: 'กรุณาอัปโหลดรูปภาพประเภท JPG, PNG, WEBP หรือ GIF เท่านั้น',
                icon: 'error',
                confirmButtonColor: '#0b2c5c',
                confirmButtonText: 'ตกลง'
            });
            input.value = '';
            return;
        }
        
        // Validate size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                title: 'ไฟล์ใหญ่เกินไป',
                text: 'ขนาดรูปภาพต้องไม่เกิน 2MB',
                icon: 'error',
                confirmButtonColor: '#0b2c5c',
                confirmButtonText: 'ตกลง'
            });
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        };
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Populate and set initial faculty/major
    const f = document.getElementById('faculty');
    if (currentFaculty) {
        f.value = currentFaculty;
        updateMajors();
    }
});
</script>
