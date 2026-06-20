<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Image;
use App\Models\Gallery;
use App\Models\Club;

class GalleryController extends Controller
{
    public function manage(): void
    {
        $this->requireRole('admin', 'president');
        
        $galModel = new Gallery;
        $clubModel = new Club;
        
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 12;
        $offset = ($currentPage - 1) * $limit;

        $gallery = [];
        $clubId = null;
        $totalItems = 0;
        
        if ($role === 'president') {
            $db = \App\Core\Database::instance();
            $stmt = $db->prepare('SELECT id FROM clubs WHERE president_id = ?');
            $stmt->execute([$userId]);
            $myClub = $stmt->fetch();
            if ($myClub) {
                $clubId = (int)$myClub['id'];
                $totalItems = $galModel->countForClub($clubId);
                $gallery = $galModel->forClubPaginated($clubId, $limit, $offset);
            }
        } else {
            $totalItems = $galModel->countAll();
            $gallery = $galModel->allPaginated($limit, $offset);
        }
        
        $totalPages = (int)ceil($totalItems / $limit);
        $clubsList = ($role === 'admin') ? $clubModel->allWithMemberCount() : [];
        
        $this->view('gallery/manage', [
            'gallery' => $gallery,
            'clubsList' => $clubsList,
            'role' => $role,
            'clubId' => $clubId,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ], 'backoffice');
    }

    public function store(): void
    {
        $this->requireRole('admin', 'president');
        
        $galModel = new Gallery;
        
        $title = trim($_POST['title'] ?? '');
        $clubId = !empty($_POST['club_id']) ? (int)$_POST['club_id'] : null;
        
        if ($title === '' || !isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $this->flash('กรุณากรอกชื่อกิจกรรมและอัปโหลดภาพให้ถูกต้อง');
            $this->redirect('/gallery/manage');
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
        
        // อัปโหลดรูปภาพ
        $imagePath = Image::uploadResized($_FILES['photo'], 'gallery');
        if ($imagePath === '') {
            $this->flash('เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ (ขนาดใหญ่เกินไป หรือไม่ใช่รูปภาพ)');
            $this->redirect('/gallery/manage');
        }
        
        $galModel->create([
            'club_id' => $clubId,
            'title' => $title,
            'image_path' => $imagePath
        ]);
        
        $this->flash('อัปโหลดรูปภาพกิจกรรมเข้าคลังแกลเลอรีสำเร็จแล้ว');
        $this->redirect('/gallery/manage');
    }

    public function delete(string $id): void
    {
        $this->requireRole('admin', 'president');
        
        $galId = (int)$id;
        $galModel = new Gallery;
        $clubModel = new Club;
        
        $photo = $galModel->find($galId);
        if (!$photo) {
            $this->redirect('/gallery/manage');
        }
        
        // เช็คสิทธิ์
        $canDelete = false;
        if ($_SESSION['role'] === 'admin') {
            $canDelete = true;
        } else {
            if ($photo['club_id'] !== null) {
                $canDelete = $clubModel->isPresident((int)$photo['club_id'], $_SESSION['user_id']);
            }
        }
        
        if (!$canDelete) {
            $this->redirect('/gallery/manage');
        }
        
        // ลบไฟล์รูปภาพออกจากโฟลเดอร์ uploads/
        if (!empty($photo['image_path']) && file_exists(BASE_PATH . '/' . $photo['image_path'])) {
            @unlink(BASE_PATH . '/' . $photo['image_path']);
        }
        
        $galModel->delete($galId);
        $this->flash('ลบรูปภาพกิจกรรมออกจากแกลเลอรีสำเร็จแล้ว');
        $this->redirect('/gallery/manage');
    }
}
