<?php
namespace App\Models;

use App\Core\Model;

class Faculty extends Model
{
    /** ดึงรายการคณะทั้งหมด */
    public function all(): array
    {
        return $this->db->query('SELECT * FROM faculties ORDER BY id ASC')->fetchAll();
    }

    /** ดึงรายการคณะทั้งหมดแบบแบ่งหน้า */
    public function allPaginated(int $limit, int $offset): array
    {
        $stmt = $this->db->prepare('SELECT * FROM faculties ORDER BY id ASC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** นับจำนวนคณะทั้งหมด */
    public function countAll(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM faculties')->fetchColumn();
    }

    /** ค้นหาคณะโดย ID */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM faculties WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** ดึงรายการคณะพร้อมกับสาขาวิชาทั้งหมดในลักษณะของ Nested Array */
    public function allWithMajors(): array
    {
        // ดึงคณะทั้งหมด
        $faculties = $this->all();
        
        // ดึงสาขาทั้งหมด
        $stmt = $this->db->query('SELECT * FROM majors ORDER BY faculty_id ASC, id ASC');
        $allMajors = $stmt->fetchAll();
        
        // Map ข้อมูลจัดกลุ่มคณะและสาขา
        $result = [];
        foreach ($faculties as $fac) {
            $result[$fac['name']] = [];
        }
        
        foreach ($allMajors as $major) {
            // ค้นหาชื่อคณะของสาขานี้
            foreach ($faculties as $fac) {
                if ((int)$fac['id'] === (int)$major['faculty_id']) {
                    $result[$fac['name']][] = $major['name'];
                    break;
                }
            }
        }
        
        return $result;
    }

    public function create(string $name): bool
    {
        $stmt = $this->db->prepare('INSERT INTO faculties (name) VALUES (?)');
        return $stmt->execute([$name]);
    }

    public function update(int $id, string $name): bool
    {
        $stmt = $this->db->prepare('UPDATE faculties SET name = ? WHERE id = ?');
        return $stmt->execute([$name, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM faculties WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
