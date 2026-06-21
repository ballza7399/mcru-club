<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginId  = $_POST['login_id'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = (new User)->findByCredentials($loginId, $password);

            if ($user) {
                if (isset($user['status']) && $user['status'] === 'disabled') {
                    $error = 'บัญชีผู้ใช้งานของคุณถูกระงับการใช้งานชั่วคราว โปรดติดต่อผู้ดูแลระบบ';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name']    = $user['name'];
                    $_SESSION['role']    = $user['role'];
                    $this->redirect('/');
                }
            } else {
                $error = 'รหัสนักศึกษา/อีเมล หรือรหัสผ่านไม่ถูกต้อง';
            }
        }

        $this->view('auth/login', ['error' => $error], 'auth');
    }

    public function register(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $error = null;
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = trim($_POST['student_id'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? '';
            $name      = trim($_POST['name'] ?? '');
            $faculty   = $_POST['faculty'] ?? '';
            $major     = $_POST['major'] ?? '';
            $phone     = trim($_POST['phone'] ?? '');

            if (strlen($studentId) !== 9) {
                $error = 'รหัสนักศึกษาต้องมี 9 หลักเท่านั้น';
            } else {
                $dup = (new User)->findDuplicate($studentId, $email);
                if ($dup === 'student_id') {
                    $error = 'รหัสนักศึกษานี้ มีการลงทะเบียนในระบบแล้ว';
                } elseif ($dup === 'email') {
                    $error = 'อีเมลนี้ มีการลงทะเบียนในระบบแล้ว';
                } else {
                    $ok = (new User)->create([
                        'student_id' => $studentId,
                        'email'      => $email,
                        'password'   => $password,
                        'name'       => $name,
                        'faculty'    => $faculty,
                        'major'      => $major,
                        'phone'      => $phone,
                    ]);
                    if ($ok) {
                        $this->redirect('/auth/register?success=1');
                    }
                    $error = 'เกิดข้อผิดพลาดในการลงทะเบียน โปรดลองใหม่อีกครั้ง';
                }
            }
        }

        if (isset($_GET['success']) && $_GET['success'] === '1') {
            $success = true;
        }

        $majorsData = (new \App\Models\Faculty)->allWithMajors();
        $this->view('auth/register', ['error' => $error, 'success' => $success, 'majorsData' => $majorsData], 'auth');
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/auth/login');
    }

    public function giveConsent(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'โปรดเข้าสู่ระบบก่อนดำเนินการ']);
            exit;
        }
        
        $userId = (int)$_SESSION['user_id'];
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        
        $db = \App\Core\Database::instance();
        
        $stmt = $db->query("SELECT policy_key, version FROM policies");
        $policies = $stmt->fetchAll();
        
        $db->beginTransaction();
        try {
            foreach ($policies as $p) {
                $key = $p['policy_key'];
                $ver = $p['version'];
                
                $stmtCheck = $db->prepare("SELECT COUNT(*) FROM user_consents WHERE user_id = ? AND policy_key = ? AND version = ?");
                $stmtCheck->execute([$userId, $key, $ver]);
                if ((int)$stmtCheck->fetchColumn() === 0) {
                    $stmtInsert = $db->prepare("INSERT INTO user_consents (user_id, policy_key, version, ip_address) VALUES (?, ?, ?, ?)");
                    $stmtInsert->execute([$userId, $key, $ver, $ip]);
                }
            }
            $db->commit();
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            $db->rollBack();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึก: ' . $e->getMessage()]);
        }
        exit;
    }

    /** หน้าข้อมูลส่วนตัว (Profile) */
    public function profile(): void
    {
        $this->requireAuth();
        
        $userId = (int)$_SESSION['user_id'];
        $userModel = new User();
        $user = $userModel->findById($userId);
        
        if (!$user) {
            $this->flash('ไม่พบข้อมูลผู้ใช้งาน');
            $this->redirect('/');
        }
        
        $facultiesData = (new \App\Models\Faculty)->allWithMajors();
        
        $this->view('auth/profile', [
            'user' => $user,
            'facultiesData' => $facultiesData,
            'pageTitle' => 'ข้อมูลส่วนตัวของฉัน'
        ]);
    }

    /** บันทึกการแก้ไขข้อมูลส่วนตัว (Profile Update) */
    public function profileUpdate(): void
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }
        
        $userId = (int)$_SESSION['user_id'];
        $userModel = new User();
        $user = $userModel->findById($userId);
        
        if (!$user) {
            $this->flash('ไม่พบข้อมูลผู้ใช้งาน');
            $this->redirect('/');
        }
        
        $email = trim($_POST['email'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $faculty = $_POST['faculty'] ?? '';
        $major = $_POST['major'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // ตรวจสอบความถูกต้องของข้อมูล
        if (empty($email) || empty($name)) {
            $this->flash('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน (ชื่อ และ อีเมล)');
            $this->redirect('/profile');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('รูปแบบอีเมลไม่ถูกต้อง');
            $this->redirect('/profile');
        }
        
        if ($userModel->isEmailTakenByAnother($email, $userId)) {
            $this->flash('อีเมลนี้ถูกใช้งานโดยผู้ใช้อื่นในระบบแล้ว');
            $this->redirect('/profile');
        }
        
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $this->flash('รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร');
                $this->redirect('/profile');
            }
            if ($password !== $confirmPassword) {
                $this->flash('การยืนยันรหัสผ่านใหม่ไม่ตรงกัน');
                $this->redirect('/profile');
            }
        }
        
        // จัดการอัปโหลดรูปภาพโปรไฟล์ (Avatar)
        $avatarPath = $user['avatar']; // ใช้รูปเดิมเป็นค่าเริ่มต้น
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            // ตรวจสอบประเภทไฟล์
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $fileType = $_FILES['avatar']['type'];
            
            // เช็ค mime type เพิ่มเติมเพื่อความปลอดภัย
            $info = @getimagesize($_FILES['avatar']['tmp_name']);
            if ($info === false || !in_array($info['mime'], $allowedTypes, true)) {
                $this->flash('ประเภทไฟล์รูปภาพไม่ถูกต้อง รองรับเฉพาะ JPG, PNG, WEBP และ GIF');
                $this->redirect('/profile');
            }
            
            // ตรวจสอบขนาดไฟล์ (ไม่เกิน 2MB)
            if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
                $this->flash('ขนาดไฟล์รูปภาพต้องไม่เกิน 2MB');
                $this->redirect('/profile');
            }
            
            // ลบรูปภาพโปรไฟล์เดิมถ้ามี (และไม่ใช่ค่าว่าง)
            if (!empty($user['avatar']) && file_exists(BASE_PATH . '/' . $user['avatar'])) {
                @unlink(BASE_PATH . '/' . $user['avatar']);
            }
            
            // อัปโหลดและปรับขนาดรูปภาพ
            $uploaded = \App\Core\Image::uploadResized($_FILES['avatar'], 'avatar', 300, 300);
            if ($uploaded) {
                $avatarPath = $uploaded;
            } else {
                $this->flash('เกิดข้อผิดพลาดในการอัปโหลดรูปภาพโปรไฟล์');
                $this->redirect('/profile');
            }
        }
        
        // เตรียมข้อมูลบันทึก
        $updateData = [
            'email' => $email,
            'name' => $name,
            'phone' => $phone,
            'faculty' => $faculty,
            'major' => $major,
            'avatar' => $avatarPath
        ];
        
        if (!empty($password)) {
            $updateData['password'] = $password;
        }
        
        $ok = $userModel->updateProfile($userId, $updateData);
        
        if ($ok) {
            // อัปเดตข้อมูล Session
            $_SESSION['name'] = $name;
            $_SESSION['avatar'] = $avatarPath;
            
            $this->flash('อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว');
        } else {
            $this->flash('เกิดข้อผิดพลาดในการบันทึกข้อมูล โปรดลองอีกครั้ง');
        }
        
        $this->redirect('/profile');
    }
}

