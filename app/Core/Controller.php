<?php
namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], string $layout = 'layout'): void
    {
        $this->syncUserSession();

        extract($data);
        $flash = $_SESSION['_flash'] ?? null;
        unset($_SESSION['_flash']);

        $contentFile = BASE_PATH . '/app/Views/' . $template . '.php';
        ob_start();
        require $contentFile;
        $content = ob_get_clean();

        require BASE_PATH . '/app/Views/layouts/' . $layout . '.php';
    }

    protected function redirect(string $path): void
    {
        $url = str_starts_with($path, 'http') ? $path : BASE_URL . $path;
        header('Location: ' . $url);
        exit;
    }

    protected function flash(string $msg): void
    {
        $_SESSION['_flash'] = $msg;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
        }
        $this->syncUserSession();
    }

    protected function requireRole(string ...$roles): void
    {
        $this->requireAuth();
        if (!in_array($_SESSION['role'], $roles, true)) {
            $this->redirect('/');
        }
    }

    protected function requirePermission(string $permissionKey): void
    {
        $this->requireAuth();
        if (!hasBackofficePermission($permissionKey)) {
            $this->flash('ขออภัย คุณไม่มีสิทธิ์เข้าถึงส่วนงานนี้');
            $this->redirect('/');
        }
    }

    private function syncUserSession(): void
    {
        if (!empty($_SESSION['user_id'])) {
            try {
                $userId = (int)$_SESSION['user_id'];
                $db = Database::instance();
                
                $stmt = $db->prepare(
                    'SELECT u.name, u.avatar, r.role_key AS role 
                     FROM users u
                     JOIN roles r ON u.role_id = r.id
                     WHERE u.id = ?'
                );
                $stmt->execute([$userId]);
                $user = $stmt->fetch();
                
                if ($user) {
                    $role = $user['role'];
                    if ($role === 'student') {
                        // Check if they are a president of any approved club
                        $stmtPres = $db->prepare('SELECT COUNT(*) FROM clubs WHERE president_id = ? AND status = "approved"');
                        $stmtPres->execute([$userId]);
                        if ((int)$stmtPres->fetchColumn() > 0) {
                            $role = 'president';
                        }
                    }
                    $_SESSION['role'] = $role;
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['avatar'] = $user['avatar'];
                }
            } catch (\Exception $e) {
                // Fail silently if database is not connected or initialized yet
            }
        }
    }

}
