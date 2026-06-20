<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Image;
use App\Models\Club;
use App\Models\User;
use App\Models\Application;

class ClubController extends Controller
{
    public function detail(string $id): void
    {
        $clubId = (int) $id;
        $club   = (new Club)->findWithDetail($clubId);

        if (!$club) {
            throw new \Exception('ไม่พบข้อมูลชมรมที่ต้องการ', 404);
        }

        $appStatus = null;
        if (!empty($_SESSION['role']) && $_SESSION['role'] === 'student') {
            $appStatus = (new Application)->statusFor($_SESSION['user_id'], $clubId);
        }

        $this->view('clubs/detail', [
            'club'      => $club,
            'appStatus' => $appStatus,
            'isFull'    => $club['current_members'] >= $club['max_members'],
        ]);
    }

    public function manage(): void
    {
        $this->requireRole('admin', 'president');
        $clubs = (new Club)->listForManage($_SESSION['role'], $_SESSION['user_id']);
        $this->view('clubs/manage', ['clubs' => $clubs]);
    }

    public function store(): void
    {
        $this->requireRole('admin');

        $logoPath = $this->uploadFile('logo');
        $qrPath   = $this->uploadFile('qr_code');

        (new Club)->create([
            'club_name'   => $_POST['club_name'],
            'description' => $_POST['description'],
            'max_members' => (int) $_POST['max_members'],
            'club_logo'   => $logoPath,
            'qr_code'     => $qrPath,
        ]);

        $this->redirect('/clubs/manage');
    }

    public function update(): void
    {
        $this->requireRole('admin', 'president');

        $clubId = (int) $_POST['club_id'];
        $role   = $_SESSION['role'];
        $userId = $_SESSION['user_id'];
        $clubModel = new Club;

        $canEdit = ($role === 'admin') || $clubModel->isPresident($clubId, $userId);
        if (!$canEdit) {
            $this->redirect('/clubs/manage');
        }

        $fields = [
            'club_name'   => $_POST['club_name'],
            'description' => $_POST['description'],
            'max_members' => (int) $_POST['max_members'],
        ];

        $logo = $this->uploadFile('logo');
        $qr   = $this->uploadFile('qr_code');
        if ($logo) $fields['club_logo'] = $logo;
        if ($qr)   $fields['qr_code']   = $qr;

        // admin สามารถเปลี่ยนประธานได้
        if ($role === 'admin') {
            $presStudentId = trim($_POST['pres_student_id'] ?? '');
            if ($presStudentId !== '') {
                $presUser = (new User)->findByStudentId($presStudentId);
                if ($presUser) {
                    $fields['president_id'] = $presUser['id'];
                    (new User)->setRole($presUser['id'], 'president');
                }
            } else {
                $fields['president_id'] = null; // ส่ง null เพื่อ SET president_id = NULL
            }
        }

        $clubModel->update($clubId, $fields);
        $this->redirect('/clubs/manage');
    }

    public function delete(string $id): void
    {
        $this->requireRole('admin');
        (new Club)->delete((int) $id);
        $this->redirect('/clubs/manage');
    }

    public function members(): void
    {
        $this->requireRole('admin', 'president');
        
        $roleModel = new \App\Models\Role;
        $clubModel = new Club;
        
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        
        $clubId = isset($_GET['club_id']) ? (int)$_GET['club_id'] : 0;
        
        if ($role === 'president') {
            // Find which club this user is president of
            $db = \App\Core\Database::instance();
            $stmt = $db->prepare('SELECT id FROM clubs WHERE president_id = ?');
            $stmt->execute([$userId]);
            $myClub = $stmt->fetch();
            if (!$myClub) {
                throw new \Exception('คุณไม่ได้เป็นประธานชมรมใด ๆ ในระบบ', 403);
            }
            $clubId = (int)$myClub['id'];
        } else {
            if ($clubId === 0) {
                $allClubs = $clubModel->allWithMemberCount();
                if (!empty($allClubs)) {
                    $clubId = (int)$allClubs[0]['id'];
                }
            }
        }
        
        $club = $clubModel->findWithDetail($clubId);
        if (!$club) {
            throw new \Exception('ไม่พบข้อมูลชมรมที่ต้องการจัดการสมาชิก', 404);
        }
        
        $members = $roleModel->getClubMembers($clubId);
        $roles = $roleModel->listClubRoles($clubId);
        $allClubsList = ($role === 'admin') ? $clubModel->allWithMemberCount() : [];
        
        $this->view('clubs/members', [
            'club' => $club,
            'members' => $members,
            'roles' => $roles,
            'allClubsList' => $allClubsList,
            'currentClubId' => $clubId
        ]);
    }

    public function assignRole(): void
    {
        $this->requireRole('admin', 'president');
        
        $clubId = (int)($_POST['club_id'] ?? 0);
        $userId = (int)($_POST['user_id'] ?? 0);
        $roleId = !empty($_POST['role_id']) ? (int)$_POST['role_id'] : null;
        
        $roleModel = new \App\Models\Role;
        $clubModel = new Club;
        
        $canManage = ($_SESSION['role'] === 'admin') || $clubModel->isPresident($clubId, $_SESSION['user_id']);
        if (!$canManage) {
            $this->redirect('/');
        }
        
        $roleModel->assignMemberRole($clubId, $userId, $roleId);
        $this->flash('อัปเดตบทบาท/ตำแหน่งสมาชิกสำเร็จแล้ว');
        $this->redirect('/clubs/members?club_id=' . $clubId);
    }

    public function removeMember(string $clubId, string $userId): void
    {
        $this->requireRole('admin', 'president');
        
        $cId = (int)$clubId;
        $uId = (int)$userId;
        
        $roleModel = new \App\Models\Role;
        $clubModel = new Club;
        
        $canManage = ($_SESSION['role'] === 'admin') || $clubModel->isPresident($cId, $_SESSION['user_id']);
        if (!$canManage) {
            $this->redirect('/');
        }
        
        $roleModel->removeClubMember($cId, $uId);
        $this->flash('คัดสมาชิกออกจากชมรมเรียบร้อยแล้ว');
        $this->redirect('/clubs/members?club_id=' . $cId);
    }

    // --- private helpers ---

    /**
     * อัปโหลดรูป (logo/qr) พร้อมย่อขนาด+บีบอัดผ่าน Image::uploadResized
     * คืน relative path หรือ '' ถ้าไม่มีไฟล์/ไม่ใช่รูป
     */
    private function uploadFile(string $field): string
    {
        if (!isset($_FILES[$field])) {
            return '';
        }
        return Image::uploadResized($_FILES[$field], $field);
    }
}
