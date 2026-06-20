<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    /** ดึงรายการแจ้งเตือนล่าสุดและจำนวนที่ยังไม่ได้อ่านเป็น JSON */
    public function list(): void
    {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        
        $model = new Notification;
        $unreadCount = $model->getUnreadCount($userId);
        $notifications = $model->getLatestForUser($userId, 10);

        // Format dates relative to now if helpful, or pass as is
        $formatted = [];
        foreach ($notifications as $n) {
            $formatted[] = [
                'id'         => (int)$n['id'],
                'title'      => e($n['title']),
                'message'    => e($n['message']),
                'is_read'    => (int)$n['is_read'],
                'created_at' => date('d/m/Y H:i', strtotime($n['created_at']))
            ];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success'      => true,
            'unread_count' => $unreadCount,
            'notifications'=> $formatted
        ]);
        exit;
    }

    /** ตั้งค่าอ่านแล้วทั้งหมด */
    public function markAllRead(): void
    {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        
        $model = new Notification;
        $success = $model->markAllAsRead($userId);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }

    /** ตั้งค่าอ่านแล้วเป็นรายข้อ */
    public function markRead(string $id): void
    {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        $notificationId = (int)$id;
        
        $model = new Notification;
        $success = $model->markAsRead($notificationId, $userId);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
}
