<?php
namespace App\Models;

use App\Core\Model;

class Application extends Model
{
    /** สถานะการสมัครของ user กับชมรมหนึ่ง (null = ยังไม่เคยสมัคร) */
    public function statusFor(int $userId, int $clubId): ?string
    {
        $stmt = $this->db->prepare(
            'SELECT status FROM applications WHERE user_id = ? AND club_id = ?'
        );
        $stmt->execute([$userId, $clubId]);
        $row = $stmt->fetch();
        return $row ? $row['status'] : null;
    }

    public function exists(int $userId, int $clubId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM applications WHERE user_id = ? AND club_id = ?'
        );
        $stmt->execute([$userId, $clubId]);
        return (bool) $stmt->fetch();
    }

    public function create(int $userId, int $clubId): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO applications (user_id, club_id, status) VALUES (?, ?, "pending")'
        );
        $stmt->execute([$userId, $clubId]);
    }

    /** รายการคำขอ (admin เห็นทั้งหมด, president เห็นเฉพาะชมรมของตน) */
    public function listForManage(string $role, int $userId): array
    {
        $sql = 'SELECT a.id, u.name, u.student_id, u.faculty, u.major, u.phone,
                       c.club_name, a.status
                FROM applications a
                JOIN users u ON a.user_id = u.id
                JOIN clubs c ON a.club_id = c.id';
        if ($role === 'admin') {
            return $this->db->query($sql . ' ORDER BY a.id DESC')->fetchAll();
        }
        $stmt = $this->db->prepare(
            $sql . ' WHERE c.president_id = ? ORDER BY a.id DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /** club_id ของคำขอ (ใช้เช็คสิทธิ์ประธาน) */
    public function clubIdOf(int $appId): ?int
    {
        $stmt = $this->db->prepare('SELECT club_id FROM applications WHERE id = ?');
        $stmt->execute([$appId]);
        $row = $stmt->fetch();
        return $row ? (int) $row['club_id'] : null;
    }

    public function updateStatus(int $appId, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE applications SET status = ? WHERE id = ?');
        $stmt->execute([$status, $appId]);
    }
}
