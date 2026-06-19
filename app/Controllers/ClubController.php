<?php
namespace App\Controllers;

use App\Core\Controller;
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
            http_response_code(404);
            echo 'ไม่พบข้อมูล';
            return;
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

    private function uploadFile(string $field): string
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return '';
        }
        $dir = BASE_PATH . '/uploads/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = 'uploads/' . time() . '_' . $field . '_' . basename($_FILES[$field]['name']);
        move_uploaded_file($_FILES[$field]['tmp_name'], BASE_PATH . '/' . $path);
        return $path;
    }
}
