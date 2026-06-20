<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Application;
use App\Models\Club;

class ApplicationController extends Controller
{
    public function apply(): void
    {
        $this->requireRole('student');

        $userId = $_SESSION['user_id'];
        $clubId = (int) ($_POST['club_id'] ?? 0);
        $appModel  = new Application;
        $clubModel = new Club;

        $cap = $clubModel->capacity($clubId);
        if (!$cap || $cap['current_members'] >= $cap['max_members']) {
            $this->redirect('/clubs/detail/' . $clubId . '?err=full');
        }

        if (!$appModel->exists($userId, $clubId)) {
            $appModel->create($userId, $clubId);
        }

        $this->redirect('/clubs/detail/' . $clubId);
    }

    public function manage(): void
    {
        $this->requireRole('admin', 'president');

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
        $offset = ($currentPage - 1) * $limit;

        $appModel = new Application;
        $totalApps = $appModel->countForManage($_SESSION['role'], $_SESSION['user_id']);
        $totalPages = (int)ceil($totalApps / $limit);

        $apps = $appModel->listForManage($_SESSION['role'], $_SESSION['user_id'], $limit, $offset);

        $this->view('applications/manage', [
            'apps' => $apps,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'limit' => $limit
        ], 'backoffice');
    }

    public function approve(string $id): void
    {
        $this->requireRole('admin', 'president');
        $this->updateStatus((int) $id, 'approved');
    }

    public function reject(string $id): void
    {
        $this->requireRole('admin', 'president');
        $this->updateStatus((int) $id, 'rejected');
    }

    private function updateStatus(int $appId, string $status): void
    {
        $appModel = new Application;
        $canUpdate = false;

        if ($_SESSION['role'] === 'admin') {
            $canUpdate = true;
        } else {
            $clubId = $appModel->clubIdOf($appId);
            if ($clubId !== null) {
                $canUpdate = (new Club)->isPresident($clubId, $_SESSION['user_id']);
            }
        }

        if ($canUpdate) {
            $appModel->updateStatus($appId, $status);
        }

        $this->redirect('/applications/manage');
    }
}
