<?php
namespace App\Models;

use App\Core\Model;

class Major extends Model
{
    /** ดึงรายการสาขาวิชาทั้งหมด พร้อมชื่อคณะ */
    public function all(): array
    {
        return $this->db->query(
            'SELECT m.*, f.name AS faculty_name 
             FROM majors m
             JOIN faculties f ON m.faculty_id = f.id
             ORDER BY m.faculty_id ASC, m.id ASC'
        )->fetchAll();
    }

    /** ดึงสาขาวิชาเฉพาะของคณะที่กำหนด */
    public function forFaculty(int $facultyId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM majors WHERE faculty_id = ? ORDER BY id ASC');
        $stmt->execute([$facultyId]);
        return $stmt->fetchAll();
    }

    /** ค้นหาสาขาวิชาโดย ID */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM majors WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $facultyId, string $name): bool
    {
        $stmt = $this->db->prepare('INSERT INTO majors (faculty_id, name) VALUES (?, ?)');
        return $stmt->execute([$facultyId, $name]);
    }

    public function update(int $id, string $name): bool
    {
        $stmt = $this->db->prepare('UPDATE majors SET name = ? WHERE id = ?');
        return $stmt->execute([$name, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM majors WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
