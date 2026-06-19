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
<div class="auth-box auth-box--register">
    <div class="auth-box__header auth-box__header--gold">
        <h2>สมัครสมาชิกใหม่</h2>
        <p>ระบบบริหารจัดการชมรม ม.ราชภัฏหมู่บ้านจอมบึง</p>
    </div>
    <div class="auth-box__body">
        <?php if ($error): ?>
            <div class="alert alert-danger p-2 mb-3 small fw-bold"><i class="fa-solid fa-circle-exclamation me-2"></i><?= e($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= url('auth/register') ?>">
            <div class="row">
                <div class="col-sm-6">
                    <div class="field">
                        <input type="text" name="student_id" class="field__control" placeholder="รหัสนักศึกษา 9 หลัก" required minlength="9" maxlength="9" pattern="[0-9]{9}">
                        <i class="fa-solid fa-id-card field__icon"></i>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="field">
                        <input type="password" name="password" class="field__control" placeholder="รหัสผ่าน" required>
                        <i class="fa-solid fa-lock field__icon"></i>
                    </div>
                </div>
            </div>
            <div class="field">
                <input type="text" name="name" class="field__control" placeholder="ชื่อ-นามสกุล" required>
                <i class="fa-solid fa-user field__icon"></i>
            </div>
            <div class="field">
                <input type="email" name="email" class="field__control" placeholder="อีเมล (Email)" required>
                <i class="fa-solid fa-envelope field__icon"></i>
            </div>
            <div class="field">
                <select name="faculty" id="faculty" class="field__control" onchange="updateMajors()" required>
                    <option value="">-- เลือกคณะ --</option>
                    <?php foreach (array_keys($majorsData) as $f): ?>
                        <option value="<?= e($f) ?>"><?= e($f) ?></option>
                    <?php endforeach; ?>
                </select>
                <i class="fa-solid fa-building-columns field__icon"></i>
            </div>
            <div class="field">
                <select name="major" id="major" class="field__control" required disabled>
                    <option value="">-- โปรดเลือกคณะก่อน --</option>
                </select>
                <i class="fa-solid fa-graduation-cap field__icon"></i>
            </div>
            <div class="field">
                <input type="text" name="phone" class="field__control" placeholder="เบอร์โทรศัพท์" required>
                <i class="fa-solid fa-phone field__icon"></i>
            </div>
            <button type="submit" class="auth-submit">ลงทะเบียนเข้าใช้งาน <i class="fa-solid fa-check ms-1"></i></button>
        </form>
        <div class="auth-link">
            มีบัญชีนักศึกษาอยู่แล้ว? <a href="<?= url('auth/login') ?>">กลับไปเข้าสู่ระบบ</a>
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
    } else {
        m.disabled = true;
    }
}
</script>
