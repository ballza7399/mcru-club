<?php
/** @var array $user */
/** @var array $facultiesData */
?>
<div class="row g-4 mt-2">
    <!-- Left Column: Profile Card -->
    <div class="col-lg-4">
        <div class="card-custom text-center pb-4">
            <!-- Academic Modern Blue Banner -->
            <div class="club-banner mb-5"></div>
            
            <form id="profile-form" method="POST" action="<?= url('profile/update') ?>" enctype="multipart/form-data">
                <!-- Avatar Upload Section (Overlapping the banner) -->
                <div class="profile-avatar-uploader mx-auto position-relative mb-4" style="width: 130px; height: 130px; margin-top: -65px;">
                    <div class="avatar-circle overflow-hidden rounded-circle border border-4 border-white shadow" style="width: 130px; height: 130px; background: var(--surface); display: flex; align-items: center; justify-content: center; position: relative; transition: all 0.3s ease;">
                        <img id="avatar-preview" src="<?= !empty($user['avatar']) ? asset($user['avatar']) : '' ?>" alt="Avatar" class="<?= !empty($user['avatar']) ? '' : 'd-none' ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <i id="avatar-placeholder" class="fa-solid fa-user text-muted <?= !empty($user['avatar']) ? 'd-none' : '' ?>" style="font-size: 3.5rem;"></i>
                        
                        <label for="avatar-input" class="avatar-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white bg-dark bg-opacity-60" style="opacity: 0; cursor: pointer; transition: opacity 0.25s var(--ease-out); border-radius: 50%;">
                            <i class="fa-solid fa-camera mb-1 fs-5"></i>
                            <span style="font-size: 0.7rem; font-weight: 500;">อัปโหลดรูป</span>
                        </label>
                    </div>
                    <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewAvatar(event)">
                    <div class="avatar-badge position-absolute bottom-0 end-0 rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 2.5px solid #fff; cursor: pointer; background-color: var(--accent-gold); color: var(--primary-blue);">
                        <i class="fa-solid fa-pencil" style="font-size: 0.75rem;"></i>
                    </div>

                </div>

                <div class="px-4">
                    <h4 class="mb-1 text-dark fw-bold" style="color: var(--primary-blue) !important;"><?= e($user['name']) ?></h4>
                    <div class="badge rounded-pill px-3 py-2 mb-4" style="background-color: var(--info-bg); color: var(--info-ink); font-weight: 600; font-size: 0.8rem;">
                        <?php
                            $roleLabel = [
                                'admin' => 'ผู้ดูแลระบบ (Admin)', 
                                'president' => 'ประธานชมรม', 
                                'student' => 'นักศึกษา',
                                'staff' => 'เจ้าหน้าที่กองพัฒนานักศึกษา'
                            ];
                            echo $roleLabel[$_SESSION['role'] ?? ''] ?? 'นักศึกษา';
                        ?>
                    </div>

                    <div class="text-start p-3 rounded-3" style="background-color: var(--surface-alt);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box-sm rounded-3 me-3 d-flex align-items-center justify-content-center text-primary-custom" style="width: 32px; height: 32px; background-color: var(--surface); border: 1px solid var(--border);">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">รหัสนักศึกษา / ไอดีเข้าใช้งาน</small>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?= e($user['student_id']) ?></span>
                            </div>
                        </div>

                        <?php if (!empty($user['faculty'])): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box-sm rounded-3 me-3 d-flex align-items-center justify-content-center text-primary-custom" style="width: 32px; height: 32px; background-color: var(--surface); border: 1px solid var(--border);">
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
                            <div class="icon-box-sm rounded-3 me-3 d-flex align-items-center justify-content-center text-primary-custom" style="width: 32px; height: 32px; background-color: var(--surface); border: 1px solid var(--border);">
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
    </div>

    <!-- Right Column: Edit Profile Form -->
    <div class="col-lg-8">
        <div class="card-custom p-4 p-md-5">
            <!-- Form Header with Academic touch -->
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="icon-box-lg rounded-3 me-3 d-flex align-items-center justify-content-center text-white" style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary-blue), var(--primary-soft));">
                    <i class="fa-solid fa-user-gear fs-5"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-primary-custom" style="font-size: 1.4rem;">จัดการข้อมูลส่วนตัว</h3>
                    <p class="text-muted mb-0 small">แก้ไขประวัติ คณะ สาขาวิชา และรหัสผ่านเข้าใช้งาน</p>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary-custom mb-1" style="font-size: 0.85rem;">รหัสนักศึกษา / ไอดีผู้ใช้</label>
                        <div class="field mb-0">
                            <input type="text" class="field__control text-muted" value="<?= e($user['student_id']) ?>" readonly style="cursor: not-allowed; background-color: var(--surface-alt);">
                            <i class="fa-solid fa-id-card field__icon text-muted"></i>
                        </div>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;"><i class="fa-solid fa-lock me-1"></i>รหัสนักศึกษาไม่สามารถเปลี่ยนแปลงได้</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary-custom mb-1" style="font-size: 0.85rem;">ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                        <div class="field mb-0">
                            <input type="text" name="name" class="field__control" value="<?= e($user['name']) ?>" placeholder="ชื่อ-นามสกุล" required>
                            <i class="fa-solid fa-user field__icon"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary-custom mb-1" style="font-size: 0.85rem;">อีเมลติดต่อ <span class="text-danger">*</span></label>
                        <div class="field mb-0">
                            <input type="email" name="email" class="field__control" value="<?= e($user['email']) ?>" placeholder="ตัวอย่าง student@mbcr.ac.th" required>
                            <i class="fa-solid fa-envelope field__icon"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary-custom mb-1" style="font-size: 0.85rem;">เบอร์โทรศัพท์</label>
                        <div class="field mb-0">
                            <input type="text" name="phone" class="field__control" value="<?= e($user['phone']) ?>" placeholder="เบอร์โทรศัพท์ติดต่อ">
                            <i class="fa-solid fa-phone field__icon"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary-custom mb-1" style="font-size: 0.85rem;">คณะที่สังกัด</label>
                        <div class="field mb-0">
                            <select name="faculty" id="faculty" class="field__control" onchange="updateMajors()">
                                <option value="">-- เลือกคณะ --</option>
                                <?php foreach (array_keys($facultiesData) as $f): ?>
                                    <option value="<?= e($f) ?>"><?= e($f) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fa-solid fa-building-columns field__icon"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary-custom mb-1" style="font-size: 0.85rem;">สาขาวิชา</label>
                        <div class="field mb-0">
                            <select name="major" id="major" class="field__control" disabled>
                                <option value="">-- โปรดเลือกคณะก่อน --</option>
                            </select>
                            <i class="fa-solid fa-graduation-cap field__icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Password Reset Section -->
                <div class="col-12 mt-3">
                    <div class="p-4 rounded-4" style="background-color: var(--surface-alt); border: 1px solid var(--border);">
                        <h5 class="fw-bold text-primary-custom mb-2" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-shield-halved me-1"></i> เปลี่ยนรหัสผ่านใหม่
                        </h5>
                        <p class="text-muted mb-3" style="font-size: 0.8rem;">ปล่อยช่องรหัสผ่านว่างไว้ หากคุณไม่ต้องการเปลี่ยนรหัสผ่านเดิม</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold text-primary-custom mb-1" style="font-size: 0.85rem;">รหัสผ่านใหม่</label>
                                <div class="field mb-0">
                                    <input type="password" name="password" class="field__control" placeholder="อย่างน้อย 6 ตัวอักษร" style="background: var(--surface);">
                                    <i class="fa-solid fa-key field__icon"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-primary-custom mb-1" style="font-size: 0.85rem;">ยืนยันรหัสผ่านใหม่</label>
                                <div class="field mb-0">
                                    <input type="password" name="confirm_password" class="field__control" placeholder="ยืนยันรหัสผ่านใหม่อีกครั้ง" style="background: var(--surface);">
                                    <i class="fa-solid fa-lock field__icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-gold-custom rounded-pill px-4 py-2.5 fw-bold shadow-sm">
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
.btn-gold-custom {
    font-size: 0.95rem;
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

    // Form submit confirmation
    const form = document.getElementById('profile-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'ยืนยันการเปลี่ยนแปลง?',
                text: 'คุณต้องการบันทึกการแก้ไขข้อมูลส่วนตัวใช่หรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0b2c5c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
});

</script>
