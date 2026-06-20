<?php
namespace App\Models;

use App\Core\Model;

class Notification extends Model
{
    /** สร้างรายการแจ้งเตือนใหม่ */
    public function createNotification(int $userId, string $title, string $message): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO notifications (user_id, title, message, is_read) 
             VALUES (?, ?, ?, 0)'
        );
        return $stmt->execute([$userId, $title, $message]);
    }

    /** ดึงจำนวนการแจ้งเตือนที่ยังไม่ได้อ่าน */
    public function getUnreadCount(int $userId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    /** ดึงรายการแจ้งเตือนล่าสุดของ User */
    public function getLatestForUser(int $userId, int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM notifications 
             WHERE user_id = ? 
             ORDER BY id DESC LIMIT ?'
        );
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** ทำเครื่องหมายว่าอ่านแล้วทั้งหมดสำหรับผู้ใช้ */
    public function markAllAsRead(int $userId): bool
    {
        $stmt = $this->db->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0');
        return $stmt->execute([$userId]);
    }

    /** ทำเครื่องหมายว่าอ่านแล้วทีละรายการ */
    public function markAsRead(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare('UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?');
        return $stmt->execute([$id, $userId]);
    }
}
