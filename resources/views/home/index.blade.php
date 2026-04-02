@extends('layouts.app')

@section('content')

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="hero-bg"></div>
    <!-- Grid lines -->
    <div class="hero-grid-line v" style="left:20%"></div>
    <div class="hero-grid-line v" style="left:50%"></div>
    <div class="hero-grid-line v" style="left:80%"></div>
    <div class="hero-grid-line h" style="top:33%"></div>
    <div class="hero-grid-line h" style="top:66%"></div>

    <div class="hero-content">
        <div class="hero-eyebrow">
            <div class="hero-eyebrow-line"></div>
            <span class="label">Est. 2019 &nbsp;·&nbsp; Surabaya, Indonesia</span>
        </div>

        <h1 class="hero-title">
            <span>WE</span>
            <span class="italic">Build</span>
            <span>Brands.</span>
        </h1>

        <div class="hero-sub">
            <p class="hero-desc">From identity to interface — we design, develop, and launch digital experiences that leave lasting impressions.</p>
            <div class="hero-actions">
                <a href="{{ route('portfolio') }}" class="btn">
                    <span>See Our Work</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="https://wa.me/{{ $settings['whatsapp'] ?? '6281234567890' }}" class="btn" style="border-color:rgba(255,255,255,0.2);" target="_blank">
                    <span>Get In Touch</span>
                </a>
            </div>
        </div>
    </div>

    <div class="hero-scroll-indicator">
        <div class="hero-scroll-line"></div>
        <span class="hero-scroll-text">Scroll</span>
    </div>
</section>

<!-- ===== MARQUEE ===== -->
<div class="marquee-section">
    <div class="marquee-track">
        <div class="marquee-item">Brand Identity <span class="marquee-dot"></span></div>
        <div class="marquee-item">Logo Design <span class="marquee-dot"></span></div>
        <div class="marquee-item">Video Production <span class="marquee-dot"></span></div>
        <div class="marquee-item">Web Development <span class="marquee-dot"></span></div>
        <div class="marquee-item">Mobile Apps <span class="marquee-dot"></span></div>
        <div class="marquee-item">Digital Advertising <span class="marquee-dot"></span></div>
        <div class="marquee-item">UI/UX Design <span class="marquee-dot"></span></div>
        <div class="marquee-item">Motion Graphics <span class="marquee-dot"></span></div>
    </div>
</div>

<!-- ===== SERVICES PREVIEW ===== -->
<section class="section" id="services">
    <div class="container">
        <div class="services-header">
            <div>
                <span class="label reveal">What We Do</span>
                <h2 class="reveal reveal-delay-1">Our<br><em style="font-family:var(--font-serif);font-style:italic">Services</em></h2>
            </div>
            <div>
                <p class="reveal reveal-delay-2" style="margin-bottom:1.5rem">We specialize in two powerful disciplines: building unforgettable brand identities, and engineering high-performance digital products.</p>
                <a href="{{ route('services') }}" class="btn reveal reveal-delay-3"><span>All Services</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <div class="services-grid">
            @foreach ($services as $i => $service)
            <div class="service-card reveal" style="transition-delay: {{ $i * 0.1 }}s">
                <div class="service-num">0{{ $i + 1 }}</div>
                <div class="service-icon">
                    {!! $service->icon_svg !!}
                </div>
                <h3>{{ $service->title }}</h3>
                <p>{{ $service->short_description }}</p>
                <div class="service-tags">
                    @foreach (explode(',', $service->tags) as $tag)
                    <span class="service-tag">{{ trim($tag) }}</span>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== PORTFOLIO PREVIEW ===== -->
<section class="section" id="work" style="padding-top:0">
    <div class="container">
        <div class="portfolio-header" style="display:flex;justify-content:space-between;align-items:flex-end">
            <div>
                <span class="label reveal">Selected Work</span>
                <h2 class="reveal reveal-delay-1">Our<br><em style="font-family:var(--font-serif);font-style:italic">Portfolio</em></h2>
            </div>
            <a href="{{ route('portfolio') }}" class="btn reveal reveal-delay-2"><span>View All</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="portfolio-grid">
            @forelse ($portfolios as $item)
            <div class="portfolio-item reveal">
                @if ($item->thumbnail)
                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" class="portfolio-img">
                @else
                    <div class="portfolio-img portfolio-placeholder">
                        <div class="portfolio-placeholder-inner">
                            <p style="font-family:var(--font-display);font-size:1rem;letter-spacing:.1em">{{ strtoupper($item->title) }}</p>
                        </div>
                    </div>
                @endif
                <div class="portfolio-overlay">
                    <span class="label" style="margin-bottom:.5rem">{{ $item->category }}</span>
                    <h4>{{ $item->title }}</h4>
                </div>
            </div>
            @empty
            @for ($i = 0; $i < 5; $i++)
            <div class="portfolio-item reveal">
                <div class="portfolio-img" style="background:rgb({{ 15 + $i*8 }},{{ 15 + $i*8 }},{{ 15 + $i*8 }});display:flex;align-items:center;justify-content:center">
                    <span style="font-family:var(--font-display);letter-spacing:.1em;opacity:.2;font-size:1.2rem">PROJECT {{ $i + 1 }}</span>
                </div>
            </div>
            @endfor
            @endforelse
        </div>
    </div>
</section>

<!-- ===== STATS ===== -->
<section class="section stats-section">
    <div class="container">
        <div class="stats-inner">
            <div class="stats-text">
                <span class="label reveal" style="color:rgba(0,0,0,0.4)">By the Numbers</span>
                <h2 class="reveal reveal-delay-1" style="color:#000">Results<br><em style="font-family:var(--font-serif);font-style:italic">Speak</em></h2>
                <p class="reveal reveal-delay-2" style="margin-top:1.5rem">We measure success by the impact we create — for our clients, their customers, and their bottom line.</p>
            </div>
            <div class="stats-grid">
                <div class="stat-item reveal">
                    <div class="stat-num" data-count="{{ $stats['projects'] ?? 150 }}" data-suffix="+">0+</div>
                    <div class="stat-label">Projects Delivered</div>
                </div>
                <div class="stat-item reveal reveal-delay-1">
                    <div class="stat-num" data-count="{{ $stats['clients'] ?? 80 }}" data-suffix="+">0+</div>
                    <div class="stat-label">Happy Clients</div>
                </div>
                <div class="stat-item reveal reveal-delay-2">
                    <div class="stat-num" data-count="{{ $stats['years'] ?? 5 }}" data-suffix="">0</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
                <div class="stat-item reveal reveal-delay-3">
                    <div class="stat-num" data-count="{{ $stats['satisfaction'] ?? 98 }}" data-suffix="%">0%</div>
                    <div class="stat-label">Client Satisfaction</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
@if ($testimonials->count() > 0)
<section class="section">
    <div class="container">
        <div style="margin-bottom:4rem;display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1rem">
            <div>
                <span class="label reveal">Testimonials</span>
                <h2 class="reveal reveal-delay-1">What Clients<br><em style="font-family:var(--font-serif);font-style:italic">Say</em></h2>
            </div>
        </div>

        <div class="testimonials-grid">
            @foreach ($testimonials->take(3) as $t)
            <div class="testimonial-card reveal" style="transition-delay:{{ $loop->index * 0.1 }}s">
                <p class="testimonial-quote">"{{ $t->quote }}"</p>
                <div class="testimonial-author">
                    @if ($t->avatar)
                        <img src="{{ asset('storage/' . $t->avatar) }}" alt="{{ $t->name }}" class="testimonial-avatar">
                    @else
                        <div class="testimonial-avatar" style="display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.1rem">{{ substr($t->name, 0, 1) }}</div>
                    @endif
                    <div>
                        <div class="testimonial-name">{{ $t->name }}</div>
                        <div class="testimonial-role">{{ $t->role }}, {{ $t->company }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===== CTA BAND ===== -->
<section style="border-top:1px solid rgba(255,255,255,0.08);border-bottom:1px solid rgba(255,255,255,0.08);padding:5rem 0">
    <div class="container" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:2rem">
        <div>
            <h2 class="reveal" style="font-size:clamp(2.5rem,6vw,6rem)">Ready to<br><em style="font-family:var(--font-serif);font-style:italic">Build?</em></h2>
        </div>
        <div class="reveal reveal-delay-1" style="display:flex;gap:1rem;flex-wrap:wrap">
            <a href="{{ route('contact') }}" class="btn"><span>Start a Project</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="https://wa.me/{{ $settings['whatsapp'] ?? '6281234567890' }}" target="_blank" class="btn" style="border-color:rgba(255,255,255,0.2)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                <span>WhatsApp Us</span>
            </a>
        </div>
    </div>
</section>

@endsection
