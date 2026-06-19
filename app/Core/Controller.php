<?php
namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], string $layout = 'layout'): void
    {
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
    }

    protected function requireRole(string ...$roles): void
    {
        $this->requireAuth();
        if (!in_array($_SESSION['role'], $roles, true)) {
            $this->redirect('/');
        }
    }
}
