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
$router->get('/policy', 'App\Controllers\HomeController', 'policyPage');
$router->get('/backoffice', 'App\Controllers\HomeController', 'backoffice');

$router->get('/auth/login',     'App\Controllers\AuthController', 'login');
$router->post('/auth/login',    'App\Controllers\AuthController', 'login');
$router->get('/auth/register',  'App\Controllers\AuthController', 'register');
$router->post('/auth/register', 'App\Controllers\AuthController', 'register');
$router->get('/auth/logout',    'App\Controllers\AuthController', 'logout');
$router->get('/profile',        'App\Controllers\AuthController', 'profile');
$router->post('/profile/update', 'App\Controllers\AuthController', 'profileUpdate');


$router->get('/clubs',             'App\Controllers\ClubController', 'list');
$router->get('/clubs/detail/{id}', 'App\Controllers\ClubController', 'detail');
$router->get('/clubs/register',    'App\Controllers\ClubController', 'registerPage');
$router->post('/clubs/register',   'App\Controllers\ClubController', 'registerSubmit');
$router->get('/clubs/register/reset', 'App\Controllers\ClubController', 'registerReset');
$router->post('/applications/apply',       'App\Controllers\ApplicationController', 'apply');

// --- Backoffice Management Routes ---
$router->get('/backoffice/clubs',      'App\Controllers\ClubController', 'manage');
$router->post('/backoffice/clubs/store',      'App\Controllers\ClubController', 'store');
$router->post('/backoffice/clubs/update',     'App\Controllers\ClubController', 'update');
$router->get('/backoffice/clubs/delete/{id}', 'App\Controllers\ClubController', 'delete');
$router->get('/backoffice/clubs/approve/{id}', 'App\Controllers\ClubController', 'approveClub');
$router->get('/backoffice/clubs/reject/{id}',  'App\Controllers\ClubController', 'rejectClub');
$router->get('/backoffice/clubs/members',     'App\Controllers\ClubController', 'members');
$router->post('/backoffice/clubs/members/assign-role', 'App\Controllers\ClubController', 'assignRole');
$router->get('/backoffice/clubs/members/remove/{club_id}/{user_id}', 'App\Controllers\ClubController', 'removeMember');

$router->get('/backoffice/applications',       'App\Controllers\ApplicationController', 'manage');
$router->get('/backoffice/applications/approve/{id}', 'App\Controllers\ApplicationController', 'approve');
$router->get('/backoffice/applications/reject/{id}',  'App\Controllers\ApplicationController', 'reject');

$router->get('/backoffice/roles',            'App\Controllers\RoleController', 'manage');
$router->post('/backoffice/roles/store',            'App\Controllers\RoleController', 'store');
$router->get('/backoffice/roles/delete/{id}',       'App\Controllers\RoleController', 'delete');
$router->post('/backoffice/roles/permissions/sync', 'App\Controllers\RoleController', 'syncPermissions');

$router->get('/backoffice/users',            'App\Controllers\UserController', 'manage');
$router->post('/backoffice/users/update-role',      'App\Controllers\UserController', 'updateRole');
$router->post('/backoffice/users/toggle-status',    'App\Controllers\UserController', 'toggleStatus');
$router->post('/backoffice/users/reset-password',   'App\Controllers\UserController', 'resetPassword');

$router->get('/announcements/detail/{id}', 'App\Controllers\AnnouncementController', 'detail');
$router->get('/backoffice/announcements',     'App\Controllers\AnnouncementController', 'manage');
$router->post('/backoffice/announcements/store',     'App\Controllers\AnnouncementController', 'store');
$router->post('/backoffice/announcements/update',    'App\Controllers\AnnouncementController', 'update');
$router->get('/backoffice/announcements/delete/{id}', 'App\Controllers\AnnouncementController', 'delete');

$router->get('/backoffice/events',     'App\Controllers\EventController', 'manage');
$router->post('/backoffice/events/store',     'App\Controllers\EventController', 'store');
$router->post('/backoffice/events/update',    'App\Controllers\EventController', 'update');
$router->get('/backoffice/events/delete/{id}', 'App\Controllers\EventController', 'delete');

$router->get('/backoffice/gallery',     'App\Controllers\GalleryController', 'manage');
$router->post('/backoffice/gallery/store',     'App\Controllers\GalleryController', 'store');
$router->get('/backoffice/gallery/delete/{id}', 'App\Controllers\GalleryController', 'delete');

$router->get('/backoffice/faculties',     'App\Controllers\FacultyController', 'manage');
$router->post('/backoffice/faculties/store',     'App\Controllers\FacultyController', 'store');
$router->post('/backoffice/faculties/update',    'App\Controllers\FacultyController', 'update');
$router->get('/backoffice/faculties/delete/{id}', 'App\Controllers\FacultyController', 'delete');

$router->get('/backoffice/pdpa',                  'App\Controllers\HomeController', 'pdpa');
$router->post('/backoffice/pdpa/update',          'App\Controllers\HomeController', 'pdpaUpdate');
$router->get('/backoffice/settings/footer',       'App\Controllers\HomeController', 'footerSettings');
$router->post('/backoffice/settings/footer/update', 'App\Controllers\HomeController', 'footerSettingsUpdate');
$router->get('/backoffice/settings/mourning',     'App\Controllers\HomeController', 'mourningSettings');
$router->post('/backoffice/settings/mourning/update', 'App\Controllers\HomeController', 'mourningSettingsUpdate');
$router->get('/backoffice/settings/og',        'App\Controllers\HomeController', 'ogSettings');
$router->post('/backoffice/settings/og/update', 'App\Controllers\HomeController', 'ogSettingsUpdate');


$router->post('/backoffice/majors/store',     'App\Controllers\FacultyController', 'storeMajor');
$router->post('/backoffice/majors/update',    'App\Controllers\FacultyController', 'updateMajor');
$router->get('/backoffice/majors/delete/{id}', 'App\Controllers\FacultyController', 'deleteMajor');

// --- Club Backoffice (ClubOffice) Routes ---
$router->get('/cluboffice',                           'App\Controllers\ClubOfficeController', 'dashboard');
$router->get('/cluboffice/info',                      'App\Controllers\ClubOfficeController', 'info');
$router->post('/cluboffice/info/update',              'App\Controllers\ClubOfficeController', 'updateInfo');
$router->get('/cluboffice/members',                   'App\Controllers\ClubOfficeController', 'members');
$router->post('/cluboffice/members/assign-role',      'App\Controllers\ClubOfficeController', 'assignRole');
$router->get('/cluboffice/members/remove/{user_id}',  'App\Controllers\ClubOfficeController', 'removeMember');
$router->get('/cluboffice/applications',              'App\Controllers\ClubOfficeController', 'applications');
$router->get('/cluboffice/applications/approve/{id}', 'App\Controllers\ClubOfficeController', 'approveApplication');
$router->get('/cluboffice/applications/reject/{id}',  'App\Controllers\ClubOfficeController', 'rejectApplication');
$router->get('/cluboffice/announcements',             'App\Controllers\ClubOfficeController', 'announcements');
$router->post('/cluboffice/announcements/store',      'App\Controllers\ClubOfficeController', 'storeAnnouncement');
$router->post('/cluboffice/announcements/update',     'App\Controllers\ClubOfficeController', 'updateAnnouncement');
$router->get('/cluboffice/announcements/delete/{id}', 'App\Controllers\ClubOfficeController', 'deleteAnnouncement');
$router->get('/cluboffice/events',                    'App\Controllers\ClubOfficeController', 'events');
$router->post('/cluboffice/events/store',              'App\Controllers\ClubOfficeController', 'storeEvent');
$router->post('/cluboffice/events/update',             'App\Controllers\ClubOfficeController', 'updateEvent');
$router->get('/cluboffice/events/delete/{id}',         'App\Controllers\ClubOfficeController', 'deleteEvent');
$router->get('/cluboffice/gallery',                   'App\Controllers\ClubOfficeController', 'gallery');
$router->post('/cluboffice/gallery/store',             'App\Controllers\ClubOfficeController', 'storeGallery');
$router->get('/cluboffice/gallery/delete/{id}',        'App\Controllers\ClubOfficeController', 'deleteGallery');

// --- Notification API Routes ---
$router->get('/api/notifications',            'App\Controllers\NotificationController', 'list');
$router->post('/api/notifications/read-all',  'App\Controllers\NotificationController', 'markAllRead');
$router->post('/api/notifications/read/{id}', 'App\Controllers\NotificationController', 'markRead');

// --- PDPA API Routes ---
$router->post('/api/pdpa/consent',            'App\Controllers\AuthController', 'giveConsent');

$router->dispatch();
