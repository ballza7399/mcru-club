<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Image;
use App\Models\Club;
use App\Models\User;
use App\Models\Application;

class ClubController extends Controller
{
    public function list(): void
    {
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(6, min(96, (int)$_GET['limit'])) : 9;
        $offset = ($currentPage - 1) * $limit;

        $clubModel = new Club;
        $totalClubs = $clubModel->countApproved();
        $totalPages = (int)ceil($totalClubs / $limit);
        
        $clubs = $clubModel->allWithMemberCount($limit, $offset);

        $this->view('clubs/list', [
            'clubs' => $clubs,
            'pageTitle' => 'ชมรมทั้งหมด',
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit
        ]);
    }

    public function detail(string $id): void
    {
        $clubId = (int) $id;
        $clubModel = new Club;
        $club = $clubModel->findWithDetail($clubId);

        if (!$club || ($club['status'] !== 'approved' && ($_SESSION['role'] ?? '') !== 'admin' && ((int)($club['president_id'] ?? 0) !== (int)($_SESSION['user_id'] ?? 0)))) {
            throw new \Exception('ไม่พบข้อมูลชมรมที่ต้องการ หรือชมรมยังไม่ได้รับการอนุมัติ', 404);
        }

        $appStatus = null;
        if (!empty($_SESSION['role']) && $_SESSION['role'] === 'student') {
            $appStatus = (new Application)->statusFor($_SESSION['user_id'], $clubId);
        }

        $db = \App\Core\Database::instance();
        
        // Fetch Club Announcements
        $stmtAnn = $db->prepare('SELECT a.*, u.name AS author_name FROM announcements a JOIN users u ON a.author_id = u.id WHERE a.club_id = ? ORDER BY a.id DESC LIMIT 6');
        $stmtAnn->execute([$clubId]);
        $announcements = $stmtAnn->fetchAll();

        // Fetch Club Events
        $stmtEv = $db->prepare('SELECT * FROM events WHERE club_id = ? ORDER BY event_date ASC, start_time ASC LIMIT 10');
        $stmtEv->execute([$clubId]);
        $events = $stmtEv->fetchAll();

        // Fetch Club Gallery
        $stmtGal = $db->prepare('SELECT * FROM gallery WHERE club_id = ? ORDER BY id DESC LIMIT 12');
        $stmtGal->execute([$clubId]);
        $gallery = $stmtGal->fetchAll();

        // Fetch Club Members for Hierarchy Chart
        $roleModel = new \App\Models\Role;
        $allMembers = $roleModel->getClubMembers($clubId);
        
        $president = null;
        $officers = [];
        $members = [];
        
        foreach ($allMembers as $m) {
            if ($m['role_key'] === 'president') {
                $president = $m;
            } elseif ($m['role_key'] === 'officer' || ($m['role_key'] !== 'member' && !empty($m['role_key']))) {
                $officers[] = $m;
            } else {
                $members[] = $m;
            }
        }

        $this->view('clubs/detail', [
            'club'          => $club,
            'appStatus'     => $appStatus,
            'isFull'        => $club['current_members'] >= $club['max_members'],
            'announcements' => $announcements,
            'events'        => $events,
            'gallery'       => $gallery,
            'president'     => $president,
            'officers'      => $officers,
            'members'       => $members
        ]);
    }

    public function registerPage(): void
    {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        
        // Find if this user has already proposed a club
        $db = \App\Core\Database::instance();
        $stmt = $db->prepare('SELECT * FROM clubs WHERE president_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([$userId]);
        $existingClub = $stmt->fetch();
        
        // Check if timeline check is enabled
        $checkEnabled = (getSetting('club_proposal_period_enabled', 'false') === 'true');
        $isOpen = true;
        $start = getSetting('club_proposal_period_start', '');
        $end = getSetting('club_proposal_period_end', '');

        if ($checkEnabled) {
            $nowTime = time();
            $startTime = !empty($start) ? strtotime($start) : null;
            $endTime = !empty($end) ? strtotime($end) : null;

            if ($startTime && $nowTime < $startTime) {
                $isOpen = false;
            }
            if ($endTime && $nowTime > $endTime) {
                $isOpen = false;
            }
        }

        $isAdminOrStaff = in_array($_SESSION['role'] ?? '', ['admin', 'staff'], true);

        if (!$isOpen && !$isAdminOrStaff && (!$existingClub || $existingClub['status'] !== 'correcting' || !isset($_GET['edit']))) {
            $this->view('clubs/register_closed', [
                'startDate' => $start,
                'endDate' => $end,
                'pageTitle' => 'ระบบเสนอขอจัดตั้งชมรมไม่อยู่ในช่วงเปิดรับ'
            ]);
            return;
        }
        
        if ($existingClub) {
            if (isset($_GET['edit']) && $existingClub['status'] === 'correcting') {
                $this->view('clubs/register', [
                    'error' => null,
                    'club' => $existingClub,
                    'isEdit' => true,
                    'pageTitle' => 'แก้ไขข้อมูลการเสนอขอก่อตั้งชมรม'
                ]);
                return;
            }

            $this->view('clubs/register_status', [
                'club' => $existingClub,
                'pageTitle' => 'สถานะการยื่นเสนอขอเพิ่มข้อมูลชมรม'
            ]);
            return;
        }
        
        $this->view('clubs/register', [
            'error' => null,
            'pageTitle' => 'ยื่นเสนอขอเพิ่มข้อมูลชมรมเข้าระบบ'
        ]);
    }

    public function registerReset(): void
    {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        
        $db = \App\Core\Database::instance();
        // Delete the rejected proposal so they can submit a new one
        $stmt = $db->prepare('DELETE FROM clubs WHERE president_id = ? AND status = "rejected"');
        $stmt->execute([$userId]);
        
        $this->redirect('/clubs/register');
    }

    public function registerSubmit(): void
    {
        $this->requireAuth();

        // Check timeline check if not admin or staff
        $checkEnabled = (getSetting('club_proposal_period_enabled', 'false') === 'true');
        $isOpen = true;
        $start = getSetting('club_proposal_period_start', '');
        $end = getSetting('club_proposal_period_end', '');

        if ($checkEnabled) {
            $nowTime = time();
            $startTime = !empty($start) ? strtotime($start) : null;
            $endTime = !empty($end) ? strtotime($end) : null;

            if ($startTime && $nowTime < $startTime) {
                $isOpen = false;
            }
            if ($endTime && $nowTime > $endTime) {
                $isOpen = false;
            }
        }

        $isAdminOrStaff = in_array($_SESSION['role'] ?? '', ['admin', 'staff'], true);
        
        $db = \App\Core\Database::instance();
        
        // Find if this user already has an existing proposal
        $stmtExist = $db->prepare('SELECT * FROM clubs WHERE president_id = ? ORDER BY id DESC LIMIT 1');
        $stmtExist->execute([$_SESSION['user_id']]);
        $existingClub = $stmtExist->fetch();

        if (!$isOpen && !$isAdminOrStaff && (!$existingClub || $existingClub['status'] !== 'correcting')) {
            $this->redirect('/clubs/register');
            return;
        }
        
        $error = null;
        $clubName = trim($_POST['club_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $advisorName = trim($_POST['advisor_name'] ?? '');
        $maxMembers = (int)($_POST['max_members'] ?? 50);
        $objectivesInput = $_POST['objectives'] ?? [];

        // Clean objectives
        $objectives = array_filter(array_map('trim', $objectivesInput), function($val) {
            return $val !== '';
        });
        $objectivesJson = !empty($objectives) ? json_encode(array_values($objectives), JSON_UNESCAPED_UNICODE) : null;

        if ($clubName === '' || $description === '') {
            $error = 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน';
        } elseif ($advisorName === '') {
            $error = 'กรุณาระบุชื่ออาจารย์ที่ปรึกษาชมรม';
        } elseif (empty($objectives)) {
            $error = 'กรุณาระบุวัตถุประสงค์ในการจัดตั้งชมรมอย่างน้อย 1 ข้อ';
        } else {
            // Check for unique club name, ignoring the one being edited
            $stmtCheck = $db->prepare('SELECT id FROM clubs WHERE club_name = ?');
            $stmtCheck->execute([$clubName]);
            $rowCheck = $stmtCheck->fetch();

            if ($rowCheck) {
                if (!$existingClub || (int)$existingClub['id'] !== (int)$rowCheck['id']) {
                    $error = 'มีชมรมชื่อนี้อยู่ในระบบแล้ว';
                }
            }
        }

        // Handle document upload
        $docPath = $this->uploadDocument('establishment_document');
        if ($docPath === '' && $existingClub) {
            $docPath = $existingClub['establishment_document'];
        }

        if (!$existingClub && $docPath === '') {
            $error = 'กรุณาแนบไฟล์เอกสารขอก่อตั้งชมรม (.doc, .docx, .pdf)';
        }
        
        if ($error) {
            $this->view('clubs/register', [
                'error' => $error,
                'club' => [
                    'club_name' => $clubName,
                    'description' => $description,
                    'advisor_name' => $advisorName,
                    'objectives' => $objectivesJson,
                    'max_members' => $maxMembers,
                    'club_logo' => $existingClub ? $existingClub['club_logo'] : '',
                    'qr_code' => $existingClub ? $existingClub['qr_code'] : '',
                    'establishment_document' => $existingClub ? $existingClub['establishment_document'] : '',
                    'rejection_reason' => $existingClub ? $existingClub['rejection_reason'] : null
                ],
                'isEdit' => (bool)$existingClub,
                'pageTitle' => $existingClub ? 'แก้ไขข้อมูลการเสนอขอก่อตั้งชมรม' : 'ยื่นเสนอขอเพิ่มข้อมูลชมรมเข้าระบบ'
            ]);
            return;
        }
        
        $logoPath = $this->uploadFile('logo');
        if ($logoPath === '' && $existingClub) {
            $logoPath = $existingClub['club_logo'];
        }

        $qrPath = $this->uploadFile('qr_code');
        if ($qrPath === '' && $existingClub) {
            $qrPath = $existingClub['qr_code'];
        }
        
        if ($existingClub) {
            // Update existing club request and set status back to pending
            $stmtUpdate = $db->prepare('
                UPDATE clubs 
                SET club_name = ?, 
                    description = ?, 
                    objectives = ?, 
                    advisor_name = ?, 
                    max_members = ?, 
                    club_logo = ?, 
                    qr_code = ?, 
                    establishment_document = ?, 
                    status = "pending",
                    rejection_reason = NULL
                WHERE id = ?
            ');
            $stmtUpdate->execute([
                $clubName,
                $description,
                $objectivesJson,
                $advisorName,
                $maxMembers,
                $logoPath ?: null,
                $qrPath ?: null,
                $docPath ?: null,
                $existingClub['id']
            ]);
            $this->flash('แก้ไขข้อมูลข้อเสนอขอจัดตั้งชมรมเรียบร้อยแล้ว โปรดรอผู้ดูแลระบบตรวจสอบอีกครั้ง');
        } else {
            // Insert new club request
            $stmtInsert = $db->prepare('
                INSERT INTO clubs (club_name, description, objectives, advisor_name, max_members, club_logo, qr_code, establishment_document, president_id, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, "pending")
            ');
            $stmtInsert->execute([
                $clubName,
                $description,
                $objectivesJson,
                $advisorName,
                $maxMembers,
                $logoPath ?: null,
                $qrPath ?: null,
                $docPath ?: null,
                $_SESSION['user_id']
            ]);
            $this->flash('ส่งข้อเสนอขอเพิ่มข้อมูลชมรมเข้าระบบเรียบร้อยแล้ว โปรดรอผู้ดูแลระบบตรวจสอบและอนุมัติ');
        }
        
        $this->redirect('/clubs');
    }

    public function requests(): void
    {
        $this->requireRole('admin', 'staff');

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        $db = \App\Core\Database::instance();
        
        $stmtCount = $db->query('SELECT COUNT(*) FROM clubs WHERE president_id IS NOT NULL OR status != "approved"');
        $totalRequests = (int)$stmtCount->fetchColumn();
        $totalPages = (int)ceil($totalRequests / $limit);

        $stmtList = $db->prepare('
            SELECT c.*, u.name AS proposer_name, u.student_id AS proposer_student_id
            FROM clubs c
            LEFT JOIN users u ON c.president_id = u.id
            WHERE c.president_id IS NOT NULL OR c.status != "approved"
            ORDER BY CASE c.status 
                WHEN "pending" THEN 1 
                WHEN "correcting" THEN 2 
                WHEN "approved" THEN 3 
                ELSE 4 
            END ASC, c.id DESC
            LIMIT ? OFFSET ?
        ');
        $stmtList->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmtList->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmtList->execute();
        $requests = $stmtList->fetchAll();

        $this->view('clubs/requests', [
            'requests' => $requests,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'pageTitle' => 'ตรวจสอบรายการคำขอจัดตั้งชมรมใหม่'
        ], 'backoffice');
    }

    public function requestDetail(string $id): void
    {
        $this->requireRole('admin', 'staff');
        $clubId = (int)$id;

        $db = \App\Core\Database::instance();
        $stmt = $db->prepare('
            SELECT c.*, u.name AS proposer_name, u.student_id AS proposer_student_id, 
                   u.faculty AS proposer_faculty, u.major AS proposer_major, u.phone AS proposer_phone
            FROM clubs c
            LEFT JOIN users u ON c.president_id = u.id
            WHERE c.id = ?
        ');
        $stmt->execute([$clubId]);
        $club = $stmt->fetch();

        if (!$club) {
            throw new \Exception('ไม่พบข้อมูลคำขอก่อตั้งชมรมนี้', 404);
        }

        $this->view('clubs/request_detail', [
            'club' => $club,
            'pageTitle' => 'รายละเอียดคำเสนอขอก่อตั้งชมรม: ' . $club['club_name']
        ], 'backoffice');
    }

    public function requestAction(): void
    {
        $this->requireRole('admin', 'staff');

        $clubId = (int)($_POST['club_id'] ?? 0);
        $action = trim($_POST['action'] ?? '');
        $reason = trim($_POST['rejection_reason'] ?? '');

        $clubModel = new Club;
        $club = $clubModel->findWithDetail($clubId);

        if (!$club) {
            throw new \Exception('ไม่พบข้อมูลชมรมที่ต้องการดำเนินการ', 404);
        }

        if ($action === 'approve') {
            $clubModel->update($clubId, ['status' => 'approved', 'rejection_reason' => null]);
            
            // Upgrade submitting student user to president system role
            if ($club['president_id']) {
                (new User)->setRole((int)$club['president_id'], 'president');
                
                // Add president user into the club members table as president role as well
                $db = \App\Core\Database::instance();
                $stmtMemberCheck = $db->prepare('SELECT COUNT(*) FROM club_members WHERE club_id = ? AND user_id = ?');
                $stmtMemberCheck->execute([$clubId, $club['president_id']]);
                if ((int)$stmtMemberCheck->fetchColumn() === 0) {
                    $stmtAddMember = $db->prepare('INSERT INTO club_members (club_id, user_id, role_id) VALUES (?, ?, 3)');
                    $stmtAddMember->execute([$clubId, $club['president_id']]);
                }

                // Send Notification
                (new \App\Models\Notification)->createNotification(
                    (int)$club['president_id'],
                    'คำเสนอจัดตั้งชมรมได้รับการอนุมัติ',
                    'ข้อเสนอขอจัดตั้งชมรม "' . $club['club_name'] . '" ของคุณได้รับการอนุมัติจัดตั้งในระยะแรกเรียบร้อยแล้ว คุณได้รับการปรับระดับเป็นประธานชมรม'
                );
            }
            $this->flash('อนุมัติคำเสนอจัดตั้งชมรมเข้าระบบเรียบร้อยแล้ว');

        } elseif ($action === 'correct') {
            if ($reason === '') {
                $this->flash('กรุณาระบุรายละเอียดสิ่งที่ต้องการให้แก้ไข');
                $this->redirect('/backoffice/clubs/requests/detail/' . $clubId);
                return;
            }

            $clubModel->update($clubId, [
                'status' => 'correcting',
                'rejection_reason' => $reason
            ]);

            if ($club['president_id']) {
                (new \App\Models\Notification)->createNotification(
                    (int)$club['president_id'],
                    'คำขอจัดตั้งชมรมถูกส่งกลับแก้ไข',
                    'ข้อเสนอขอจัดตั้งชมรม "' . $club['club_name'] . '" ของคุณต้องแก้ไขเพิ่มเติม: ' . $reason
                );
            }
            $this->flash('ส่งกลับแก้ไขข้อมูลชมรมเรียบร้อยแล้ว');

        } elseif ($action === 'reject') {
            if ($reason === '') {
                $this->flash('กรุณาระบุเหตุผลการปฏิเสธจัดตั้ง');
                $this->redirect('/backoffice/clubs/requests/detail/' . $clubId);
                return;
            }

            $clubModel->update($clubId, [
                'status' => 'rejected',
                'rejection_reason' => $reason
            ]);

            if ($club['president_id']) {
                (new \App\Models\Notification)->createNotification(
                    (int)$club['president_id'],
                    'คำขอจัดตั้งชมรมไม่ผ่านการอนุมัติ',
                    'ข้อเสนอขอจัดตั้งชมรม "' . $club['club_name'] . '" ของคุณถูกปฏิเสธ: ' . $reason
                );
            }
            $this->flash('ปฏิเสธคำขอก่อตั้งชมรมเรียบร้อยแล้ว');
        } else {
            throw new \Exception('การทำงานไม่ถูกต้อง', 400);
        }

        $this->redirect('/backoffice/clubs/requests');
    }

    public function approveVerification(): void
    {
        $this->requireRole('admin', 'staff');
        
        $clubId = (int)($_POST['club_id'] ?? 0);
        
        $clubModel = new Club;
        $club = $clubModel->findWithDetail($clubId);
        if ($club) {
            $clubModel->update($clubId, [
                'member_verification_status' => 'approved',
                'member_verification_comment' => NULL
            ]);
            
            if ($club['president_id']) {
                (new \App\Models\Notification)->createNotification(
                    (int)$club['president_id'],
                    'อนุมัติการตรวจสอบรายชื่อสมาชิกสำเร็จ',
                    'รายชื่อสมาชิกชมรม "' . $club['club_name'] . '" ของคุณผ่านการตรวจสอบเสร็จสมบูรณ์ ชมรมได้รับการจัดตั้งเสร็จสมบูรณ์และดำเนินกิจกรรมได้เต็มรูปแบบ!'
                );
            }
            $this->flash('อนุมัติการตรวจสอบรายชื่อสมาชิกเรียบร้อยแล้ว');
        }
        $this->redirect('/backoffice/clubs');
    }

    public function correctVerification(): void
    {
        $this->requireRole('admin', 'staff');
        
        $clubId = (int)($_POST['club_id'] ?? 0);
        $comment = trim($_POST['member_verification_comment'] ?? '');
        
        $clubModel = new Club;
        $club = $clubModel->findWithDetail($clubId);
        if ($club) {
            $clubModel->update($clubId, [
                'member_verification_status' => 'correcting',
                'member_verification_comment' => $comment
            ]);
            
            if ($club['president_id']) {
                (new \App\Models\Notification)->createNotification(
                    (int)$club['president_id'],
                    'ส่งกลับแก้ไขรายชื่อสมาชิก',
                    'คำขอตรวจสอบรายชื่อสมาชิกชมรม "' . $club['club_name'] . '" ของคุณถูกส่งกลับแก้ไข: ' . $comment
                );
            }
            $this->flash('ส่งกลับแก้ไขรายชื่อสมาชิกเรียบร้อยแล้ว');
        }
        $this->redirect('/backoffice/clubs');
    }

    public function manage(): void
    {
        $this->requireRole('admin', 'staff', 'president');

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        $clubModel = new Club;
        $totalClubs = $clubModel->countForManage($_SESSION['role'], $_SESSION['user_id']);
        $totalPages = (int)ceil($totalClubs / $limit);

        $clubs = $clubModel->listForManage($_SESSION['role'], $_SESSION['user_id'], $limit, $offset);

        $this->view('clubs/manage', [
            'clubs' => $clubs,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit
        ], 'backoffice');
    }

    public function store(): void
    {
        $this->requireRole('admin', 'staff');

        $logoPath = $this->uploadFile('logo');
        $qrPath   = $this->uploadFile('qr_code');

        (new Club)->create([
            'club_name'   => $_POST['club_name'],
            'description' => $_POST['description'],
            'max_members' => (int) $_POST['max_members'],
            'club_logo'   => $logoPath,
            'qr_code'     => $qrPath,
        ]);

        $this->redirect('/backoffice/clubs');
    }

    public function update(): void
    {
        $this->requireRole('admin', 'staff', 'president');

        $clubId = (int) $_POST['club_id'];
        $role   = $_SESSION['role'];
        $userId = $_SESSION['user_id'];
        $clubModel = new Club;

        $canEdit = ($role === 'admin' || $role === 'staff') || $clubModel->isPresident($clubId, $userId);
        if (!$canEdit) {
            $this->redirect('/backoffice/clubs');
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

        // admin / staff สามารถเปลี่ยนประธานได้
        if ($role === 'admin' || $role === 'staff') {
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
        $this->redirect('/backoffice/clubs');
    }

    public function delete(string $id): void
    {
        $this->requireRole('admin', 'staff');
        (new Club)->delete((int) $id);
        $this->redirect('/backoffice/clubs');
    }

    public function members(): void
    {
        $this->requireRole('admin', 'staff', 'president');
        
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
        
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        $totalMembers = $roleModel->countClubMembers($clubId);
        $totalPages = (int)ceil($totalMembers / $limit);

        $members = $roleModel->getClubMembersPaginated($clubId, $limit, $offset);
        $roles = $roleModel->listClubRoles($clubId);
        $allClubsList = ($role === 'admin' || $role === 'staff') ? $clubModel->allWithMemberCount() : [];
        
        $this->view('clubs/members', [
            'club' => $club,
            'members' => $members,
            'roles' => $roles,
            'allClubsList' => $allClubsList,
            'currentClubId' => $clubId,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit
        ], 'backoffice');
    }

    public function assignRole(): void
    {
        $this->requireRole('admin', 'staff', 'president');
        
        $clubId = (int)($_POST['club_id'] ?? 0);
        $userId = (int)($_POST['user_id'] ?? 0);
        $roleId = !empty($_POST['role_id']) ? (int)$_POST['role_id'] : null;
        
        $roleModel = new \App\Models\Role;
        $clubModel = new Club;
        
        $canManage = ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff') || $clubModel->isPresident($clubId, $_SESSION['user_id']);
        if (!$canManage) {
            $this->redirect('/');
        }
        
        $roleModel->assignMemberRole($clubId, $userId, $roleId);
        $this->flash('อัปเดตบทบาท/ตำแหน่งสมาชิกสำเร็จแล้ว');
        $this->redirect('/backoffice/clubs/members?club_id=' . $clubId);
    }

    public function removeMember(string $clubId, string $userId): void
    {
        $this->requireRole('admin', 'staff', 'president');
        
        $cId = (int)$clubId;
        $uId = (int)$userId;
        
        $roleModel = new \App\Models\Role;
        $clubModel = new Club;
        
        $canManage = ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff') || $clubModel->isPresident($cId, $_SESSION['user_id']);
        if (!$canManage) {
            $this->redirect('/');
        }
        
        $roleModel->removeClubMember($cId, $uId);
        $this->flash('คัดสมาชิกออกจากชมรมเรียบร้อยแล้ว');
        $this->redirect('/backoffice/clubs/members?club_id=' . $cId);
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

    private function uploadDocument(string $field): string
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return '';
        }
        
        $file = $_FILES[$field];
        $origName = $file['name'] ?? '';
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        
        if (!in_array($ext, ['doc', 'docx', 'pdf'], true)) {
            return '';
        }
        
        $dir = BASE_PATH . '/uploads/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        $name = time() . '_' . $field . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $relPath = 'uploads/' . $name;
        $absPath = $dir . $name;
        
        if (@move_uploaded_file($file['tmp_name'], $absPath)) {
            return $relPath;
        }
        
        return '';
    }
}
