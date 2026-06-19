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
            'SELECT id, name, role FROM users
             WHERE (student_id = ? OR email = ?) AND password = ?'
        );
        $stmt->execute([$loginId, $loginId, $password]);
        return $stmt->fetch() ?: null;
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
        $stmt = $this->db->prepare(
            'INSERT INTO users (student_id, email, password, name, faculty, major, phone)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        return $stmt->execute([
            $data['student_id'], $data['email'], $data['password'],
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
        $stmt = $this->db->prepare(
            "UPDATE users SET role = ? WHERE id = ? AND role = 'student'"
        );
        $stmt->execute([$role, $userId]);
    }
}
