<!DOCTYPE html>
<html lang="id" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    @php
        // ── SEO variables ──────────────────────────────────────────
        $seoTitle     = $seo['title']       ?? ($title ?? '1017Studios') . ' | Branding & Digital Agency';
        $seoDesc      = $seo['description'] ?? $meta_description ?? '1017Studios adalah studio branding dan teknologi di Surabaya. Kami merancang identitas brand, memproduksi video, dan membangun website serta aplikasi berkelas dunia.';
        $seoKeywords  = $seo['keywords']    ?? 'branding agency surabaya, jasa logo, desain brand, web developer surabaya, pembuatan website, jasa aplikasi, video production, 1017studios';
        $seoCanonical = $seo['canonical']   ?? url()->current();
        $seoImage     = $seo['image']       ?? asset('images/og-image.jpg');
        $seoType      = $seo['type']        ?? 'website';
        $seoRobots    = $seo['robots']      ?? 'index, follow';
        $siteName     = '1017Studios';
        $siteUrl      = config('app.url');
        $seoLocale    = 'id_ID';

        // ── Build sameAs array cleanly (no @if inside JSON) ────────
        $sameAs = [];
        if (!empty($settings['instagram'])) $sameAs[] = $settings['instagram'];
        if (!empty($settings['linkedin']))  $sameAs[] = $settings['linkedin'];
        $sameAsJson = json_encode($sameAs, JSON_UNESCAPED_SLASHES);

        // ── JSON-LD structured data built in PHP ───────────────────
        $jsonLd = [
            '@context'    => 'https://schema.org',
            '@type'       => 'ProfessionalService',
            'name'        => '1017Studios',
            'url'         => $siteUrl,
            'logo'        => asset('images/logo.png'),
            'image'       => $seoImage,
            'description' => 'Studio branding dan teknologi di Surabaya. Spesialis brand identity, video production, web development, dan app development.',
            'address'     => [
                '@type'           => 'PostalAddress',
                'addressLocality' => 'Surabaya',
                'addressRegion'   => 'Jawa Timur',
                'addressCountry'  => 'ID',
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => '-7.2575',
                'longitude' => '112.7521',
            ],
            'contactPoint' => [
                '@type'             => 'ContactPoint',
                'contactType'       => 'customer service',
                'availableLanguage' => ['Indonesian', 'English'],
            ],
            'sameAs' => $sameAs,
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name'  => 'Layanan 1017Studios',
                'itemListElement' => [
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Brand Identity & Logo Design']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Video Production & Iklan']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Web Development']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'App Development']],
                ],
            ],
        ];
        $jsonLdString = json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    @endphp

    {{-- ── Basic SEO ──────────────────────────────────────────── --}}
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta name="keywords"    content="{{ $seoKeywords }}">
    <meta name="author"      content="1017Studios">
    <meta name="robots"      content="{{ $seoRobots }}">
    <meta name="googlebot"   content="{{ $seoRobots }}">
    <link rel="canonical"    href="{{ $seoCanonical }}">

    {{-- ── Open Graph ─────────────────────────────────────────── --}}
    <meta property="og:type"        content="{{ $seoType }}">
    <meta property="og:url"         content="{{ $seoCanonical }}">
    <meta property="og:title"       content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:image"       content="{{ $seoImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height"content="630">
    <meta property="og:image:alt"   content="{{ $siteName }}">
    <meta property="og:site_name"   content="{{ $siteName }}">
    <meta property="og:locale"      content="{{ $seoLocale }}">

    {{-- ── Twitter / X Card ───────────────────────────────────── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    <meta name="twitter:image"       content="{{ $seoImage }}">
    <meta name="twitter:image:alt"   content="{{ $siteName }}">

    {{-- ── JSON-LD Structured Data (built safely in PHP above) ── --}}
    <script type="application/ld+json">{!! $jsonLdString !!}</script>

    {{-- ── Per-page structured data slot ────────────────────── --}}
    @yield('structured_data')

    {{-- ── Favicon ─────────────────────────────────────────────── --}}
    <link rel="icon"             type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="icon"             type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"               href="{{ asset('images/logo.png') }}">
    <meta name="theme-color"     content="#141414">

    {{-- ── Fonts ───────────────────────────────────────────────── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Instrument+Serif:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    {{-- ── CSS ────────────────────────────────────────────────── --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- ── Critical inline styles (cursor & base — always loads) ─ --}}
    <style>
        :root {
            --bg:#141414; --bg-2:#1c1c1c; --bg-3:#232323;
            --white:#f0ede8; --mid-gray:#888;
            --border:rgba(255,255,255,0.08);
            --accent:#d4c5a9;
            --ease:cubic-bezier(0.16,1,0.3,1);
        }
        html,body { background:var(--bg); color:var(--white); cursor:none; margin:0; padding:0; }
        .cursor {
            width:10px; height:10px; background:#f0ede8;
            border-radius:50%; position:fixed; top:0; left:0;
            pointer-events:none; z-index:999999;
            transform:translate(-50%,-50%);
            mix-blend-mode:difference; will-change:left,top;
            transition:width .18s,height .18s;
        }
        .cursor.hover { width:18px; height:18px; }
        .cursor-follower {
            width:40px; height:40px;
            border:1.5px solid rgba(240,237,232,0.28);
            border-radius:50%; position:fixed; top:0; left:0;
            pointer-events:none; z-index:999998;
            transform:translate(-50%,-50%); will-change:left,top;
            transition:width .28s var(--ease),height .28s var(--ease),border-color .28s;
        }
        .cursor-follower.hover { width:64px; height:64px; border-color:rgba(240,237,232,0.5); }
        @media(max-width:768px){
            html,body{cursor:auto;}
            .cursor,.cursor-follower{display:none!important;}
        }
    </style>
</head>
<body>

    <div class="cursor" id="cursor"></div>
    <div class="cursor-follower" id="cursorFollower"></div>
    <div class="noise-overlay"></div>

    <!-- Navigation -->
    <nav class="nav" id="mainNav">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-logo">
                <img src="{{ asset('images/logo.png') }}" alt="1017Studios" class="logo-img">
                <span class="nav-logo-tag">Branding &amp; Digital Studio</span>
            </a>
            <div class="nav-links">
                <a href="{{ route('home') }}"      class="nav-link {{ request()->routeIs('home')      ? 'active' : '' }}">Home</a>
                <a href="{{ route('services') }}"  class="nav-link {{ request()->routeIs('services')  ? 'active' : '' }}">Services</a>
                <a href="{{ route('portfolio') }}" class="nav-link {{ request()->routeIs('portfolio') ? 'active' : '' }}">Work</a>
                <a href="{{ route('about') }}"     class="nav-link {{ request()->routeIs('about')     ? 'active' : '' }}">About</a>
                <a href="{{ route('contact') }}"   class="nav-cta">Let's Talk</a>
            </div>
            <button class="nav-hamburger" id="hamburger" aria-label="Menu">
                <span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-inner">
            <a href="{{ route('home') }}"      class="mobile-link">Home</a>
            <a href="{{ route('services') }}"  class="mobile-link">Services</a>
            <a href="{{ route('portfolio') }}" class="mobile-link">Work</a>
            <a href="{{ route('about') }}"     class="mobile-link">About</a>
            <a href="{{ route('contact') }}"   class="mobile-link">Contact</a>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="{{ asset('images/logo.png') }}" alt="1017Studios" class="footer-logo">
                <p class="footer-tagline">{{ $settings['tagline'] ?? 'We build brands that move people.' }}</p>
            </div>
            <div class="footer-col">
                <h4 class="footer-heading">Services</h4>
                <a href="{{ route('services') }}" class="footer-link">Brand Identity</a>
                <a href="{{ route('services') }}" class="footer-link">Video Production</a>
                <a href="{{ route('services') }}" class="footer-link">Web Development</a>
                <a href="{{ route('services') }}" class="footer-link">App Development</a>
            </div>
            <div class="footer-col">
                <h4 class="footer-heading">Company</h4>
                <a href="{{ route('about') }}"     class="footer-link">About Us</a>
                <a href="{{ route('portfolio') }}" class="footer-link">Our Work</a>
                <a href="{{ route('contact') }}"   class="footer-link">Contact</a>
            </div>
            <div class="footer-col">
                <h4 class="footer-heading">Get In Touch</h4>
                <a href="https://wa.me/{{ $settings['whatsapp'] ?? '6281234567890' }}" class="footer-link" target="_blank" rel="noopener">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    WhatsApp Us
                </a>
                <a href="mailto:{{ $settings['email'] ?? 'hello@1017studios.com' }}" class="footer-link">
                    {{ $settings['email'] ?? 'hello@1017studios.com' }}
                </a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} 1017Studios. All rights reserved.</p>
            <p class="footer-credit">Crafted with intention.</p>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/{{ $settings['whatsapp'] ?? '6281234567890' }}" class="wa-float" target="_blank" rel="noopener" title="Chat via WhatsApp">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
        <span>Chat Now</span>
    </a>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
