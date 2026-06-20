<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Event;
use App\Models\Club;

class EventController extends Controller
{
    public function manage(): void
    {
        $this->requireRole('admin', 'president');
        
        $eventModel = new Event;
        $clubModel = new Club;
        
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        
        $events = [];
        $clubId = null;
        
        if ($role === 'president') {
            $db = \App\Core\Database::instance();
            $stmt = $db->prepare('SELECT id FROM clubs WHERE president_id = ?');
            $stmt->execute([$userId]);
            $myClub = $stmt->fetch();
            if ($myClub) {
                $clubId = (int)$myClub['id'];
                $events = $eventModel->forClub($clubId);
            }
        } else {
            $events = $eventModel->all();
        }
        
        $clubsList = ($role === 'admin') ? $clubModel->allWithMemberCount() : [];
        
        $this->view('events/manage', [
            'events' => $events,
            'clubsList' => $clubsList,
            'role' => $role,
            'clubId' => $clubId
        ], 'backoffice');
    }

    public function store(): void
    {
        $this->requireRole('admin', 'president');
        
        $eventModel = new Event;
        
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = $_POST['event_date'] ?? '';
        $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
        $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : null;
        $location = trim($_POST['location'] ?? '');
        $clubId = !empty($_POST['club_id']) ? (int)$_POST['club_id'] : null;
        
        if ($title === '' || $event_date === '') {
            $this->flash('กรุณากรอกข้อมูลหัวข้อกิจกรรมและวันที่ให้ครบถ้วน');
            $this->redirect('/events/manage');
        }
        
        // ตรวจสอบสิทธิ์ประธานชมรม
        if ($_SESSION['role'] === 'president') {
            $db = \App\Core\Database::instance();
            $stmt = $db->prepare('SELECT id FROM clubs WHERE president_id = ?');
            $stmt->execute([$_SESSION['user_id']]);
            $myClub = $stmt->fetch();
            if (!$myClub) {
                $this->redirect('/');
            }
            $clubId = (int)$myClub['id'];
        }
        
        $eventModel->create([
            'club_id' => $clubId,
            'title' => $title,
            'description' => $description,
            'event_date' => $event_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'location' => $location
        ]);
        
        $this->flash('เพิ่มกิจกรรมใหม่เข้าสู่ปฏิทินสำเร็จแล้ว');
        $this->redirect('/events/manage');
    }

    public function update(): void
    {
        $this->requireRole('admin', 'president');
        
        $eventModel = new Event;
        $clubModel = new Club;
        
        $id = (int)$_POST['id'];
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = $_POST['event_date'] ?? '';
        $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
        $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : null;
        $location = trim($_POST['location'] ?? '');
        
        $event = $eventModel->find($id);
        if (!$event) {
            $this->redirect('/events/manage');
        }
        
        // เช็คสิทธิ์
        $canEdit = false;
        if ($_SESSION['role'] === 'admin') {
            $canEdit = true;
        } else {
            if ($event['club_id'] !== null) {
                $canEdit = $clubModel->isPresident((int)$event['club_id'], $_SESSION['user_id']);
            }
        }
        
        if (!$canEdit) {
            $this->redirect('/events/manage');
        }
        
        $eventModel->update($id, [
            'title' => $title,
            'description' => $description,
            'event_date' => $event_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'location' => $location
        ]);
        
        $this->flash('แก้ไขข้อมูลปฏิทินกิจกรรมสำเร็จแล้ว');
        $this->redirect('/events/manage');
    }

    public function delete(string $id): void
    {
        $this->requireRole('admin', 'president');
        
        $eventId = (int)$id;
        $eventModel = new Event;
        $clubModel = new Club;
        
        $event = $eventModel->find($eventId);
        if (!$event) {
            $this->redirect('/events/manage');
        }
        
        // เช็คสิทธิ์
        $canDelete = false;
        if ($_SESSION['role'] === 'admin') {
            $canDelete = true;
        } else {
            if ($event['club_id'] !== null) {
                $canDelete = $clubModel->isPresident((int)$event['club_id'], $_SESSION['user_id']);
            }
        }
        
        if (!$canDelete) {
            $this->redirect('/events/manage');
        }
        
        $eventModel->delete($eventId);
        $this->flash('ลบกิจกรรมออกจากปฏิทินเรียบร้อยแล้ว');
        $this->redirect('/events/manage');
    }
}
