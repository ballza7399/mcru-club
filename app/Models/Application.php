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

    /** รายการคำขอ (admin เห็นทั้งหมด, president เห็นเฉพาะชมรมของตน) แบบแบ่งหน้า */
    public function listForManage(string $role, int $userId, int $limit = 10, int $offset = 0): array
    {
        $sql = 'SELECT a.id, u.name, u.student_id, u.faculty, u.major, u.phone,
                       c.club_name, a.status
                FROM applications a
                JOIN users u ON a.user_id = u.id
                JOIN clubs c ON a.club_id = c.id';
        if ($role === 'admin') {
            $stmt = $this->db->prepare($sql . ' ORDER BY a.id DESC LIMIT ? OFFSET ?');
            $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt = $this->db->prepare(
            $sql . ' WHERE c.president_id = ? ORDER BY a.id DESC LIMIT ? OFFSET ?'
        );
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** นับจำนวนรายการคำขอทั้งหมดตามบทบาทหลัก */
    public function countForManage(string $role, int $userId): int
    {
        if ($role === 'admin') {
            return (int)$this->db->query('SELECT COUNT(*) FROM applications')->fetchColumn();
        }
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM applications a JOIN clubs c ON a.club_id = c.id WHERE c.president_id = ?'
        );
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
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
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('UPDATE applications SET status = ? WHERE id = ?');
            $stmt->execute([$status, $appId]);

            if ($status === 'approved') {
                $stmtFetch = $this->db->prepare('SELECT club_id, user_id FROM applications WHERE id = ?');
                $stmtFetch->execute([$appId]);
                $app = $stmtFetch->fetch();

                if ($app) {
                    $stmtCheck = $this->db->prepare('SELECT COUNT(*) FROM club_members WHERE club_id = ? AND user_id = ?');
                    $stmtCheck->execute([$app['club_id'], $app['user_id']]);
                    if ((int)$stmtCheck->fetchColumn() === 0) {
                        $stmtInsert = $this->db->prepare('INSERT INTO club_members (club_id, user_id, role_id) VALUES (?, ?, 5)');
                        $stmtInsert->execute([$app['club_id'], $app['user_id']]);
                    }
                }
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
