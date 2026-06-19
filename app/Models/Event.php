<?php
namespace App\Models;

use App\Core\Model;

class Event extends Model
{
    /** ดึงรายการปฏิทินกิจกรรมทั้งหมด */
    public function all(): array
    {
        $sql = 'SELECT e.*, c.club_name 
                FROM events e
                LEFT JOIN clubs c ON e.club_id = c.id
                ORDER BY e.event_date ASC, e.start_time ASC';
        return $this->db->query($sql)->fetchAll();
    }

    /** ดึงรายการกิจกรรมสำหรับชมรมเฉพาะเจาะจง */
    public function forClub(int $clubId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM events 
             WHERE club_id = ?
             ORDER BY event_date ASC, start_time ASC'
        );
        $stmt->execute([$clubId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM events WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO events (club_id, title, description, event_date, start_time, end_time, location)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        return $stmt->execute([
            $data['club_id'] ?? null,
            $data['title'],
            $data['description'] ?? null,
            $data['event_date'],
            $data['start_time'] ?? null,
            $data['end_time'] ?? null,
            $data['location'] ?? null
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE events SET title = ?, description = ?, event_date = ?, start_time = ?, end_time = ?, location = ?
             WHERE id = ?'
        );
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['event_date'],
            $data['start_time'] ?? null,
            $data['end_time'] ?? null,
            $data['location'] ?? null,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM events WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
