<?php
/**
 * Front Controller — จุดเข้าเดียวของแอปทั้งหมด
 * ทุก request ถูก .htaccess ส่งมาที่นี่ แล้ว Router แยกไปยัง Controller/Action
 */

declare(strict_types=1);

session_start();

// --- paths & config ---
define('BASE_PATH', __DIR__);

$config = require BASE_PATH . '/config/config.php';

// base_path = โฟลเดอร์ย่อยที่แอปอยู่ (เช่น /mcru-club) ใช้สร้าง URL และตัด prefix
define('BASE_URL', rtrim($config['base_path'], '/'));

// --- autoloader (PSR-4 แบบง่าย: App\ => app/) ---
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require BASE_PATH . '/app/Core/helpers.php';

// --- error handling middleware (ติดตั้งให้เร็วที่สุด เพื่อดักทุก error หลังจากนี้) ---
App\Core\ErrorHandler::register($config['debug'] ?? false);

// --- เชื่อมต่อฐานข้อมูล ---
App\Core\Database::connect($config['db']);

// --- routes ---
use App\Core\Router;

$router = new Router(BASE_URL);

$router->get('/',  'App\Controllers\HomeController', 'index');

$router->get('/auth/login',     'App\Controllers\AuthController', 'login');
$router->post('/auth/login',    'App\Controllers\AuthController', 'login');
$router->get('/auth/register',  'App\Controllers\AuthController', 'register');
$router->post('/auth/register', 'App\Controllers\AuthController', 'register');
$router->get('/auth/logout',    'App\Controllers\AuthController', 'logout');

$router->get('/clubs/detail/{id}', 'App\Controllers\ClubController', 'detail');
$router->get('/clubs/manage',      'App\Controllers\ClubController', 'manage');
$router->post('/clubs/store',      'App\Controllers\ClubController', 'store');
$router->post('/clubs/update',     'App\Controllers\ClubController', 'update');
$router->get('/clubs/delete/{id}', 'App\Controllers\ClubController', 'delete');

$router->post('/applications/apply',       'App\Controllers\ApplicationController', 'apply');
$router->get('/applications/manage',       'App\Controllers\ApplicationController', 'manage');
$router->get('/applications/approve/{id}', 'App\Controllers\ApplicationController', 'approve');
$router->get('/applications/reject/{id}',  'App\Controllers\ApplicationController', 'reject');

$router->dispatch();
