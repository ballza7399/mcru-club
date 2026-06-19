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
        $this->requireAuth();
        $clubId = (int) $id;
        $club   = (new Club)->findWithDetail($clubId);

        if (!$club) {
            throw new \Exception('ไม่พบข้อมูลชมรมที่ต้องการ', 404);
        }

        $appStatus = null;
        if ($_SESSION['role'] === 'student') {
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
