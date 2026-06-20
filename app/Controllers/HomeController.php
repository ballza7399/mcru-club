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
        
        $this->view('home/index', [
            'clubs'          => $clubs,
            'announcements'  => $announcements,
            'events'         => $events,
            'gallery'        => $gallery
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
}
