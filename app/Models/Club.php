<?php
namespace App\Models;

use App\Core\Model;

class Club extends Model
{
    /** รายการชมรมทั้งหมด พร้อมจำนวนสมาชิกที่อนุมัติแล้ว (หน้าแรก) */
    public function allWithMemberCount(): array
    {
        $sql = 'SELECT c.*,
                    (SELECT COUNT(*) FROM applications
                     WHERE club_id = c.id AND status = "approved") AS current_members
                FROM clubs c
                WHERE c.status = "approved"';
        return $this->db->query($sql)->fetchAll();
    }

    /** ข้อมูลชมรมเดียว พร้อมชื่อประธานและจำนวนสมาชิก */
    public function findWithDetail(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, u.name AS pres_name,
                (SELECT COUNT(*) FROM applications
                 WHERE club_id = c.id AND status = "approved") AS current_members
             FROM clubs c
             LEFT JOIN users u ON c.president_id = u.id
             WHERE c.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** รายการชมรมสำหรับหน้าจัดการ (admin เห็นทั้งหมด, president เห็นเฉพาะของตน) แบบแบ่งหน้า */
    public function listForManage(string $role, int $userId, int $limit = 10, int $offset = 0): array
    {
        $sql = 'SELECT c.*, u.student_id AS pres_id, u.name AS pres_name
                FROM clubs c
                LEFT JOIN users u ON c.president_id = u.id';
        if ($role === 'admin') {
            $stmt = $this->db->prepare($sql . ' LIMIT ? OFFSET ?');
            $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt = $this->db->prepare($sql . ' WHERE c.president_id = ? LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** นับจำนวนชมรมทั้งหมดสำหรับหน้าจัดการ */
    public function countForManage(string $role, int $userId): int
    {
        if ($role === 'admin') {
            return (int)$this->db->query('SELECT COUNT(*) FROM clubs')->fetchColumn();
        }
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM clubs WHERE president_id = ?');
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO clubs (club_name, description, max_members, club_logo, qr_code, status)
             VALUES (?, ?, ?, ?, ?, "approved")'
        );
        $stmt->execute([
            $data['club_name'], $data['description'], $data['max_members'],
            $data['club_logo'], $data['qr_code'],
        ]);
    }

    /** ตรวจว่า user เป็นประธานของชมรมนี้หรือไม่ */
    public function isPresident(int $clubId, int $userId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM clubs WHERE id = ? AND president_id = ?'
        );
        $stmt->execute([$clubId, $userId]);
        return (bool) $stmt->fetch();
    }

    /**
     * อัปเดตข้อมูลชมรมแบบ dynamic (เฉพาะ field ที่ส่งมา)
     * $fields = ['club_name' => ..., 'description' => ..., ...]
     */
    public function update(int $clubId, array $fields): void
    {
        $columns = [];
        $params = [];
        foreach ($fields as $col => $val) {
            if ($val === null && $col === 'president_id') {
                $columns[] = 'president_id = NULL';
                continue;
            }
            $columns[] = "$col = ?";
            $params[] = $val;
        }
        $params[] = $clubId;
        $sql = 'UPDATE clubs SET ' . implode(', ', $columns) . ' WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM applications WHERE club_id = ?')->execute([$id]);
        $this->db->prepare('DELETE FROM clubs WHERE id = ?')->execute([$id]);
    }

    /** ข้อมูลความจุชมรม (max + จำนวนปัจจุบัน) สำหรับเช็คก่อนสมัคร */
    public function capacity(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT max_members,
                (SELECT COUNT(*) FROM applications
                 WHERE club_id = ? AND status = "approved") AS current_members
             FROM clubs WHERE id = ?'
        );
        $stmt->execute([$id, $id]);
        return $stmt->fetch() ?: null;
    }

    /** ดึงรายชื่อชมรมที่ผู้ใช้งานเข้าร่วม (สถานะอนุมัติแล้ว) */
    public function getJoinedClubs(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, r.role_name AS member_role,
                (SELECT COUNT(*) FROM applications WHERE club_id = c.id AND status = "approved") AS current_members
             FROM club_members cm 
             JOIN clubs c ON cm.club_id = c.id 
             JOIN roles r ON cm.role_id = r.id
             WHERE cm.user_id = ? AND c.status = "approved"'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /** ดึงรายการสมัครชมรมที่รอการอนุมัติ (pending) */
    public function getPendingApplications(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, a.status, a.id AS app_id,
                (SELECT COUNT(*) FROM applications WHERE club_id = c.id AND status = "approved") AS current_members
             FROM applications a 
             JOIN clubs c ON a.club_id = c.id 
             WHERE a.user_id = ? AND a.status = "pending" AND c.status = "approved"'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
