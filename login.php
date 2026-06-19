<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_id = $_POST['login_id']; 
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, role FROM users WHERE (student_id = ? OR email = ?) AND password = ?");
    $stmt->bind_param("sss", $login_id, $login_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];
        header("Location: index.php");
        exit();
    } else {
        $error = "รหัสนักศึกษา/อีเมล หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ระบบจัดการชมรม (MCRU)</title>
    <!-- นำเข้า Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- นำเข้า Google Fonts (Kanit) -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- นำเข้า FontAwesome สำหรับไอคอน -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --mru-blue: #0b2c5c;
            --mru-gold: #f9a826;
            --mru-blue-hover: #071d3d;
        }
        body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            padding: 0;
            /* พื้นหลังแบบมีรูปภาพและสีทับ (เปลี่ยน URL รูปลานกิจกรรม/ตึกเรียนได้เลย) */
            background: linear-gradient(rgba(11, 44, 92, 0.7), rgba(11, 44, 92, 0.9)), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center center / cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* กรอบเข้าสู่ระบบตรงกลาง */
        .login-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            overflow: hidden;
            margin: 20px;
        }
        
        /* ส่วนหัวของกรอบ (โลโก้/แบรนด์) */
        .login-card-header {
            background: linear-gradient(135deg, var(--mru-blue), #1a4980);
            color: white;
            text-align: center;
            padding: 40px 20px 30px;
            position: relative;
        }
        .login-card-header h2 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 2rem;
            letter-spacing: 1px;
        }
        .login-card-header h2 span {
            color: var(--mru-gold);
        }
        .login-card-header p {
            font-size: 0.95rem;
            opacity: 0.85;
            margin: 0;
        }
        /* วงกลมตกแต่งมุมขวาล่างของส่วนหัว */
        .login-card-header::after {
            content: '';
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(249, 168, 38, 0.15);
            border-radius: 50%;
        }

        /* ส่วนฟอร์ม */
        .login-card-body {
            padding: 40px 35px;
        }
        .login-card-body h5 {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 25px;
            text-align: center;
        }
        
        /* ตกแต่ง Input Form */
        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }
        .input-group-custom i.icon-left {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            z-index: 10;
        }
        .form-control-custom {
            height: 50px;
            border-radius: 10px;
            padding-left: 45px; 
            padding-right: 20px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        .form-control-custom:focus {
            border-color: var(--mru-blue);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(11, 44, 92, 0.1);
            outline: none;
        }
        .form-control-custom:focus + i.icon-left,
        .form-control-custom:not(:placeholder-shown) + i.icon-left {
            color: var(--mru-blue);
        }

        /* ตกแต่งไอคอนตา */
        .eye-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            z-index: 10;
            transition: 0.2s;
        }
        .eye-icon:hover {
            color: var(--mru-blue);
        }

        /* ปุ่มเข้าสู่ระบบ */
        .btn-login {
            background-color: var(--mru-blue);
            color: white;
            height: 50px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            transition: all 0.3s;
            margin-top: 5px;
            width: 100%;
        }
        .btn-login:hover {
            background-color: var(--mru-blue-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(11, 44, 92, 0.2);
            color: white;
        }

        /* ตัวแบ่งและสมัครสมาชิก */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0 20px 0;
            color: #a0aec0;
            font-size: 0.85rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }

        .btn-register {
            border: 2px solid #e2e8f0;
            color: #4a5568;
            height: 50px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.95rem;
            background: transparent;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            width: 100%;
        }
        .btn-register:hover {
            border-color: var(--mru-blue);
            color: var(--mru-blue);
            background: #f8fafc;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- ส่วนหัว -->
        <div class="login-card-header">
            <h2>MCRU <span>Clubs</span></h2>
            <p>ระบบบริหารจัดการชมรม ม.ราชภัฏหมู่บ้านจอมบึง</p>
        </div>

        <!-- ส่วนฟอร์ม -->
        <div class="login-card-body">
            <h5>เข้าสู่ระบบ</h5>

            <?php if(isset($error)) echo "<div class='alert alert-danger p-2 mb-4 rounded-3 fw-bold small'><i class='fa-solid fa-circle-exclamation me-2'></i>$error</div>"; ?>

            <form method="POST">
                <div class="input-group-custom">
                    <input type="text" name="login_id" class="form-control-custom" placeholder="รหัสนักศึกษา หรือ อีเมล" required>
                    <i class="fa-solid fa-user icon-left"></i>
                </div>
                
                <div class="input-group-custom">
                    <input type="password" name="password" id="loginPassword" class="form-control-custom" placeholder="รหัสผ่าน" required>
                    <i class="fa-solid fa-lock icon-left"></i>
                    <i class="fa-regular fa-eye-slash eye-icon" id="togglePassword"></i>
                </div>

                <button type="submit" class="btn btn-login">
                    เข้าสู่ระบบ <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                </button>
            </form>

            <div class="divider">หรือ</div>

            <a href="register.php" class="btn-register">
                <i class="fa-solid fa-user-plus me-2"></i> สร้างบัญชีนักศึกษาใหม่
            </a>
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
        
        if(type === 'text') {
            this.style.color = 'var(--mru-blue)';
        } else {
            this.style.color = '#a0aec0';
        }
    });
</script>

</body>
</html>