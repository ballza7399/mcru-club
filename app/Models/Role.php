<?php
namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    /** ดึงรายการบทบาทระบบหลัก (เช่น admin, student) */
    public function listSystemRoles(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM roles WHERE scope = "system"');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** ดึงรายการตำแหน่งของชมรม (รวมตำแหน่งดีฟอลต์ และตำแหน่งที่ชมรมนั้นสร้างเอง) */
    public function listClubRoles(?int $clubId = null): array
    {
        if ($clubId === null) {
            $stmt = $this->db->prepare('SELECT * FROM roles WHERE scope = "club" AND club_id IS NULL');
            $stmt->execute();
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare(
            'SELECT * FROM roles 
             WHERE scope = "club" AND (club_id IS NULL OR club_id = ?)
             ORDER BY club_id ASC, id ASC'
        );
        $stmt->execute([$clubId]);
        return $stmt->fetchAll();
    }

    /** ค้นหาบทบาทโดย ID */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM roles WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** สร้างบทบาท/ตำแหน่งใหม่สำหรับชมรม (หากกำหนดเป็น NULL จะเป็นตำแหน่งชมรมส่วนกลางที่ทุกชมรมใช้ร่วมกัน) */
    public function createClubRole(?int $clubId, string $roleKey, string $roleName): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO roles (role_key, role_name, scope, club_id) 
             VALUES (?, ?, "club", ?)'
        );
        return $stmt->execute([$roleKey, $roleName, $clubId]);
    }

    /** ลบบทบาท/ตำแหน่งของชมรม */
    public function deleteClubRole(int $roleId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM roles WHERE id = ?');
        return $stmt->execute([$roleId]);
    }

    /** ดึงรายการสิทธิ์ใช้งานระบบแบ่งตามระดับขอบเขต */
    public function listPermissions(string $scope): array
    {
        $stmt = $this->db->prepare('SELECT * FROM permissions WHERE scope = ?');
        $stmt->execute([$scope]);
        return $stmt->fetchAll();
    }

    /** ดึงรายการสิทธิ์ทั้งหมดที่ตำแหน่งนี้ได้รับ */
    public function getRolePermissions(int $roleId): array
    {
        $stmt = $this->db->prepare(
            'SELECT permission_id FROM role_permissions WHERE role_id = ?'
        );
        $stmt->execute([$roleId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];
    }

    /** ซิงค์สิทธิ์ใช้งานให้กับบทบาท */
    public function syncRolePermissions(int $roleId, array $permissionIds): void
    {
        $this->db->beginTransaction();
        try {
            // ลบสิทธิ์เดิมออกก่อน
            $stmtDel = $this->db->prepare('DELETE FROM role_permissions WHERE role_id = ?');
            $stmtDel->execute([$roleId]);

            // บันทึกสิทธิ์ใหม่
            if (!empty($permissionIds)) {
                $stmtIns = $this->db->prepare(
                    'INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)'
                );
                foreach ($permissionIds as $permId) {
                    $stmtIns->execute([$roleId, (int) $permId]);
                }
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** ดึงข้อมูลสมาชิกชมรมและตำแหน่งหน้าที่ */
    public function getClubMembers(int $clubId): array
    {
        $stmt = $this->db->prepare(
            'SELECT cm.id AS member_record_id, u.id AS user_id, u.student_id, u.name, 
                    u.faculty, u.major, u.phone, r.id AS role_id, r.role_name, r.role_key
             FROM club_members cm
             JOIN users u ON cm.user_id = u.id
             LEFT JOIN roles r ON cm.role_id = r.id
             WHERE cm.club_id = ?
             ORDER BY CASE WHEN r.role_key = "president" THEN 1 
                           WHEN r.role_key = "officer" THEN 2 
                           WHEN r.id IS NULL THEN 4 
                           ELSE 3 END ASC, cm.joined_at ASC'
        );
        $stmt->execute([$clubId]);
        return $stmt->fetchAll();
    }

    /** ดึงข้อมูลสมาชิกชมรมและตำแหน่งหน้าที่ แบบแบ่งหน้า */
    public function getClubMembersPaginated(int $clubId, int $limit, int $offset): array
    {
        $stmt = $this->db->prepare(
            'SELECT cm.id AS member_record_id, u.id AS user_id, u.student_id, u.name, 
                    u.faculty, u.major, u.phone, r.id AS role_id, r.role_name, r.role_key
             FROM club_members cm
             JOIN users u ON cm.user_id = u.id
             LEFT JOIN roles r ON cm.role_id = r.id
             WHERE cm.club_id = ?
             ORDER BY CASE WHEN r.role_key = "president" THEN 1 
                           WHEN r.role_key = "officer" THEN 2 
                           WHEN r.id IS NULL THEN 4 
                           ELSE 3 END ASC, cm.joined_at ASC
             LIMIT ? OFFSET ?'
        );
        $stmt->bindValue(1, $clubId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** นับจำนวนสมาชิกชมรมทั้งหมด */
    public function countClubMembers(int $clubId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM club_members WHERE club_id = ?');
        $stmt->execute([$clubId]);
        return (int) $stmt->fetchColumn();
    }

    /** กำหนดตำแหน่ง (Role) ให้กับสมาชิกชมรม */
    public function assignMemberRole(int $clubId, int $userId, ?int $roleId): bool
    {
        // หากเปลี่ยนเป็นประธานชมรม ให้ปรับปรุง president_id ในตาราง clubs ด้วย
        if ($roleId !== null) {
            $role = $this->find($roleId);
            if ($role && $role['role_key'] === 'president') {
                // อัปเดตตาราง clubs ให้ user คนนี้เป็นประธาน
                $stmtClub = $this->db->prepare('UPDATE clubs SET president_id = ? WHERE id = ?');
                $stmtClub->execute([$userId, $clubId]);

                // สำหรับประธานชมรมเก่าในตาราง club_members ให้ลดตำแหน่งเป็นสมาชิกทั่วไป (หรือตำแหน่งกรรมการ)
                $stmtRevoke = $this->db->prepare(
                    'UPDATE club_members SET role_id = 5 
                     WHERE club_id = ? AND role_id = ? AND user_id != ?'
                );
                $stmtRevoke->execute([$clubId, $roleId, $userId]);
            }
        } else {
            // หากยกเลิกบทบาทของประธานชมรม
            $stmtCheckPres = $this->db->prepare(
                'SELECT r.role_key FROM club_members cm 
                 JOIN roles r ON cm.role_id = r.id 
                 WHERE cm.club_id = ? AND cm.user_id = ?'
            );
            $stmtCheckPres->execute([$clubId, $userId]);
            $currentRoleKey = $stmtCheckPres->fetchColumn();
            if ($currentRoleKey === 'president') {
                $stmtClub = $this->db->prepare('UPDATE clubs SET president_id = NULL WHERE id = ?');
                $stmtClub->execute([$clubId]);
            }
        }

        $stmt = $this->db->prepare(
            'UPDATE club_members SET role_id = ? WHERE club_id = ? AND user_id = ?'
        );
        return $stmt->execute([$roleId, $clubId, $userId]);
    }

    /** คัดสมาชิกออกจากชมรม */
    public function removeClubMember(int $clubId, int $userId): bool
    {
        $this->db->beginTransaction();
        try {
            // เช็คว่าเป็นประธานไหม หากใช่ ให้ปลดจากประธานชมรมในตาราง clubs ด้วย
            $stmtCheck = $this->db->prepare('SELECT president_id FROM clubs WHERE id = ?');
            $stmtCheck->execute([$clubId]);
            $presId = $stmtCheck->fetchColumn();
            if ((int)$presId === $userId) {
                $stmtUpdateClub = $this->db->prepare('UPDATE clubs SET president_id = NULL WHERE id = ?');
                $stmtUpdateClub->execute([$clubId]);
            }

            // ลบจากความสัมพันธ์สมาชิก
            $stmtDelMem = $this->db->prepare('DELETE FROM club_members WHERE club_id = ? AND user_id = ?');
            $stmtDelMem->execute([$clubId, $userId]);

            // อัปเดตใบสมัครเข้าชมรมนี้เป็น rejected หรือลบออกไปเลยก็ได้ เพื่อไม่ให้ยังเป็น approved อยู่
            $stmtDelApp = $this->db->prepare(
                'UPDATE applications SET status = "rejected" WHERE club_id = ? AND user_id = ? AND status = "approved"'
            );
            $stmtDelApp->execute([$clubId, $userId]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** ตรวจสอบว่าผู้ใช้มีสิทธิ์ใช้งานตามคีย์ที่กำหนดหรือไม่ */
    public function hasPermission(int $userId, string $permissionKey, ?int $clubId = null): bool
    {
        // 1. ดึงสิทธิ์ระดับระบบของผู้ใช้งาน
        $stmtUser = $this->db->prepare(
            'SELECT u.role_id, r.role_key 
             FROM users u
             JOIN roles r ON u.role_id = r.id
             WHERE u.id = ?'
        );
        $stmtUser->execute([$userId]);
        $user = $stmtUser->fetch();
        if (!$user) return false;

        // หากเป็นผู้ดูแลระบบหลัก (System Admin) จะได้สิทธิ์ทุกอย่างทั้งหมดในระบบ
        if ($user['role_key'] === 'admin') {
            return true;
        }

        // 2. ตรวจสอบสิทธิ์ระดับชมรม (หากกำหนด clubId มา)
        if ($clubId !== null) {
            // เช็คว่าผู้ใช้งานเป็นประธานชมรมของชมรมนี้โดยตรงตามตาราง clubs หรือไม่
            $stmtPres = $this->db->prepare('SELECT COUNT(*) FROM clubs WHERE id = ? AND president_id = ?');
            $stmtPres->execute([$clubId, $userId]);
            if ((int) $stmtPres->fetchColumn() > 0) {
                return true; // ประธานชมรมได้รับสิทธิ์การจัดการในชมรมตัวเองทุกประการ
            }

            // ตรวจสอบตำแหน่งอื่น ๆ ของสมาชิกชมรมนี้ และดึงสิทธิ์ที่เชื่อมโยงกับตำแหน่งนั้น
            $stmtClubPerm = $this->db->prepare(
                'SELECT COUNT(*) 
                 FROM club_members cm
                 JOIN roles r ON cm.role_id = r.id
                 JOIN role_permissions rp ON r.id = rp.role_id
                 JOIN permissions p ON rp.permission_id = p.id
                 WHERE cm.club_id = ? AND cm.user_id = ? AND p.perm_key = ?'
            );
            $stmtClubPerm->execute([$clubId, $userId, $permissionKey]);
            if ((int) $stmtClubPerm->fetchColumn() > 0) {
                return true;
            }
        }

        // 3. ตรวจสอบสิทธิ์ระดับระบบจากสิทธิ์การเข้าถึงทั่วไปของสิทธิ์ที่สวมบทบาทหลัก
        $stmtSysPerm = $this->db->prepare(
            'SELECT COUNT(*) 
             FROM role_permissions rp
             JOIN permissions p ON rp.permission_id = p.id
             WHERE rp.role_id = ? AND p.perm_key = ?'
        );
        $stmtSysPerm->execute([$user['role_id'], $permissionKey]);
        return (int) $stmtSysPerm->fetchColumn() > 0;
    }
}
