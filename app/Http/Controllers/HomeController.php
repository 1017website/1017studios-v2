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
        $settings     = $this->getSettings();
        $services     = Service::where('is_active', true)->orderBy('order')->get();
        $portfolios   = Portfolio::where('is_active', true)->where('is_featured', true)->orderBy('order')->take(5)->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('order')->get();
        $stats = [
            'projects'     => $settings['stat_projects']     ?? 150,
            'clients'      => $settings['stat_clients']      ?? 80,
            'years'        => $settings['stat_years']        ?? 5,
            'satisfaction' => $settings['stat_satisfaction'] ?? 98,
        ];
        $seo = [
            'title'       => '1017Studios | Branding & Digital Agency Surabaya',
            'description' => 'Studio branding dan teknologi di Surabaya. Kami merancang brand identity, memproduksi video iklan, dan membangun website serta aplikasi berkelas dunia.',
            'keywords'    => 'branding agency surabaya, jasa logo surabaya, web developer surabaya, pembuatan website profesional, jasa aplikasi mobile, video production surabaya, 1017studios',
            'canonical'   => url('/'),
            'type'        => 'website',
        ];

        return view('home.index', compact('settings', 'services', 'portfolios', 'testimonials', 'stats', 'seo'));
    }

    public function services()
    {
        $settings = $this->getSettings();
        $services = Service::where('is_active', true)->orderBy('order')->get();
        $seo = [
            'title'       => 'Layanan Kami | 1017Studios — Branding & Web Development Surabaya',
            'description' => 'Kami menawarkan jasa brand identity, logo design, video production, web development, dan app development. Studio kreatif dan teknologi terpercaya di Surabaya.',
            'keywords'    => 'jasa branding surabaya, jasa logo design, video production surabaya, web development surabaya, jasa pembuatan aplikasi surabaya, agency kreatif',
            'canonical'   => route('services'),
            'type'        => 'website',
        ];

        return view('home.services', compact('settings', 'services', 'seo'));
    }

    public function portfolio(Request $request)
    {
        $settings   = $this->getSettings();
        $query      = Portfolio::where('is_active', true)->orderBy('order');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $portfolios  = $query->paginate(12);
        $categories  = Portfolio::where('is_active', true)->distinct()->pluck('category')->filter();

        $seo = [
            'title'       => 'Portfolio | 1017Studios — Hasil Karya Branding & Digital',
            'description' => 'Lihat hasil karya terbaik 1017Studios — mulai dari logo, brand identity, website, aplikasi, hingga video production untuk berbagai klien.',
            'keywords'    => 'portfolio branding surabaya, contoh logo design, portfolio web developer, portofolio agency kreatif surabaya',
            'canonical'   => route('portfolio'),
            'type'        => 'website',
        ];

        return view('home.portfolio', compact('settings', 'portfolios', 'categories', 'seo'));
    }

    public function about()
    {
        $settings     = $this->getSettings();
        $testimonials = Testimonial::where('is_active', true)->orderBy('order')->get();
        $seo = [
            'title'       => 'Tentang Kami | 1017Studios — Studio Kreatif & Teknologi Surabaya',
            'description' => '1017Studios adalah studio branding dan teknologi berbasis di Surabaya. Kami percaya bahwa desain dan teknologi yang baik adalah fondasi bisnis yang sukses.',
            'keywords'    => 'tentang 1017studios, studio branding surabaya, agency kreatif surabaya, digital agency indonesia',
            'canonical'   => route('about'),
            'type'        => 'website',
        ];

        return view('home.about', compact('settings', 'testimonials', 'seo'));
    }

    public function contact()
    {
        $settings = $this->getSettings();
        $services = Service::where('is_active', true)->pluck('title');
        $seo = [
            'title'       => 'Hubungi Kami | 1017Studios — Konsultasi Branding & Digital Gratis',
            'description' => 'Punya proyek atau ide? Hubungi 1017Studios untuk konsultasi gratis. Kami siap membantu mewujudkan brand dan produk digital impianmu.',
            'keywords'    => 'kontak 1017studios, konsultasi branding surabaya, hubungi web developer surabaya, whatsapp agency surabaya',
            'canonical'   => route('contact'),
            'type'        => 'website',
        ];

        return view('home.contact', compact('settings', 'services', 'seo'));
    }

    public function sitemap()
    {
        $portfolios = Portfolio::where('is_active', true)->orderBy('updated_at', 'desc')->get();
        $lastmod    = now()->toAtomString();

        $xml = response()->view('seo.sitemap', compact('portfolios', 'lastmod'))
                         ->header('Content-Type', 'application/xml');
        return $xml;
    }

    public function robots()
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /admin/*\n\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
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
