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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['role']    = $user['role'];
                $this->redirect('/');
            }

            $error = 'รหัสนักศึกษา/อีเมล หรือรหัสผ่านไม่ถูกต้อง';
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
}
