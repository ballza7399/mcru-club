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
$router->get('/clubs/members',     'App\Controllers\ClubController', 'members');
$router->post('/clubs/members/assign-role', 'App\Controllers\ClubController', 'assignRole');
$router->get('/clubs/members/remove/{club_id}/{user_id}', 'App\Controllers\ClubController', 'removeMember');

$router->post('/applications/apply',       'App\Controllers\ApplicationController', 'apply');
$router->get('/applications/manage',       'App\Controllers\ApplicationController', 'manage');
$router->get('/applications/approve/{id}', 'App\Controllers\ApplicationController', 'approve');
$router->get('/applications/reject/{id}',  'App\Controllers\ApplicationController', 'reject');

$router->get('/roles/manage',            'App\Controllers\RoleController', 'manage');
$router->post('/roles/store',            'App\Controllers\RoleController', 'store');
$router->get('/roles/delete/{id}',       'App\Controllers\RoleController', 'delete');
$router->post('/roles/permissions/sync', 'App\Controllers\RoleController', 'syncPermissions');

$router->get('/announcements/detail/{id}', 'App\Controllers\AnnouncementController', 'detail');
$router->get('/announcements/manage',     'App\Controllers\AnnouncementController', 'manage');
$router->post('/announcements/store',     'App\Controllers\AnnouncementController', 'store');
$router->post('/announcements/update',    'App\Controllers\AnnouncementController', 'update');
$router->get('/announcements/delete/{id}', 'App\Controllers\AnnouncementController', 'delete');

$router->get('/events/manage',     'App\Controllers\EventController', 'manage');
$router->post('/events/store',     'App\Controllers\EventController', 'store');
$router->post('/events/update',    'App\Controllers\EventController', 'update');
$router->get('/events/delete/{id}', 'App\Controllers\EventController', 'delete');

$router->get('/gallery/manage',     'App\Controllers\GalleryController', 'manage');
$router->post('/gallery/store',     'App\Controllers\GalleryController', 'store');
$router->get('/gallery/delete/{id}', 'App\Controllers\GalleryController', 'delete');

$router->get('/faculties/manage',     'App\Controllers\FacultyController', 'manage');
$router->post('/faculties/store',     'App\Controllers\FacultyController', 'store');
$router->post('/faculties/update',    'App\Controllers\FacultyController', 'update');
$router->get('/faculties/delete/{id}', 'App\Controllers\FacultyController', 'delete');

$router->post('/majors/store',     'App\Controllers\FacultyController', 'storeMajor');
$router->post('/majors/update',    'App\Controllers\FacultyController', 'updateMajor');
$router->get('/majors/delete/{id}', 'App\Controllers\FacultyController', 'deleteMajor');

$router->dispatch();
