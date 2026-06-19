<?php
namespace App\Models;

use App\Core\Model;

class Gallery extends Model
{
    /** ดึงรายการรูปภาพกิจกรรมในระบบ */
    public function all(int $limit = 12): array
    {
        $stmt = $this->db->prepare(
            'SELECT g.*, c.club_name 
             FROM gallery g
             LEFT JOIN clubs c ON g.club_id = c.id
             ORDER BY g.created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** ดึงรายการรูปภาพกิจกรรมของชมรมเฉพาะเจาะจง */
    public function forClub(int $clubId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM gallery 
             WHERE club_id = ?
             ORDER BY created_at DESC'
        );
        $stmt->execute([$clubId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM gallery WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO gallery (club_id, title, image_path)
             VALUES (?, ?, ?)'
        );
        return $stmt->execute([
            $data['club_id'] ?? null,
            $data['title'],
            $data['image_path']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM gallery WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
