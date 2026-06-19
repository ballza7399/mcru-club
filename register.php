<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ใช้ trim() เพื่อตัดช่องว่างหน้า-หลังที่อาจเผลอพิมพ์ติดมา
    $student_id = trim($_POST['student_id']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $name = trim($_POST['name']);
    $faculty = $_POST['faculty'];
    $major = $_POST['major'];
    $phone = trim($_POST['phone']);

    // เงื่อนไขที่ 2: ตรวจสอบว่ารหัสนักศึกษามีความยาว 9 ตัวพอดีหรือไม่
    if (strlen($student_id) !== 9) {
        $error = "รหัสนักศึกษาต้องมี 9 หลักเท่านั้น";
    } else {
        // เงื่อนไขที่ 1: เช็คว่า รหัสนักศึกษา หรือ อีเมล นี้ซ้ำในระบบหรือไม่
        $check = $conn->prepare("SELECT student_id, email FROM users WHERE student_id = ? OR email = ?");
        $check->bind_param("ss", $student_id, $email);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // เช็คให้ละเอียดขึ้นว่าอะไรซ้ำ จะได้แจ้งเตือนถูกจุด
            if ($row['student_id'] === $student_id) {
                $error = "รหัสนักศึกษานี้ มีการลงทะเบียนในระบบแล้ว";
            } else {
                $error = "อีเมลนี้ มีการลงทะเบียนในระบบแล้ว";
            }
        } else {
            // ถ้าข้อมูลถูกต้องและไม่ซ้ำ บันทึกข้อมูล
            $stmt = $conn->prepare("INSERT INTO users (student_id, email, password, name, faculty, major, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $student_id, $email, $password, $name, $faculty, $major, $phone);
            
            if ($stmt->execute()) {
                echo "<script>alert('สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ'); window.location.href='login.php';</script>";
                exit();
            } else {
                $error = "เกิดข้อผิดพลาดในการลงทะเบียน โปรดลองใหม่อีกครั้ง";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - ระบบจัดการชมรม (MCRU)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            background: linear-gradient(rgba(11, 44, 92, 0.7), rgba(11, 44, 92, 0.9)), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center center / cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .register-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 550px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            overflow: hidden;
            margin: 20px;
        }
        .register-card-header {
            background: linear-gradient(135deg, var(--mru-gold), #d98a12);
            color: white;
            text-align: center;
            padding: 35px 20px 25px;
            position: relative;
        }
        .register-card-header h2 { font-weight: 700; margin-bottom: 5px; font-size: 1.8rem; color: var(--mru-blue); }
        .register-card-header p { font-size: 0.95rem; color: #ffffff; margin: 0; }
        .register-card-header::after {
            content: ''; position: absolute; top: -30px; left: -30px; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.15); border-radius: 50%;
        }
        .register-card-body { padding: 35px 40px; }
        .input-group-custom { position: relative; margin-bottom: 15px; }
        .input-group-custom i.icon-left { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a0aec0; z-index: 10; }
        .form-control-custom {
            height: 50px; border-radius: 10px; padding-left: 45px; padding-right: 15px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 0.95rem; transition: all 0.3s ease; width: 100%;
        }
        select.form-control-custom {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right 15px center; background-size: 16px 12px;
        }
        .form-control-custom:focus {
            border-color: var(--mru-gold); background-color: #fff; box-shadow: 0 0 0 3px rgba(249, 168, 38, 0.15); outline: none;
        }
        .form-control-custom:focus + i.icon-left, .form-control-custom:not(:placeholder-shown) + i.icon-left { color: var(--mru-gold); }
        
        .btn-register-submit {
            background-color: var(--mru-blue); color: white; height: 50px; border-radius: 10px; font-weight: 600; font-size: 1rem; border: none; transition: all 0.3s; margin-top: 15px; width: 100%;
        }
        .btn-register-submit:hover { background-color: var(--mru-blue-hover); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(11, 44, 92, 0.2); color: white; }
        .login-link { text-align: center; margin-top: 20px; font-size: 0.95rem; }
        .login-link a { color: var(--mru-blue); font-weight: 600; text-decoration: none; transition: 0.2s; }
        .login-link a:hover { color: var(--mru-gold); text-decoration: underline; }
        
        @media (max-width: 576px) { .register-card-body { padding: 30px 20px; } }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="register-card-header">
            <h2>สมัครสมาชิกใหม่</h2>
            <p>ระบบบริหารจัดการชมรม ม.ราชภัฏหมู่บ้านจอมบึง</p>
        </div>

        <div class="register-card-body">
            
            <?php if(isset($error)) echo "<div class='alert alert-danger p-2 mb-4 rounded-3 fw-bold small'><i class='fa-solid fa-circle-exclamation me-2'></i>$error</div>"; ?>

            <form method="POST">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group-custom">
                            <!-- เพิ่มเงื่อนไขบังคับให้กรอก 9 หลักเท่านั้น -->
                            <input type="text" name="student_id" class="form-control-custom" placeholder="รหัสนักศึกษา 9 หลัก" required minlength="9" maxlength="9" pattern="[0-9]{9}" title="กรุณากรอกรหัสนักศึกษาเป็นตัวเลข 9 หลักเท่านั้น">
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
                        <option value="คณะวิทยาศาสตร์และเทคโนโลยี">คณะวิทยาศาสตร์และเทคโนโลยี</option>
                        <option value="คณะวิทยาการจัดการ">คณะวิทยาการจัดการ</option>
                        <option value="คณะครุศาสตร์">คณะครุศาสตร์</option>
                        <option value="คณะมนุษยศาสตร์และสังคมศาสตร์">คณะมนุษยศาสตร์และสังคมศาสตร์</option>
                        <option value="คณะเทคโนโลยีอุตสาหกรรม">คณะเทคโนโลยีอุตสาหกรรม</option>
                        <option value="วิทยาลัยมวยไทยศึกษาและการแพทย์แผนไทย">วิทยาลัยมวยไทยศึกษาและการแพทย์แผนไทย</option>
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
                
                <button type="submit" class="btn btn-register-submit">
                    ลงทะเบียนเข้าใช้งาน <i class="fa-solid fa-check ms-1"></i>
                </button>
            </form>

            <div class="login-link">
                มีบัญชีนักศึกษาอยู่แล้ว? <a href="login.php">กลับไปเข้าสู่ระบบ</a>
            </div>
        </div>
    </div>

<script>
const majorsData = {
    "คณะวิทยาศาสตร์และเทคโนโลยี": ["เทคโนโลยีสารสนเทศ (IT)", "วิทยาการคอมพิวเตอร์ (CS)", "สาธารณสุขศาสตร์", "วิทยาศาสตร์การกีฬา", "คณิตศาสตร์", "วิทยาศาสตร์สิ่งแวดล้อม"],
    "คณะวิทยาการจัดการ": ["คอมพิวเตอร์ธุรกิจ", "การจัดการ", "การบัญชี", "การตลาด", "การบริหารทรัพยากรมนุษย์", "นิเทศศาสตร์"],
    "คณะครุศาสตร์": ["การศึกษาปฐมวัย", "ภาษาไทย", "ภาษาอังกฤษ", "วิทยาศาสตร์ทั่วไป", "คณิตศาสตร์", "พลศึกษา"],
    "คณะมนุษยศาสตร์และสังคมศาสตร์": ["รัฐประศาสนศาสตร์", "การพัฒนาชุมชน", "นิติศาสตร์", "ภาษาอังกฤษธุรกิจ", "ศิลปกรรม"],
    "คณะเทคโนโลยีอุตสาหกรรม": ["เทคโนโลยีวิศวกรรมไฟฟ้า", "เทคโนโลยีวิศวกรรมเครื่องกล", "เทคโนโลยีอุตสาหการ", "การจัดการโลจิสติกส์"],
    "วิทยาลัยมวยไทยศึกษาและการแพทย์แผนไทย": ["มวยไทยศึกษา", "การแพทย์แผนไทย"]
};

function updateMajors() {
    const facultySelect = document.getElementById("faculty");
    const majorSelect = document.getElementById("major");
    const selectedFaculty = facultySelect.value;

    majorSelect.innerHTML = '<option value="">-- เลือกสาขาวิชา --</option>';

    if (selectedFaculty && majorsData[selectedFaculty]) {
        majorSelect.disabled = false;
        majorsData[selectedFaculty].forEach(function(major) {
            const option = document.createElement("option");
            option.value = major;
            option.text = major;
            majorSelect.appendChild(option);
        });
    } else {
        majorSelect.disabled = true;
        majorSelect.innerHTML = '<option value="">-- โปรดเลือกคณะก่อน --</option>';
    }
}
</script>

</body>
</html>

