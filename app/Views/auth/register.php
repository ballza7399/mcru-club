<?php
/** @var string|null $error */
$majorsData = [
    'คณะวิทยาศาสตร์และเทคโนโลยี' => ['เทคโนโลยีสารสนเทศ (IT)', 'วิทยาการคอมพิวเตอร์ (CS)', 'สาธารณสุขศาสตร์', 'วิทยาศาสตร์การกีฬา', 'คณิตศาสตร์', 'วิทยาศาสตร์สิ่งแวดล้อม'],
    'คณะวิทยาการจัดการ' => ['คอมพิวเตอร์ธุรกิจ', 'การจัดการ', 'การบัญชี', 'การตลาด', 'การบริหารทรัพยากรมนุษย์', 'นิเทศศาสตร์'],
    'คณะครุศาสตร์' => ['การศึกษาปฐมวัย', 'ภาษาไทย', 'ภาษาอังกฤษ', 'วิทยาศาสตร์ทั่วไป', 'คณิตศาสตร์', 'พลศึกษา'],
    'คณะมนุษยศาสตร์และสังคมศาสตร์' => ['รัฐประศาสนศาสตร์', 'การพัฒนาชุมชน', 'นิติศาสตร์', 'ภาษาอังกฤษธุรกิจ', 'ศิลปกรรม'],
    'คณะเทคโนโลยีอุตสาหกรรม' => ['เทคโนโลยีวิศวกรรมไฟฟ้า', 'เทคโนโลยีวิศวกรรมเครื่องกล', 'เทคโนโลยีอุตสาหการ', 'การจัดการโลจิสติกส์'],
    'วิทยาลัยมวยไทยศึกษาและการแพทย์แผนไทย' => ['มวยไทยศึกษา', 'การแพทย์แผนไทย'],
];
?>
<style>
.register-card{background:#fff;width:100%;max-width:550px;border-radius:20px;box-shadow:0 20px 50px rgba(0,0,0,.3);overflow:hidden;margin:20px;}
.register-card-header{background:linear-gradient(135deg,var(--mru-gold),#d98a12);color:#fff;text-align:center;padding:35px 20px 25px;position:relative;}
.register-card-header h2{font-weight:700;margin-bottom:5px;font-size:1.8rem;color:var(--mru-blue);}
.register-card-header::after{content:'';position:absolute;top:-30px;left:-30px;width:100px;height:100px;background:rgba(255,255,255,.15);border-radius:50%;}
.register-card-body{padding:35px 40px;}
.input-group-custom{position:relative;margin-bottom:15px;}
.input-group-custom i.icon-left{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#a0aec0;z-index:10;}
.form-control-custom{height:50px;border-radius:10px;padding-left:45px;padding-right:15px;border:1px solid #e2e8f0;background:#f8fafc;font-size:.95rem;transition:all .3s;width:100%;}
select.form-control-custom{appearance:none;background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 15px center;background-size:16px 12px;}
.form-control-custom:focus{border-color:var(--mru-gold);background:#fff;box-shadow:0 0 0 3px rgba(249,168,38,.15);outline:none;}
.btn-register-submit{background:var(--mru-blue);color:#fff;height:50px;border-radius:10px;font-weight:600;font-size:1rem;border:none;transition:all .3s;margin-top:15px;width:100%;}
.btn-register-submit:hover{background:var(--mru-blue-hover);transform:translateY(-2px);color:#fff;}
</style>

<div class="register-card">
    <div class="register-card-header">
        <h2>สมัครสมาชิกใหม่</h2>
        <p style="color:#fff;font-size:.95rem;">ระบบบริหารจัดการชมรม ม.ราชภัฏหมู่บ้านจอมบึง</p>
    </div>
    <div class="register-card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger p-2 mb-4 rounded-3 fw-bold small"><i class="fa-solid fa-circle-exclamation me-2"></i><?= e($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= url('auth/register') ?>">
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group-custom">
                        <input type="text" name="student_id" class="form-control-custom" placeholder="รหัสนักศึกษา 9 หลัก" required minlength="9" maxlength="9" pattern="[0-9]{9}">
                        <i class="fa-solid fa-id-card icon-left"></i>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group-custom">
                        <input type="password" name="password" class="form-control-custom" placeholder="รหัสผ่าน" required>
                        <i class="fa-solid fa-lock icon-left"></i>
                    </div>
                </div>
            </div>
            <div class="input-group-custom">
                <input type="text" name="name" class="form-control-custom" placeholder="ชื่อ-นามสกุล" required>
                <i class="fa-solid fa-user icon-left"></i>
            </div>
            <div class="input-group-custom">
                <input type="email" name="email" class="form-control-custom" placeholder="อีเมล (Email)" required>
                <i class="fa-solid fa-envelope icon-left"></i>
            </div>
            <div class="input-group-custom">
                <select name="faculty" id="faculty" class="form-control-custom" onchange="updateMajors()" required>
                    <option value="">-- เลือกคณะ --</option>
                    <?php foreach (array_keys($majorsData) as $f): ?>
                        <option value="<?= e($f) ?>"><?= e($f) ?></option>
                    <?php endforeach; ?>
                </select>
                <i class="fa-solid fa-building-columns icon-left"></i>
            </div>
            <div class="input-group-custom">
                <select name="major" id="major" class="form-control-custom" required disabled>
                    <option value="">-- โปรดเลือกคณะก่อน --</option>
                </select>
                <i class="fa-solid fa-graduation-cap icon-left"></i>
            </div>
            <div class="input-group-custom">
                <input type="text" name="phone" class="form-control-custom" placeholder="เบอร์โทรศัพท์" required>
                <i class="fa-solid fa-phone icon-left"></i>
            </div>
            <button type="submit" class="btn btn-register-submit">ลงทะเบียนเข้าใช้งาน <i class="fa-solid fa-check ms-1"></i></button>
        </form>
        <div style="text-align:center;margin-top:20px;font-size:.95rem;">
            มีบัญชีนักศึกษาอยู่แล้ว? <a href="<?= url('auth/login') ?>" style="color:var(--mru-blue);font-weight:600;text-decoration:none;">กลับไปเข้าสู่ระบบ</a>
        </div>
    </div>
</div>

<script>
const majorsData = <?= json_encode($majorsData, JSON_UNESCAPED_UNICODE) ?>;
function updateMajors() {
    const f = document.getElementById('faculty');
    const m = document.getElementById('major');
    m.innerHTML = '<option value="">-- เลือกสาขาวิชา --</option>';
    if (f.value && majorsData[f.value]) {
        m.disabled = false;
        majorsData[f.value].forEach(v => {
            const o = document.createElement('option');
            o.value = o.text = v;
            m.appendChild(o);
        });
    } else { m.disabled = true; }
}
</script>
