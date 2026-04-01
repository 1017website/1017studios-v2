<?php
// ============================================================
// DashboardController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Message;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'portfolio'    => Portfolio::count(),
            'services'     => Service::count(),
            'testimonials' => Testimonial::count(),
            'messages'     => Message::where('is_read', false)->count(),
        ];
        $recentMessages = Message::latest()->take(6)->get();
        $unreadMessages = Message::where('is_read', false)->count();
        $pageTitle = 'Dashboard';
        return view('admin.dashboard', compact('stats', 'recentMessages', 'unreadMessages', 'pageTitle'));
    }
}
