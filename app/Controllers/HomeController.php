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

    public function footerSettings(): void
    {
        $this->requireRole('admin');
        
        $db = \App\Core\Database::instance();
        $settingsRaw = $db->query("SELECT * FROM site_settings WHERE setting_group = 'footer'")->fetchAll();
        
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['setting_key']] = $s['setting_value'];
        }
        
        $this->view('home/footer_settings', [
            'settings'   => $settings,
            'activePage' => 'footer_settings',
            'pageTitle'  => 'จัดการข้อมูล Footer และช่องทางการติดต่อ'
        ], 'backoffice');
    }

    public function footerSettingsUpdate(): void
    {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = \App\Core\Database::instance();
            
            $updatableKeys = [
                'footer_about_text',
                'footer_facebook_url',
                'footer_youtube_url',
                'footer_website_url',
                'footer_contact_address',
                'footer_contact_phone',
                'footer_contact_email'
            ];
            
            try {
                $db->beginTransaction();
                
                $stmt = $db->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                
                foreach ($updatableKeys as $key) {
                    $val = trim($_POST[$key] ?? '');
                    $stmt->execute([$val, $key]);
                }
                
                $db->commit();
                $this->flash('อัปเดตข้อมูล Footer และช่องทางการติดต่อสำเร็จเรียบร้อยแล้ว');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('เกิดข้อผิดพลาดในการอัปเดต: ' . $e->getMessage());
            }
        }
        
        $this->redirect('/backoffice/settings/footer');
    }

    public function mourningSettings(): void
    {
        $this->requireRole('admin');
        
        $db = \App\Core\Database::instance();
        $settingsRaw = $db->query("SELECT * FROM site_settings WHERE setting_group = 'mourning'")->fetchAll();
        
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['setting_key']] = $s['setting_value'];
        }
        
        $this->view('home/mourning_settings', [
            'settings'   => $settings,
            'activePage' => 'mourning_settings',
            'pageTitle'  => 'จัดการระบบไว้อาลัยก่อนเข้าเว็บไซต์'
        ], 'backoffice');
    }

    public function mourningSettingsUpdate(): void
    {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = \App\Core\Database::instance();
            
            $updatableKeys = [
                'mourning_enabled',
                'mourning_image_url',
                'mourning_duration',
                'mourning_stars_enabled',
                'mourning_homepage_only',
                'mourning_every_time'
            ];
            
            try {
                $db->beginTransaction();
                
                $stmt = $db->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                
                foreach ($updatableKeys as $key) {
                    $val = trim($_POST[$key] ?? '');
                    if ($key === 'mourning_duration') {
                        $val = (int)$val;
                        if ($val <= 0) $val = 3;
                    }
                    $stmt->execute([$val, $key]);
                }
                
                $db->commit();
                $this->flash('อัปเดตการตั้งค่าระบบไว้อาลัยสำเร็จเรียบร้อยแล้ว');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
            }
        }
        
        $this->redirect('/backoffice/settings/mourning');
    }

    public function ogSettings(): void
    {
        $this->requireRole('admin');
        
        $db = \App\Core\Database::instance();
        $settingsRaw = $db->query("SELECT * FROM site_settings WHERE setting_group = 'opengraph'")->fetchAll();
        
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['setting_key']] = $s['setting_value'];
        }
        
        $this->view('home/og_settings', [
            'settings'   => $settings,
            'activePage' => 'og_settings',
            'pageTitle'  => 'จัดการการแชร์และ Open Graph Meta Tags'
        ], 'backoffice');
    }

    public function ogSettingsUpdate(): void
    {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = \App\Core\Database::instance();
            
            $ogTitle = trim($_POST['og_title'] ?? '');
            $ogDescription = trim($_POST['og_description'] ?? '');
            $ogImagePath = $_POST['og_image_old'] ?? '';
            
            // จัดการอัปโหลดรูปภาพสำหรับการแชร์
            if (isset($_FILES['og_image']) && $_FILES['og_image']['error'] === UPLOAD_ERR_OK) {
                $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
                $info = @getimagesize($_FILES['og_image']['tmp_name']);
                if ($info !== false && in_array($info['mime'], $allowedMime, true)) {
                    // ลบรูปภาพเก่าถ้ามี
                    if (!empty($ogImagePath) && file_exists(BASE_PATH . '/' . $ogImagePath)) {
                        @unlink(BASE_PATH . '/' . $ogImagePath);
                    }
                    
                    // อัปโหลดและปรับขนาดตามสัดส่วนการแชร์ของสื่อสังคมออนไลน์ (1200x630)
                    $uploaded = \App\Core\Image::uploadResized($_FILES['og_image'], 'og_share', 1200, 630);
                    if ($uploaded) {
                        $ogImagePath = $uploaded;
                    }
                } else {
                    $this->flash('ประเภทไฟล์รูปภาพสำหรับการแชร์ไม่ถูกต้อง รองรับเฉพาะ JPG, PNG และ WEBP');
                    $this->redirect('/backoffice/settings/og');
                }
            }
            
            try {
                $db->beginTransaction();
                
                $stmt = $db->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$ogTitle, 'og_title']);
                $stmt->execute([$ogDescription, 'og_description']);
                $stmt->execute([$ogImagePath, 'og_image']);
                
                $db->commit();
                $this->flash('อัปเดตการตั้งค่าการแชร์ (Open Graph) สำเร็จเรียบร้อยแล้ว');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
            }
        }
        
        $this->redirect('/backoffice/settings/og');
    }

    public function proposalSettings(): void
    {
        $this->requireRole('admin');
        
        $db = \App\Core\Database::instance();
        $settingsRaw = $db->query("SELECT * FROM site_settings WHERE setting_group = 'club_proposal'")->fetchAll();
        
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['setting_key']] = $s['setting_value'];
        }
        
        $this->view('home/proposal_settings', [
            'settings'   => $settings,
            'activePage' => 'proposal_settings',
            'pageTitle'  => 'ตั้งค่าช่วงเวลาเปิดรับเสนอจัดตั้งชมรม'
        ], 'backoffice');
    }

    public function proposalSettingsUpdate(): void
    {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = \App\Core\Database::instance();
            
            $updatableKeys = [
                'club_proposal_period_start',
                'club_proposal_period_end',
                'club_proposal_period_enabled'
            ];
            
            try {
                $db->beginTransaction();
                
                $stmt = $db->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                
                foreach ($updatableKeys as $key) {
                    $val = trim($_POST[$key] ?? '');
                    if ($key === 'club_proposal_period_enabled') {
                        $val = ($val === 'true') ? 'true' : 'false';
                    }
                    $stmt->execute([$val, $key]);
                }
                
                $db->commit();
                $this->flash('อัปเดตการตั้งค่าช่วงเวลาเปิดรับเสนอจัดตั้งชมรมสำเร็จเรียบร้อยแล้ว');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
            }
        }
        
        $this->redirect('/backoffice/settings/proposal');
    }
}



