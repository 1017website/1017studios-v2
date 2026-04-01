<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Message;
use App\Models\Setting;

class HomeController extends Controller
{
    private function getSettings(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    public function index()
    {
        $settings  = $this->getSettings();
        $services  = Service::where('is_active', true)->orderBy('order')->get();
        $portfolios = Portfolio::where('is_active', true)
                               ->where('is_featured', true)
                               ->orderBy('order')
                               ->take(5)
                               ->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('order')->get();
        $stats = [
            'projects'     => $settings['stat_projects']     ?? 150,
            'clients'      => $settings['stat_clients']      ?? 80,
            'years'        => $settings['stat_years']        ?? 5,
            'satisfaction' => $settings['stat_satisfaction'] ?? 98,
        ];

        return view('home.index', compact('settings', 'services', 'portfolios', 'testimonials', 'stats'));
    }

    public function services()
    {
        $settings = $this->getSettings();
        $services = Service::where('is_active', true)->orderBy('order')->get();
        return view('home.services', compact('settings', 'services'));
    }

    public function portfolio(Request $request)
    {
        $settings   = $this->getSettings();
        $query      = Portfolio::where('is_active', true)->orderBy('order');
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $portfolios  = $query->paginate(12);
        $categories = Portfolio::where('is_active', true)->distinct()->pluck('category')->filter();
        return view('home.portfolio', compact('settings', 'portfolios', 'categories'));
    }

    public function about()
    {
        $settings = $this->getSettings();
        $testimonials = Testimonial::where('is_active', true)->orderBy('order')->get();
        return view('home.about', compact('settings', 'testimonials'));
    }

    public function contact()
    {
        $settings = $this->getSettings();
        $services = Service::where('is_active', true)->pluck('title');
        return view('home.contact', compact('settings', 'services'));
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'service' => 'nullable|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        Message::create($validated);

        return redirect()
            ->route('contact')
            ->with('success', 'Terima kasih! Pesan Anda telah kami terima. Kami akan menghubungi Anda segera.');
    }
}
