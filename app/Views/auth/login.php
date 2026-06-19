<?php /** @var string|null $error */ ?>
<style>
.login-card{background:#fff;width:100%;max-width:420px;border-radius:20px;box-shadow:0 20px 50px rgba(0,0,0,.3);overflow:hidden;margin:20px;}
.login-card-header{background:linear-gradient(135deg,var(--mru-blue),#1a4980);color:#fff;text-align:center;padding:40px 20px 30px;position:relative;}
.login-card-header h2{font-weight:700;margin-bottom:5px;font-size:2rem;letter-spacing:1px;}
.login-card-header h2 span{color:var(--mru-gold);}
.login-card-header p{font-size:.95rem;opacity:.85;margin:0;}
.login-card-header::after{content:'';position:absolute;bottom:-30px;right:-30px;width:120px;height:120px;background:rgba(249,168,38,.15);border-radius:50%;}
.login-card-body{padding:40px 35px;}
.login-card-body h5{font-weight:600;margin-bottom:25px;text-align:center;}
.input-group-custom{position:relative;margin-bottom:20px;}
.input-group-custom i.icon-left{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#a0aec0;z-index:10;}
.form-control-custom{height:50px;border-radius:10px;padding-left:45px;padding-right:20px;border:1px solid #e2e8f0;background:#f8fafc;font-size:.95rem;transition:all .3s;width:100%;}
.form-control-custom:focus{border-color:var(--mru-blue);background:#fff;box-shadow:0 0 0 3px rgba(11,44,92,.1);outline:none;}
.eye-icon{position:absolute;right:15px;top:50%;transform:translateY(-50%);cursor:pointer;color:#a0aec0;z-index:10;}
.eye-icon:hover{color:var(--mru-blue);}
.btn-login{background:var(--mru-blue);color:#fff;height:50px;border-radius:10px;font-weight:600;font-size:1rem;border:none;transition:all .3s;margin-top:5px;width:100%;}
.btn-login:hover{background:var(--mru-blue-hover);transform:translateY(-2px);box-shadow:0 8px 15px rgba(11,44,92,.2);color:#fff;}
.divider{display:flex;align-items:center;text-align:center;margin:25px 0 20px;color:#a0aec0;font-size:.85rem;}
.divider::before,.divider::after{content:'';flex:1;border-bottom:1px solid #e2e8f0;}
.divider:not(:empty)::before{margin-right:.5em;}
.divider:not(:empty)::after{margin-left:.5em;}
.btn-register{border:2px solid #e2e8f0;color:#4a5568;height:50px;border-radius:10px;font-weight:500;font-size:.95rem;background:transparent;transition:.3s;display:flex;align-items:center;justify-content:center;text-decoration:none;width:100%;}
.btn-register:hover{border-color:var(--mru-blue);color:var(--mru-blue);background:#f8fafc;}
</style>

<div class="login-card">
    <div class="login-card-header">
        <h2>MCRU <span>Clubs</span></h2>
        <p>ระบบบริหารจัดการชมรม ม.ราชภัฏหมู่บ้านจอมบึง</p>
    </div>
    <div class="login-card-body">
        <h5>เข้าสู่ระบบ</h5>
        <?php if ($error): ?>
            <div class="alert alert-danger p-2 mb-4 rounded-3 fw-bold small"><i class="fa-solid fa-circle-exclamation me-2"></i><?= e($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= url('auth/login') ?>">
            <div class="input-group-custom">
                <input type="text" name="login_id" class="form-control-custom" placeholder="รหัสนักศึกษา หรือ อีเมล" required>
                <i class="fa-solid fa-user icon-left"></i>
            </div>
            <div class="input-group-custom">
                <input type="password" name="password" id="loginPassword" class="form-control-custom" placeholder="รหัสผ่าน" required>
                <i class="fa-solid fa-lock icon-left"></i>
                <i class="fa-regular fa-eye-slash eye-icon" id="togglePassword"></i>
            </div>
            <button type="submit" class="btn btn-login">เข้าสู่ระบบ <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i></button>
        </form>
        <div class="divider">หรือ</div>
        <a href="<?= url('auth/register') ?>" class="btn-register"><i class="fa-solid fa-user-plus me-2"></i> สร้างบัญชีนักศึกษาใหม่</a>
    </div>
</div>

<script>
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('loginPassword');
togglePassword.addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
    this.classList.toggle('fa-eye');
    this.style.color = type === 'text' ? 'var(--mru-blue)' : '#a0aec0';
});
</script>
