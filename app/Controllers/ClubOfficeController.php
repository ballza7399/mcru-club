<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Image;
use App\Models\Club;
use App\Models\User;
use App\Models\Application;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Role;

class ClubOfficeController extends Controller
{
    protected int $clubId;
    protected array $club;

    public function __construct()
    {
        $this->requireAuth();
        
        $role = $_SESSION['role'] ?? '';
        $userId = $_SESSION['user_id'] ?? 0;
        
        if ($role === 'president') {
            // Find which club this user is president of
            $db = \App\Core\Database::instance();
            $stmt = $db->prepare('SELECT * FROM clubs WHERE president_id = ? AND status = "approved"');
            $stmt->execute([$userId]);
            $club = $stmt->fetch();
            if (!$club) {
                throw new \Exception('คุณไม่ได้รับอนุญาตให้เข้าถึงระบบนี้ หรือยังไม่มีชมรมที่ได้รับอนุมัติ', 403);
            }
            $this->clubId = (int)$club['id'];
            $this->club = $club;
        } elseif ($role === 'admin') {
            $clubId = isset($_GET['club_id']) ? (int)$_GET['club_id'] : 0;
            if ($clubId === 0) {
                // Default to first approved club
                $db = \App\Core\Database::instance();
                $club = $db->query('SELECT * FROM clubs WHERE status = "approved" LIMIT 1')->fetch();
                if ($club) {
                    $clubId = (int)$club['id'];
                }
            } else {
                $db = \App\Core\Database::instance();
                $stmt = $db->prepare('SELECT * FROM clubs WHERE id = ?');
                $stmt->execute([$clubId]);
                $club = $stmt->fetch();
            }
            
            if (!$club) {
                throw new \Exception('ไม่พบข้อมูลชมรมที่ระบุ', 404);
            }
            
            $this->clubId = $clubId;
            $this->club = $club;
        } else {
            $this->redirect('/');
        }
    }

    protected function view(string $template, array $data = [], string $layout = 'cluboffice'): void
    {
        $data['club'] = $this->club;
        parent::view($template, $data, $layout);
    }

    public function dashboard(): void
    {
        $db = \App\Core\Database::instance();
        
        // Members count
        $stmtMembers = $db->prepare('SELECT COUNT(*) FROM club_members WHERE club_id = ?');
        $stmtMembers->execute([$this->clubId]);
        $totalMembers = (int)$stmtMembers->fetchColumn();

        // Unique faculties count
        $stmtFaculties = $db->prepare('
            SELECT COUNT(DISTINCT u.faculty) 
            FROM club_members cm 
            JOIN users u ON cm.user_id = u.id 
            WHERE cm.club_id = ? AND u.faculty IS NOT NULL AND u.faculty != ""
        ');
        $stmtFaculties->execute([$this->clubId]);
        $uniqueFaculties = (int)$stmtFaculties->fetchColumn();
        
        // Pending applications
        $stmtApps = $db->prepare('SELECT COUNT(*) FROM applications WHERE club_id = ? AND status = "pending"');
        $stmtApps->execute([$this->clubId]);
        $totalApps = (int)$stmtApps->fetchColumn();
        
        // Events
        $stmtEvents = $db->prepare('SELECT COUNT(*) FROM events WHERE club_id = ?');
        $stmtEvents->execute([$this->clubId]);
        $totalEvents = (int)$stmtEvents->fetchColumn();
        
        // Announcements
        $stmtNews = $db->prepare('SELECT COUNT(*) FROM announcements WHERE club_id = ?');
        $stmtNews->execute([$this->clubId]);
        $totalNews = (int)$stmtNews->fetchColumn();

        $this->view('cluboffice/dashboard', [
            'totalMembers' => $totalMembers,
            'uniqueFaculties' => $uniqueFaculties,
            'totalApps' => $totalApps,
            'totalEvents' => $totalEvents,
            'totalNews' => $totalNews,
            'pageTitle' => 'แผงควบคุมชมรม'
        ]);
    }

    public function submitVerification(): void
    {
        $clubModel = new Club;
        $clubModel->update($this->clubId, [
            'member_verification_status' => 'pending',
            'member_verification_comment' => NULL
        ]);
        
        $this->flash('ส่งรายชื่อสมาชิกให้เจ้าหน้าที่ตรวจสอบความหลากหลายของคณะเรียบร้อยแล้ว โปรดรอการพิจารณา');
        $this->redirect('/cluboffice');
    }

    public function info(): void
    {
        $this->view('cluboffice/info', [
            'pageTitle' => 'แก้ไขข้อมูลชมรม'
        ]);
    }

    public function updateInfo(): void
    {
        $fields = [
            'description' => $_POST['description'] ?? '',
            'max_members' => (int)($_POST['max_members'] ?? 50),
        ];

        // System Admin can update club name as well
        if ($_SESSION['role'] === 'admin') {
            $fields['club_name'] = $_POST['club_name'] ?? '';
        }

        $logo = $this->uploadFile('logo');
        $qr   = $this->uploadFile('qr_code');
        if ($logo !== '') $fields['club_logo'] = $logo;
        if ($qr !== '')   $fields['qr_code']   = $qr;

        (new Club)->update($this->clubId, $fields);
        
        // Refresh session club info
        $db = \App\Core\Database::instance();
        $stmt = $db->prepare('SELECT * FROM clubs WHERE id = ?');
        $stmt->execute([$this->clubId]);
        $this->club = $stmt->fetch();

        $this->flash('บันทึกการแก้ไขข้อมูลชมรมสำเร็จแล้ว');
        
        $clubIdQuery = '?club_id=' . $this->clubId;
        $this->redirect('/cluboffice/info' . $clubIdQuery);
    }

    public function members(): void
    {
        $roleModel = new Role;
        
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        $totalMembers = $roleModel->countClubMembers($this->clubId);
        $totalPages = (int)ceil($totalMembers / $limit);

        $members = $roleModel->getClubMembersPaginated($this->clubId, $limit, $offset);
        $roles = $roleModel->listClubRoles($this->clubId);

        $this->view('cluboffice/members', [
            'members' => $members,
            'roles' => $roles,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'pageTitle' => 'จัดการสมาชิก'
        ]);
    }

    public function assignRole(): void
    {
        $userId = (int)($_POST['user_id'] ?? 0);
        $roleId = !empty($_POST['role_id']) ? (int)$_POST['role_id'] : null;
        
        (new Role)->assignMemberRole($this->clubId, $userId, $roleId);
        
        $this->flash('อัปเดตตำแหน่งสมาชิกสำเร็จแล้ว');
        $clubIdQuery = '?club_id=' . $this->clubId;
        $this->redirect('/cluboffice/members' . $clubIdQuery);
    }

    public function removeMember(string $userId): void
    {
        $uId = (int)$userId;
        (new Role)->removeClubMember($this->clubId, $uId);
        
        $this->flash('คัดสมาชิกออกจากชมรมเรียบร้อยแล้ว');
        $clubIdQuery = '?club_id=' . $this->clubId;
        $this->redirect('/cluboffice/members' . $clubIdQuery);
    }

    public function applications(): void
    {
        $appModel = new Application;

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        // Fetch paginated applications for this club
        $db = \App\Core\Database::instance();
        $stmtCount = $db->prepare('SELECT COUNT(*) FROM applications WHERE club_id = ?');
        $stmtCount->execute([$this->clubId]);
        $totalApps = (int)$stmtCount->fetchColumn();
        $totalPages = (int)ceil($totalApps / $limit);

        $stmtApps = $db->prepare(
            'SELECT a.id, a.status, u.name, u.student_id, u.email, u.phone, u.faculty, u.major 
             FROM applications a
             JOIN users u ON a.user_id = u.id
             WHERE a.club_id = ?
             ORDER BY a.id DESC LIMIT ? OFFSET ?'
        );
        $stmtApps->bindValue(1, $this->clubId, \PDO::PARAM_INT);
        $stmtApps->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmtApps->bindValue(3, $offset, \PDO::PARAM_INT);
        $stmtApps->execute();
        $apps = $stmtApps->fetchAll();

        $this->view('cluboffice/applications', [
            'apps' => $apps,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'pageTitle' => 'จัดการผู้สมัคร'
        ]);
    }

    public function approveApplication(string $id): void
    {
        $appId = (int)$id;
        $appModel = new Application;
        
        // Safety check
        if ($appModel->clubIdOf($appId) === $this->clubId) {
            $appModel->updateStatus($appId, 'approved');
            
            // Add user into club members table automatically if not exists
            $appDetail = $appModel->find($appId);
            if ($appDetail) {
                $db = \App\Core\Database::instance();
                
                // Add role_id 5 (member) as default
                $stmtCheck = $db->prepare('SELECT COUNT(*) FROM club_members WHERE club_id = ? AND user_id = ?');
                $stmtCheck->execute([$this->clubId, $appDetail['user_id']]);
                if ((int)$stmtCheck->fetchColumn() === 0) {
                    $stmtInsert = $db->prepare('INSERT INTO club_members (club_id, user_id, role_id) VALUES (?, ?, 5)');
                    $stmtInsert->execute([$this->clubId, $appDetail['user_id']]);
                }
            }
            
            $this->flash('อนุมัติการรับสมัครเข้าชมรมเรียบร้อยแล้ว');
        }
        
        $clubIdQuery = '?club_id=' . $this->clubId;
        $this->redirect('/cluboffice/applications' . $clubIdQuery);
    }

    public function rejectApplication(string $id): void
    {
        $appId = (int)$id;
        $appModel = new Application;
        
        // Safety check
        if ($appModel->clubIdOf($appId) === $this->clubId) {
            $appModel->updateStatus($appId, 'rejected');
            $this->flash('ปฏิเสธคำสมัครเข้าชมรมเรียบร้อยแล้ว');
        }
        
        $clubIdQuery = '?club_id=' . $this->clubId;
        $this->redirect('/cluboffice/applications' . $clubIdQuery);
    }

    public function announcements(): void
    {
        $annModel = new Announcement;

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        $totalNews = $annModel->countForClub($this->clubId);
        $totalPages = (int)ceil($totalNews / $limit);

        $announcements = $annModel->forClubPaginated($this->clubId, $limit, $offset);

        $this->view('cluboffice/announcements', [
            'announcements' => $announcements,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'pageTitle' => 'จัดการข่าวประชาสัมพันธ์'
        ]);
    }

    public function storeAnnouncement(): void
    {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        
        if ($title === '') {
            $this->flash('กรุณากรอกหัวข้อข่าว');
            $this->redirect('/cluboffice/announcements?club_id=' . $this->clubId);
            return;
        }

        $thumbnail = $this->uploadFile('thumbnail');

        (new Announcement)->create([
            'title' => $title,
            'content' => $content,
            'thumbnail' => $thumbnail ?: null,
            'club_id' => $this->clubId,
            'author_id' => $_SESSION['user_id']
        ]);

        $this->flash('เพิ่มข่าวประชาสัมพันธ์ชมรมสำเร็จแล้ว');
        $this->redirect('/cluboffice/announcements?club_id=' . $this->clubId);
    }

    public function updateAnnouncement(): void
    {
        $id = (int)$_POST['id'];
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        
        $annModel = new Announcement;
        $news = $annModel->find($id);
        
        if (!$news || (int)$news['club_id'] !== $this->clubId) {
            throw new \Exception('คุณไม่มีสิทธิ์แก้ไขข่าวสารนี้', 403);
        }

        $thumbnail = $this->uploadFile('thumbnail');

        $annModel->update($id, [
            'title' => $title,
            'content' => $content,
            'thumbnail' => $thumbnail ?: null
        ]);

        $this->flash('แก้ไขข้อมูลข่าวประชาสัมพันธ์สำเร็จแล้ว');
        $this->redirect('/cluboffice/announcements?club_id=' . $this->clubId);
    }

    public function deleteAnnouncement(string $id): void
    {
        $annId = (int)$id;
        $annModel = new Announcement;
        $news = $annModel->find($annId);
        
        if ($news && (int)$news['club_id'] === $this->clubId) {
            $annModel->delete($annId);
            $this->flash('ลบข่าวประชาสัมพันธ์เรียบร้อยแล้ว');
        }

        $this->redirect('/cluboffice/announcements?club_id=' . $this->clubId);
    }

    public function events(): void
    {
        $eventModel = new Event;

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        $totalEvents = $eventModel->countForClub($this->clubId);
        $totalPages = (int)ceil($totalEvents / $limit);

        $events = $eventModel->forClubPaginated($this->clubId, $limit, $offset);

        $this->view('cluboffice/events', [
            'events' => $events,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'pageTitle' => 'จัดการปฏิทินกิจกรรม'
        ]);
    }

    public function storeEvent(): void
    {
        $title = trim($_POST['title'] ?? '');
        $date  = $_POST['event_date'] ?? '';
        
        if ($title === '' || $date === '') {
            $this->flash('กรุณากรอกหัวข้อและวันที่จัดกิจกรรม');
            $this->redirect('/cluboffice/events?club_id=' . $this->clubId);
            return;
        }

        (new Event)->create([
            'club_id'     => $this->clubId,
            'title'       => $title,
            'description' => $_POST['description'] ?? null,
            'event_date'  => $date,
            'start_time'  => !empty($_POST['start_time']) ? $_POST['start_time'] : null,
            'end_time'    => !empty($_POST['end_time']) ? $_POST['end_time'] : null,
            'location'    => $_POST['location'] ?? null,
        ]);

        $this->flash('เพิ่มกิจกรรมชมรมเข้าปฏิทินสำเร็จแล้ว');
        $this->redirect('/cluboffice/events?club_id=' . $this->clubId);
    }

    public function updateEvent(): void
    {
        $id = (int)$_POST['id'];
        $eventModel = new Event;
        $ev = $eventModel->find($id);

        if (!$ev || (int)$ev['club_id'] !== $this->clubId) {
            throw new \Exception('คุณไม่มีสิทธิ์แก้ไขกิจกรรมนี้', 403);
        }

        $eventModel->update($id, [
            'title'       => $_POST['title'],
            'description' => $_POST['description'] ?? null,
            'event_date'  => $_POST['event_date'],
            'start_time'  => !empty($_POST['start_time']) ? $_POST['start_time'] : null,
            'end_time'    => !empty($_POST['end_time']) ? $_POST['end_time'] : null,
            'location'    => $_POST['location'] ?? null,
        ]);

        $this->flash('แก้ไขข้อมูลปฏิทินกิจกรรมสำเร็จแล้ว');
        $this->redirect('/cluboffice/events?club_id=' . $this->clubId);
    }

    public function deleteEvent(string $id): void
    {
        $evId = (int)$id;
        $eventModel = new Event;
        $ev = $eventModel->find($evId);

        if ($ev && (int)$ev['club_id'] === $this->clubId) {
            $eventModel->delete($evId);
            $this->flash('ลบกิจกรรมออกจากปฏิทินเรียบร้อยแล้ว');
        }

        $this->redirect('/cluboffice/events?club_id=' . $this->clubId);
    }

    public function gallery(): void
    {
        $galModel = new Gallery;

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(12, min(96, (int)$_GET['limit'])) : 12; // grid view defaults to 12
        $offset = ($currentPage - 1) * $limit;

        $totalImages = $galModel->countForClub($this->clubId);
        $totalPages = (int)ceil($totalImages / $limit);

        $images = $galModel->forClubPaginated($this->clubId, $limit, $offset);

        $this->view('cluboffice/gallery', [
            'images' => $images,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'pageTitle' => 'คลังแกลเลอรีรูปภาพ'
        ]);
    }

    public function storeGallery(): void
    {
        $title = trim($_POST['title'] ?? '');
        if ($title === '' || !isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $this->flash('กรุณากรอกคำอธิบายและเลือกภาพให้ถูกต้อง');
            $this->redirect('/cluboffice/gallery?club_id=' . $this->clubId);
            return;
        }

        $imagePath = Image::uploadResized($_FILES['image'], 'gallery');
        if (!$imagePath) {
            $this->flash('เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ (ไม่ใช่ไฟล์รูปภาพ หรือขนาดใหญ่เกินไป)');
            $this->redirect('/cluboffice/gallery?club_id=' . $this->clubId);
            return;
        }

        (new Gallery)->create([
            'club_id'    => $this->clubId,
            'title'      => $title,
            'image_path' => $imagePath
        ]);

        $this->flash('อัปโหลดภาพกิจกรรมชมรมสำเร็จแล้ว');
        $this->redirect('/cluboffice/gallery?club_id=' . $this->clubId);
    }

    public function deleteGallery(string $id): void
    {
        $galId = (int)$id;
        $galModel = new Gallery;
        $img = $galModel->find($galId);

        if ($img && (int)$img['club_id'] === $this->clubId) {
            $galModel->delete($galId);
            $this->flash('ลบรูปภาพกิจกรรมสำเร็จแล้ว');
        }

        $this->redirect('/cluboffice/gallery?club_id=' . $this->clubId);
    }

    private function uploadFile(string $field): string
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return '';
        }
        return Image::uploadResized($_FILES[$field], $field);
    }
}
