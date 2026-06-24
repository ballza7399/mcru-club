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
        $db = \App\Core\Database::instance();
        
        // Fetch all roles (both system and club scope)
        $roles = $db->query('SELECT * FROM roles ORDER BY scope DESC, id ASC')->fetchAll();
        // Fetch all permissions
        $permissions = $db->query('SELECT * FROM permissions ORDER BY scope DESC, id ASC')->fetchAll();
        
        // Fetch permission mapping for each role
        $rolePerms = [];
        foreach ($roles as $r) {
            $rolePerms[$r['id']] = $roleModel->getRolePermissions((int)$r['id']);
        }
        
        $this->view('roles/manage', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePerms' => $rolePerms,
            'pageTitle' => 'จัดการตารางสิทธิ์การใช้งาน (Role Matrix)'
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
        $matrix = $_POST['matrix'] ?? []; // Associative array: [role_id => [permission_id_1, permission_id_2, ...]]
        
        $db = \App\Core\Database::instance();
        $roles = $db->query('SELECT id FROM roles')->fetchAll(\PDO::FETCH_COLUMN);
        
        foreach ($roles as $roleId) {
            $permissionIds = $matrix[$roleId] ?? [];
            $permissionIds = array_map('intval', $permissionIds);
            $roleModel->syncRolePermissions((int)$roleId, $permissionIds);
        }
        
        $this->flash('บันทึกตารางสิทธิ์การใช้งาน (Role Matrix) เรียบร้อยแล้ว');
        $this->redirect('/backoffice/roles');
    }
}
