<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Club;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Gallery;

class HomeController extends Controller
{
    public function index(): void
    {
        $clubModel = new Club;
        $allClubs = $clubModel->allWithMemberCount();
        $clubs = array_slice($allClubs, 0, 6);
        
        $annModel = new Announcement;
        $announcements = $annModel->all(6);
        
        $eventModel = new Event;
        $events = $eventModel->all();
        
        $galModel = new Gallery;
        $gallery = $galModel->all(12);
        
        $myClubs = [];
        $myApplications = [];
        if (!empty($_SESSION['user_id']) && $_SESSION['role'] === 'student') {
            $userId = (int)$_SESSION['user_id'];
            $myClubs = $clubModel->getJoinedClubs($userId);
            $myApplications = $clubModel->getPendingApplications($userId);
        }
        
        $this->view('home/index', [
            'clubs'          => $clubs,
            'announcements'  => $announcements,
            'events'         => $events,
            'gallery'        => $gallery,
            'myClubs'        => $myClubs,
            'myApplications' => $myApplications
        ]);
    }

    public function backoffice(): void
    {
        $this->requireRole('admin', 'president');
        
        $role = $_SESSION['role'];
        $userId = $_SESSION['user_id'];
        
        $db = \App\Core\Database::instance();
        
        $totalClubs = 0;
        $pendingApps = 0;
        $totalAnnouncements = 0;
        $totalEvents = 0;
        
        if ($role === 'admin') {
            $totalClubs = (int)$db->query('SELECT COUNT(*) FROM clubs')->fetchColumn();
            $pendingApps = (int)$db->query('SELECT COUNT(*) FROM applications WHERE status = "pending"')->fetchColumn();
            $totalAnnouncements = (int)$db->query('SELECT COUNT(*) FROM announcements')->fetchColumn();
            $totalEvents = (int)$db->query('SELECT COUNT(*) FROM events')->fetchColumn();
        } else {
            $stmt = $db->prepare('SELECT id FROM clubs WHERE president_id = ?');
            $stmt->execute([$userId]);
            $myClub = $stmt->fetch();
            
            if ($myClub) {
                $clubId = (int)$myClub['id'];
                
                $stmt2 = $db->prepare('SELECT COUNT(*) FROM applications WHERE club_id = ? AND status = "pending"');
                $stmt2->execute([$clubId]);
                $pendingApps = (int)$stmt2->fetchColumn();
                
                $stmt3 = $db->prepare('SELECT COUNT(*) FROM announcements WHERE club_id = ?');
                $stmt3->execute([$clubId]);
                $totalAnnouncements = (int)$stmt3->fetchColumn();
                
                $stmt4 = $db->prepare('SELECT COUNT(*) FROM events WHERE club_id = ?');
                $stmt4->execute([$clubId]);
                $totalEvents = (int)$stmt4->fetchColumn();
                
                $totalClubs = 1;
            }
        }
        
        $this->view('home/backoffice', [
            'totalClubs' => $totalClubs,
            'pendingApps' => $pendingApps,
            'totalAnnouncements' => $totalAnnouncements,
            'totalEvents' => $totalEvents,
            'role' => $role,
            'pageTitle' => 'แผงควบคุมหลังบ้าน (Backoffice)'
        ], 'backoffice');
    }

    public function policyPage(): void
    {
        $db = \App\Core\Database::instance();
        $policies = $db->query("SELECT * FROM policies")->fetchAll();
        
        $this->view('home/policy', [
            'policies'  => $policies,
            'pageTitle' => 'เงื่อนไขข้อตกลงและนโยบายความเป็นส่วนตัว (TOS & Privacy Policy)'
        ]);
    }

    public function pdpa(): void
    {
        $this->requireRole('admin');
        
        $db = \App\Core\Database::instance();
        
        $policies = $db->query("SELECT * FROM policies")->fetchAll();
        
        $consents = $db->query("
            SELECT c.*, u.name, u.student_id 
            FROM user_consents c 
            JOIN users u ON c.user_id = u.id 
            ORDER BY c.consented_at DESC 
            LIMIT 100
        ")->fetchAll();
        
        $this->view('home/pdpa', [
            'policies'  => $policies,
            'consents'  => $consents,
            'activePage' => 'pdpa',
            'pageTitle' => 'จัดการนโยบายความยินยอม PDPA'
        ], 'backoffice');
    }

    public function pdpaUpdate(): void
    {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $policyKey = $_POST['policy_key'] ?? '';
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $version = trim($_POST['version'] ?? '1.0');
            
            if ($policyKey && $title && $content) {
                $db = \App\Core\Database::instance();
                try {
                    $stmt = $db->prepare("
                        UPDATE policies 
                        SET title = ?, content = ?, version = ? 
                        WHERE policy_key = ?
                    ");
                    $stmt->execute([$title, $content, $version, $policyKey]);
                    $this->flash('อัปเดตนโยบายความเป็นส่วนตัวและเวอร์ชันสำเร็จ นโยบายรุ่นใหม่จะมีผลบังคับให้ผู้ใช้ทุกคนกดยืนยอมใหม่อีกครั้ง');
                } catch (\Exception $e) {
                    $this->flash('เกิดข้อผิดพลาด: ' . $e->getMessage());
                }
            } else {
                $this->flash('กรุณากรอกข้อมูลให้ครบถ้วน');
            }
        }
        $this->redirect('/backoffice/pdpa');
    }
}
