@extends('layouts.app')

@section('content')

<div style="padding-top:var(--nav-h)"></div>

<!-- Header -->
<section class="section" style="padding-bottom:4rem">
    <div class="container">
        <div style="max-width:800px">
            <span class="label reveal">What We Do</span>
            <h1 class="reveal reveal-delay-1" style="margin:1rem 0 1.5rem;font-size:clamp(4rem,10vw,10rem)">Our<br><em style="font-family:var(--font-serif);font-style:italic">Services</em></h1>
            <p class="reveal reveal-delay-2" style="font-size:1.1rem;max-width:520px">Two disciplines, one vision — building brands that resonate and products that perform.</p>
        </div>
    </div>
</section>

<!-- Services List -->
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="services-grid">
            @forelse ($services as $i => $service)
            <div class="service-card reveal" style="transition-delay:{{ $i * 0.08 }}s" id="{{ Str::slug($service->title) }}">
                <div class="service-num">0{{ $i + 1 }}</div>
                <div class="service-icon">{!! $service->icon_svg !!}</div>
                <h3 style="font-family:var(--font-display);font-style:normal;letter-spacing:.05em">{{ $service->title }}</h3>
                <p style="margin:1rem 0">{{ $service->short_description }}</p>
                @if ($service->full_description)
                    <p style="font-size:.88rem;color:rgba(255,255,255,.4);line-height:1.7">{{ $service->full_description }}</p>
                @endif
                <div class="service-tags" style="margin-top:1.5rem">
                    @foreach (explode(',', $service->tags ?? '') as $tag)
                        @if (trim($tag))
                        <span class="service-tag">{{ trim($tag) }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
            @empty
            <div class="service-card" style="grid-column:span 2;text-align:center;padding:4rem">
                <p>Services coming soon.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA -->
<section style="border-top:1px solid rgba(255,255,255,.08);padding:5rem 0">
    <div class="container" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:2rem">
        <div>
            <h2 class="reveal" style="font-size:clamp(2rem,5vw,5rem)">Got a project<br><em style="font-family:var(--font-serif);font-style:italic">in mind?</em></h2>
        </div>
        <div class="reveal reveal-delay-1" style="display:flex;gap:1rem;flex-wrap:wrap">
            <a href="{{ route('contact') }}" class="btn"><span>Start a Conversation</span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
            <a href="https://wa.me/{{ $settings['whatsapp'] ?? '6281234567890' }}" target="_blank" class="btn" style="border-color:rgba(255,255,255,.2)"><span>WhatsApp Us</span></a>
        </div>
    </div>
</section>

@endsection
