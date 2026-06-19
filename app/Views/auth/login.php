<?php /** @var string|null $error */ ?>
<div class="auth-box auth-box--login">
    <div class="auth-box__header auth-box__header--blue">
        <h2>MCRU <span>Clubs</span></h2>
        <p>ระบบบริหารจัดการชมรม ม.ราชภัฏหมู่บ้านจอมบึง</p>
    </div>
    <div class="auth-box__body">
        <h5 class="auth-box__title">เข้าสู่ระบบ</h5>
        <?php if ($error): ?>
            <div class="alert alert-danger p-2 mb-3 small fw-bold"><i class="fa-solid fa-circle-exclamation me-2"></i><?= e($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= url('auth/login') ?>">
            <div class="field">
                <input type="text" name="login_id" class="field__control" placeholder="รหัสนักศึกษา หรือ อีเมล" required>
                <i class="fa-solid fa-user field__icon"></i>
            </div>
            <div class="field">
                <input type="password" name="password" id="loginPassword" class="field__control" placeholder="รหัสผ่าน" required>
                <i class="fa-solid fa-lock field__icon"></i>
                <i class="fa-regular fa-eye-slash field__eye" id="togglePassword"></i>
            </div>
            <button type="submit" class="auth-submit">เข้าสู่ระบบ <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i></button>
        </form>
        <div class="auth-divider">หรือ</div>
        <a href="<?= url('auth/register') ?>" class="auth-secondary"><i class="fa-solid fa-user-plus me-2"></i> สร้างบัญชีนักศึกษาใหม่</a>
    </div>
</div>
<script>
const togglePassword = document.getElementById('togglePassword');
const loginPassword = document.getElementById('loginPassword');
togglePassword.addEventListener('click', function () {
    const isPass = loginPassword.getAttribute('type') === 'password';
    loginPassword.setAttribute('type', isPass ? 'text' : 'password');
    this.classList.toggle('fa-eye-slash', !isPass);
    this.classList.toggle('fa-eye', isPass);
});
</script>
