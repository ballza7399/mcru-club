<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    /**
     * ค้นหา user ด้วย student_id หรือ email พร้อมตรวจรหัสผ่าน
     * คืนค่า row หรือ null
     *
     * หมายเหตุด้านความปลอดภัย: รหัสผ่านปัจจุบันเก็บเป็น plain text
     * เมื่อ migrate ให้ใช้ password_hash() ตอนสมัครและ password_verify() ตรงนี้
     */
    public function findByCredentials(string $loginId, string $password): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT u.id, u.name, u.password, u.status, r.role_key AS role 
             FROM users u
             JOIN roles r ON u.role_id = r.id
             WHERE u.student_id = ? OR u.email = ?'
        );
        $stmt->execute([$loginId, $loginId]);
        $user = $stmt->fetch() ?: null;

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Remove hash for safety

            if ($user['role'] === 'student') {
                // Check if they are a president of any club to grant access to backend management
                $stmtPres = $this->db->prepare('SELECT COUNT(*) FROM clubs WHERE president_id = ?');
                $stmtPres->execute([$user['id']]);
                if ((int)$stmtPres->fetchColumn() > 0) {
                    $user['role'] = 'president';
                }
            }
            return $user;
        }

        return null;
    }

    /** ตรวจว่า student_id หรือ email ซ้ำ — คืน 'student_id'|'email'|null */
    public function findDuplicate(string $studentId, string $email): ?string
    {
        $stmt = $this->db->prepare(
            'SELECT student_id, email FROM users WHERE student_id = ? OR email = ?'
        );
        $stmt->execute([$studentId, $email]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return $row['student_id'] === $studentId ? 'student_id' : 'email';
    }

    public function create(array $data): bool
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            'INSERT INTO users (student_id, email, password, name, faculty, major, phone, role_id)
             VALUES (?, ?, ?, ?, ?, ?, ?, 2)'
        );
        return $stmt->execute([
            $data['student_id'], $data['email'], $hashedPassword,
            $data['name'], $data['faculty'], $data['major'], $data['phone'],
        ]);
    }

    public function findByStudentId(string $studentId): ?array
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE student_id = ?');
        $stmt->execute([$studentId]);
        return $stmt->fetch() ?: null;
    }

    public function setRole(int $userId, string $role): void
    {
        $stmtRole = $this->db->prepare('SELECT id FROM roles WHERE role_key = ?');
        $stmtRole->execute([$role]);
        $roleRow = $stmtRole->fetch();
        if ($roleRow) {
            $stmt = $this->db->prepare(
                'UPDATE users SET role_id = ? WHERE id = ?'
            );
            $stmt->execute([$roleRow['id'], $userId]);
        }
    }

    /** ดึงรายการผู้ใช้งานทั้งหมดพร้อมข้อมูลบทบาท */
    public function allWithRole(): array
    {
        $stmt = $this->db->prepare(
            'SELECT u.*, r.role_name, r.role_key 
             FROM users u
             JOIN roles r ON u.role_id = r.id
             ORDER BY u.id ASC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** กำหนดบทบาทโดยตรงผ่าน role_id */
    public function setRoleId(int $userId, int $roleId): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET role_id = ? WHERE id = ?');
        return $stmt->execute([$roleId, $userId]);
    }

    /** อัปเดตสถานะการใช้งาน (active / disabled) */
    public function setStatus(int $userId, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $userId]);
    }

    /** รีเซ็ตรหัสผ่านของผู้ใช้งาน */
    public function resetPassword(int $userId, string $newPassword): bool
    {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
        return $stmt->execute([$hashed, $userId]);
    }
}
