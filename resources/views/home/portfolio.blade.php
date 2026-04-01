@extends('layouts.app')

@section('content')

<div style="padding-top:var(--nav-h)"></div>

<section class="section" style="padding-bottom:3rem">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:2rem;margin-bottom:3rem">
            <div>
                <span class="label reveal">Our Work</span>
                <h1 class="reveal reveal-delay-1" style="font-size:clamp(3.5rem,9vw,9rem)">Selected<br><em style="font-family:var(--font-serif);font-style:italic">Portfolio</em></h1>
            </div>
            @if ($categories->isNotEmpty())
            <div class="reveal reveal-delay-2" style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center">
                <a href="{{ route('portfolio') }}" class="service-tag {{ !request('category') ? 'nav-link active' : '' }}" style="padding:8px 16px;border:1px solid {{ !request('category') ? 'rgba(255,255,255,.5)' : 'rgba(255,255,255,.12)' }};color:{{ !request('category') ? 'var(--white)' : 'rgba(255,255,255,.4)' }}">All</a>
                @foreach ($categories as $cat)
                <a href="{{ route('portfolio', ['category' => $cat]) }}" class="service-tag" style="padding:8px 16px;border:1px solid {{ request('category') === $cat ? 'rgba(255,255,255,.5)' : 'rgba(255,255,255,.12)' }};color:{{ request('category') === $cat ? 'var(--white)' : 'rgba(255,255,255,.4)' }}">{{ $cat }}</a>
                @endforeach
            </div>
            @endif
        </div>

        <div class="portfolio-grid">
            @forelse ($portfolios as $item)
            <div class="portfolio-item reveal" style="transition-delay:{{ ($loop->index % 4) * 0.08 }}s">
                @if ($item->thumbnail)
                    <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" class="portfolio-img">
                @else
                    <div class="portfolio-img" style="background:rgb({{ 18 + $loop->index * 5 }},{{ 18 + $loop->index * 5 }},{{ 18 + $loop->index * 5 }});display:flex;align-items:center;justify-content:center">
                        <span style="font-family:var(--font-display);letter-spacing:.1em;opacity:.2">{{ strtoupper(substr($item->title,0,2)) }}</span>
                    </div>
                @endif
                <div class="portfolio-overlay">
                    <span class="label" style="margin-bottom:.4rem">{{ $item->category }}</span>
                    <h4 style="font-family:var(--font-display);font-size:1.4rem;letter-spacing:.05em;margin-bottom:.3rem">{{ $item->title }}</h4>
                    @if ($item->client)<p style="font-size:.8rem;color:rgba(255,255,255,.5)">{{ $item->client }}</p>@endif
                    @if ($item->project_url)
                    <a href="{{ $item->project_url }}" target="_blank" style="display:inline-flex;align-items:center;gap:6px;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:var(--white);border-bottom:1px solid rgba(255,255,255,.3);margin-top:.75rem;padding-bottom:2px">
                        View Project <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div style="grid-column:span 12;text-align:center;padding:5rem 0;color:rgba(255,255,255,.3)">
                <p style="font-family:var(--font-display);font-size:2rem;margin-bottom:1rem">COMING SOON</p>
                <p>We're curating our portfolio. Check back soon.</p>
            </div>
            @endforelse
        </div>

        @if ($portfolios->hasPages())
        <div style="margin-top:3rem;display:flex;justify-content:center">
            {{ $portfolios->links() }}
        </div>
        @endif
    </div>
</section>

@endsection
