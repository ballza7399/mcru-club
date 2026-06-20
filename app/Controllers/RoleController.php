<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Role;
use App\Models\Club;

class RoleController extends Controller
{
    public function manage(): void
    {
        $this->requireRole('admin', 'president');
        
        $roleModel = new Role;
        $clubModel = new Club;
        
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];
        
        $clubId = isset($_GET['club_id']) ? (int)$_GET['club_id'] : 0;
        
        if ($userRole === 'president') {
            // ค้นหาชมรมที่คนนี้เป็นประธาน
            $db = \App\Core\Database::instance();
            $stmt = $db->prepare('SELECT id FROM clubs WHERE president_id = ?');
            $stmt->execute([$userId]);
            $myClub = $stmt->fetch();
            if (!$myClub) {
                throw new \Exception('คุณไม่ได้เป็นประธานชมรมในระบบ', 403);
            }
            $clubId = (int)$myClub['id'];
        }
        
        // ดึงบทบาททั้งหมดตามขอบเขต
        if ($userRole === 'admin' && $clubId === 0) {
            // แอดมินหลักกำลังจัดการสิทธิ์ระบบทั่วไป
            $roles = $roleModel->listSystemRoles();
            $permissions = $roleModel->listPermissions('system');
            $scopeLabel = 'ระบบหลัก';
        } else {
            // จัดการระดับสิทธิ์ของตำแหน่งในชมรม
            $roles = $roleModel->listClubRoles($clubId);
            $permissions = $roleModel->listPermissions('club');
            $scopeLabel = 'ระดับชมรม';
        }
        
        $club = $clubId > 0 ? $clubModel->findWithDetail($clubId) : null;
        $allClubsList = ($userRole === 'admin') ? $clubModel->allWithMemberCount() : [];
        
        // หา Permission mapping ของแต่ละ Role เพื่อส่งไปเช็คใน View
        $rolePerms = [];
        foreach ($roles as $r) {
            $rolePerms[$r['id']] = $roleModel->getRolePermissions($r['id']);
        }
        
        $this->view('roles/manage', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePerms' => $rolePerms,
            'club' => $club,
            'allClubsList' => $allClubsList,
            'currentClubId' => $clubId,
            'scopeLabel' => $scopeLabel
        ], 'backoffice');
    }

    public function store(): void
    {
        $this->requireRole('admin', 'president');
        
        $roleModel = new Role;
        $clubModel = new Club;
        
        $clubId = (int)($_POST['club_id'] ?? 0);
        $roleName = trim($_POST['role_name'] ?? '');
        
        if ($roleName === '') {
            $this->flash('กรุณากรอกชื่อตำแหน่ง');
            $this->redirect('/backoffice/roles?club_id=' . $clubId);
        }
        
        // เช็คสิทธิ์การจัดการชมรม
        $canManage = ($_SESSION['role'] === 'admin') || $clubModel->isPresident($clubId, $_SESSION['user_id']);
        if (!$canManage) {
            $this->redirect('/');
        }
        
        // สร้าง role_key แบบสุ่มหรือตามตัวอักษร
        $roleKey = 'custom_' . time() . '_' . rand(10, 99);
        
        $roleModel->createClubRole($clubId, $roleKey, $roleName);
        $this->flash('เพิ่มตำแหน่งใหม่สำเร็จแล้ว');
        $this->redirect('/backoffice/roles?club_id=' . $clubId);
    }

    public function delete(string $id): void
    {
        $this->requireRole('admin', 'president');
        
        $roleId = (int)$id;
        $roleModel = new Role;
        $clubModel = new Club;
        
        $role = $roleModel->find($roleId);
        if (!$role || $role['scope'] !== 'club' || $role['club_id'] === null) {
            $this->flash('ไม่สามารถลบตำแหน่งของระบบหลักได้');
            $this->redirect('/backoffice/roles');
        }
        
        $clubId = (int)$role['club_id'];
        
        // เช็คสิทธิ์
        $canManage = ($_SESSION['role'] === 'admin') || $clubModel->isPresident($clubId, $_SESSION['user_id']);
        if (!$canManage) {
            $this->redirect('/');
        }
        
        $roleModel->deleteClubRole($roleId, $clubId);
        $this->flash('ลบตำแหน่งชมรมเรียบร้อยแล้ว');
        $this->redirect('/backoffice/roles?club_id=' . $clubId);
    }

    public function syncPermissions(): void
    {
        $this->requireRole('admin', 'president');
        
        $roleModel = new Role;
        $clubModel = new Club;
        
        $roleId = (int)$_POST['role_id'];
        $clubId = (int)($_POST['club_id'] ?? 0);
        $permissionIds = $_POST['permissions'] ?? [];
        
        $role = $roleModel->find($roleId);
        if (!$role) {
            $this->redirect('/');
        }
        
        // เช็คสิทธิ์
        if ($role['scope'] === 'system') {
            if ($_SESSION['role'] !== 'admin') {
                $this->redirect('/');
            }
        } else {
            $targetClubId = $role['club_id'] ?? $clubId;
            $canManage = ($_SESSION['role'] === 'admin') || $clubModel->isPresident($targetClubId, $_SESSION['user_id']);
            if (!$canManage) {
                $this->redirect('/');
            }
        }
        
        $roleModel->syncRolePermissions($roleId, $permissionIds);
        $this->flash('บันทึกการตั้งค่าสิทธิ์เรียบร้อยแล้ว');
        
        $redirectUrl = '/backoffice/roles';
        if ($clubId > 0) {
            $redirectUrl .= '?club_id=' . $clubId;
        }
        $this->redirect($redirectUrl);
    }
}
