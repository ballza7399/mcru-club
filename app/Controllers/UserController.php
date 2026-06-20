<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    /** หน้าต่างหลักสำหรับแอดมินในการจัดการผู้ใช้งานระบบทั้งหมด */
    public function manage(): void
    {
        $this->requireRole('admin');

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        $userModel = new User;
        $roleModel = new Role;

        $totalUsers = $userModel->countAll();
        $totalPages = (int)ceil($totalUsers / $limit);

        $users = $userModel->allWithRole($limit, $offset);
        $roles = $roleModel->listSystemRoles();

        $this->view('users/manage', [
            'users' => $users,
            'roles' => $roles,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'pageTitle' => 'จัดการผู้ใช้ในระบบ'
        ], 'backoffice');
    }

    /** อัปเดตบทบาทสิทธิ์ใช้งานของผู้ใช้ */
    public function updateRole(): void
    {
        $this->requireRole('admin');

        $userId = (int)$_POST['user_id'];
        $roleId = (int)$_POST['role_id'];

        // ป้องกันสิทธิ์แอดมินสูงสุดล็อกตัวเองออกจากระบบ
        if ($userId === (int)$_SESSION['user_id'] && $roleId !== 1) {
            $this->flash('ผิดพลาด: คุณไม่สามารถปลดสิทธิ์ผู้ดูแลระบบหลักของตัวคุณเองได้');
            $this->redirect('/backoffice/users');
        }

        $userModel = new User;
        $userModel->setRoleId($userId, $roleId);

        $this->flash('อัปเดตบทบาทผู้ใช้งานสำเร็จแล้ว');
        $this->redirect('/backoffice/users');
    }

    /** เปิด/ปิดการใช้งาน บัญชีผู้ใช้งาน */
    public function toggleStatus(): void
    {
        $this->requireRole('admin');

        $userId = (int)$_POST['user_id'];
        $status = $_POST['status'] === 'active' ? 'active' : 'disabled';

        // ป้องกันแอดมินระงับบัญชีของตัวเอง
        if ($userId === (int)$_SESSION['user_id']) {
            $this->flash('ผิดพลาด: คุณไม่สามารถระงับการใช้งานบัญชีของผู้ดูแลระบบของคุณเองได้');
            $this->redirect('/backoffice/users');
        }

        $userModel = new User;
        $userModel->setStatus($userId, $status);

        $statusText = $status === 'active' ? 'เปิดใช้งานบัญชี' : 'ระงับบัญชีผู้ใช้งาน';
        $this->flash($statusText . ' เรียบร้อยแล้ว');
        $this->redirect('/backoffice/users');
    }

    /** เปลี่ยนหรือตั้งรหัสผ่านใหม่ (Reset Password) */
    public function resetPassword(): void
    {
        $this->requireRole('admin');

        $userId = (int)$_POST['user_id'];
        $newPassword = $_POST['new_password'] ?? '';

        if (trim($newPassword) === '') {
            $this->flash('ผิดพลาด: กรุณากรอกรหัสผ่านใหม่');
            $this->redirect('/backoffice/users');
        }

        $userModel = new User;
        $userModel->resetPassword($userId, $newPassword);

        $this->flash('รีเซ็ตรหัสผ่านใหม่ให้กับผู้ใช้งานสำเร็จเสร็จสิ้น');
        $this->redirect('/backoffice/users');
    }
}
