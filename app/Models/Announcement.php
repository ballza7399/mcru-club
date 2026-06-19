<?php
namespace App\Models;

use App\Core\Model;

class Announcement extends Model
{
    /** ดึงรายการข่าวสารประชาสัมพันธ์ทั้งหมด (ข่าวสารกลาง + ข่าวสารชมรม) */
    public function all(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            'SELECT a.*, c.club_name, u.name AS author_name 
             FROM announcements a
             LEFT JOIN clubs c ON a.club_id = c.id
             JOIN users u ON a.author_id = u.id
             ORDER BY a.created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** ดึงรายการข่าวสารเฉพาะของชมรม */
    public function forClub(int $clubId): array
    {
        $stmt = $this->db->prepare(
            'SELECT a.*, u.name AS author_name 
             FROM announcements a
             JOIN users u ON a.author_id = u.id
             WHERE a.club_id = ?
             ORDER BY a.created_at DESC'
        );
        $stmt->execute([$clubId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT a.*, c.club_name, u.name AS author_name 
             FROM announcements a
             LEFT JOIN clubs c ON a.club_id = c.id
             JOIN users u ON a.author_id = u.id
             WHERE a.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO announcements (title, content, thumbnail, club_id, author_id)
             VALUES (?, ?, ?, ?, ?)'
        );
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $data['thumbnail'] ?? null,
            $data['club_id'] ?? null,
            $data['author_id']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE announcements SET title = ?, content = ?, thumbnail = COALESCE(?, thumbnail)
             WHERE id = ?'
        );
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $data['thumbnail'] ?? null,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM announcements WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
