<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Club;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Gallery;

class HomeController extends Controller
{
    public function index(): void
    {
        $clubModel = new Club;
        $allClubs = $clubModel->allWithMemberCount();
        $clubs = array_slice($allClubs, 0, 6);
        
        $annModel = new Announcement;
        $announcements = $annModel->all(6);
        
        $eventModel = new Event;
        $events = $eventModel->all();
        
        $galModel = new Gallery;
        $gallery = $galModel->all(12);
        
        $this->view('home/index', [
            'clubs'          => $clubs,
            'announcements'  => $announcements,
            'events'         => $events,
            'gallery'        => $gallery
        ]);
    }
}
