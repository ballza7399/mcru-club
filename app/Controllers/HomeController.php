<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Club;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $clubs = (new Club)->allWithMemberCount();
        $this->view('home/index', ['clubs' => $clubs]);
    }
}
