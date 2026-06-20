<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Image;
use App\Models\Announcement;
use App\Models\Club;

class AnnouncementController extends Controller
{
    public function manage(): void
    {
        $this->requireRole('admin', 'president');
        
        $annModel = new Announcement;
        $clubModel = new Club;
        
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        $announcements = [];
        $clubId = null;
        $totalItems = 0;
        
        if ($role === 'president') {
            // ดึงชมรมของประธาน
            $db = \App\Core\Database::instance();
            $stmt = $db->prepare('SELECT id FROM clubs WHERE president_id = ?');
            $stmt->execute([$userId]);
            $myClub = $stmt->fetch();
            
            if ($myClub) {
                $clubId = (int)$myClub['id'];
                $totalItems = $annModel->countForClub($clubId);
                $announcements = $annModel->forClubPaginated($clubId, $limit, $offset);
            }
        } else {
            // แอดมินจัดการได้หมด
            $totalItems = $annModel->countAll();
            $announcements = $annModel->allPaginated($limit, $offset);
        }
        
        $totalPages = (int)ceil($totalItems / $limit);
        $clubsList = ($role === 'admin') ? $clubModel->allWithMemberCount() : [];
        
        $this->view('announcements/manage', [
            'announcements' => $announcements,
            'clubsList' => $clubsList,
            'role' => $role,
            'clubId' => $clubId,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit
        ], 'backoffice');
    }

    public function store(): void
    {
        $this->requireRole('admin', 'president');
        
        $annModel = new Announcement;
        
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $clubId = !empty($_POST['club_id']) ? (int)$_POST['club_id'] : null;
        
        if ($title === '' || $content === '') {
            $this->flash('กรุณากรอกข้อมูลให้ครบถ้วน');
            $this->redirect('/backoffice/announcements');
        }
        
        // ตรวจสอบสิทธิ์
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
        
        $thumbnail = '';
        if (isset($_FILES['thumbnail'])) {
            $thumbnail = Image::uploadResized($_FILES['thumbnail'], 'news');
        }
        
        $annModel->create([
            'title' => $title,
            'content' => $content,
            'thumbnail' => $thumbnail ?: null,
            'club_id' => $clubId,
            'author_id' => $_SESSION['user_id']
        ]);
        
        $this->flash('เพิ่มข่าวประชาสัมพันธ์สำเร็จแล้ว');
        $this->redirect('/backoffice/announcements');
    }

    public function update(): void
    {
        $this->requireRole('admin', 'president');
        
        $annModel = new Announcement;
        $clubModel = new Club;
        
        $id = (int)$_POST['id'];
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        
        $ann = $annModel->find($id);
        if (!$ann) {
            $this->redirect('/backoffice/announcements');
        }
        
        // เช็คสิทธิ์
        $canEdit = false;
        if ($_SESSION['role'] === 'admin') {
            $canEdit = true;
        } else {
            if ($ann['club_id'] !== null) {
                $canEdit = $clubModel->isPresident((int)$ann['club_id'], $_SESSION['user_id']);
            }
        }
        
        if (!$canEdit) {
            $this->redirect('/backoffice/announcements');
        }
        
        $thumbnail = null;
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $thumbnail = Image::uploadResized($_FILES['thumbnail'], 'news');
        }
        
        $annModel->update($id, [
            'title' => $title,
            'content' => $content,
            'thumbnail' => $thumbnail
        ]);
        
        $this->flash('แก้ไขข่าวประชาสัมพันธ์สำเร็จแล้ว');
        $this->redirect('/backoffice/announcements');
    }

    public function detail(string $id): void
    {
        $annId = (int)$id;
        $annModel = new Announcement;
        $ann = $annModel->find($annId);
        
        if (!$ann) {
            $this->redirect('/');
        }
        
        $this->view('announcements/detail', [
            'announcement' => $ann,
            'pageTitle' => $ann['title']
        ]);
    }

    public function delete(string $id): void
    {
        $this->requireRole('admin', 'president');
        
        $annId = (int)$id;
        $annModel = new Announcement;
        $clubModel = new Club;
        
        $ann = $annModel->find($annId);
        if (!$ann) {
            $this->redirect('/backoffice/announcements');
        }
        
        // เช็คสิทธิ์
        $canDelete = false;
        if ($_SESSION['role'] === 'admin') {
            $canDelete = true;
        } else {
            if ($ann['club_id'] !== null) {
                $canDelete = $clubModel->isPresident((int)$ann['club_id'], $_SESSION['user_id']);
            }
        }
        
        if (!$canDelete) {
            $this->redirect('/backoffice/announcements');
        }
        
        $annModel->delete($annId);
        $this->flash('ลบข่าวประชาสัมพันธ์เรียบร้อยแล้ว');
        $this->redirect('/backoffice/announcements');
    }
}
