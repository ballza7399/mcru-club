<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Role;
use App\Models\Club;

class RoleController extends Controller
{
    public function manage(): void
    {
        $this->requireRole('admin');
        
        $roleModel = new Role;
        
        $type = isset($_GET['type']) ? $_GET['type'] : 'system';
        if ($type !== 'club') {
            $type = 'system';
        }
        
        // ดึงบทบาททั้งหมดตามขอบเขต
        if ($type === 'system') {
            // แอดมินหลักกำลังจัดการสิทธิ์ระบบทั่วไป
            $roles = $roleModel->listSystemRoles();
            $permissions = $roleModel->listPermissions('system');
            $scopeLabel = 'ระบบหลัก';
        } else {
            // จัดการระดับสิทธิ์ของตำแหน่งในชมรมส่วนกลาง (club_id เป็น NULL)
            $roles = $roleModel->listClubRoles(null);
            $permissions = $roleModel->listPermissions('club');
            $scopeLabel = 'ตำแหน่งชมรมส่วนกลาง';
        }
        
        // หา Permission mapping ของแต่ละ Role เพื่อส่งไปเช็คใน View
        $rolePerms = [];
        foreach ($roles as $r) {
            $rolePerms[$r['id']] = $roleModel->getRolePermissions($r['id']);
        }
        
        $this->view('roles/manage', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePerms' => $rolePerms,
            'scopeLabel' => $scopeLabel,
            'type' => $type
        ], 'backoffice');
    }

    public function store(): void
    {
        $this->requireRole('admin');
        
        $roleModel = new Role;
        $roleName = trim($_POST['role_name'] ?? '');
        $type = $_POST['type'] ?? 'club';
        
        if ($roleName === '') {
            $this->flash('กรุณากรอกชื่อตำแหน่ง');
            $this->redirect('/backoffice/roles?type=' . $type);
        }
        
        // สร้าง role_key แบบสุ่ม
        $roleKey = 'custom_' . time() . '_' . rand(10, 99);
        
        // สร้างตำแหน่งกลาง (club_id = NULL)
        $roleModel->createClubRole(null, $roleKey, $roleName);
        
        $this->flash('เพิ่มตำแหน่งใหม่สำเร็จแล้ว');
        $this->redirect('/backoffice/roles?type=' . $type);
    }

    public function delete(string $id): void
    {
        $this->requireRole('admin');
        
        $roleId = (int)$id;
        $roleModel = new Role;
        
        $role = $roleModel->find($roleId);
        // สามารถลบได้เฉพาะบทบาทระดับชมรมที่เป็น custom ที่สร้างขึ้นใหม่ (เริ่มต้นด้วย custom_)
        if (!$role || $role['scope'] !== 'club' || strpos($role['role_key'], 'custom_') !== 0) {
            $this->flash('ไม่สามารถลบตำแหน่งระบบหลักหรือตำแหน่งเริ่มต้นได้');
            $this->redirect('/backoffice/roles?type=club');
        }
        
        $roleModel->deleteClubRole($roleId);
        $this->flash('ลบตำแหน่งชมรมส่วนกลางเรียบร้อยแล้ว');
        $this->redirect('/backoffice/roles?type=club');
    }

    public function syncPermissions(): void
    {
        $this->requireRole('admin');
        
        $roleModel = new Role;
        
        $roleId = (int)$_POST['role_id'];
        $permissionIds = $_POST['permissions'] ?? [];
        
        $role = $roleModel->find($roleId);
        if (!$role) {
            $this->redirect('/backoffice/roles');
        }
        
        $roleModel->syncRolePermissions($roleId, $permissionIds);
        $this->flash('บันทึกการตั้งค่าสิทธิ์เรียบร้อยแล้ว');
        
        $type = $role['scope'] === 'system' ? 'system' : 'club';
        $this->redirect('/backoffice/roles?type=' . $type);
    }
}
