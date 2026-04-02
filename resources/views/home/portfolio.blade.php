@extends('layouts.app')

@section('structured_data')
@php
    $itemListElement = [];
    foreach ($portfolios as $index => $item) {
        $itemListElement[] = [
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'name'     => $item->title,
            'description' => $item->description ?? $item->category ?? '',
        ];
    }
    $portfolioJsonLd = json_encode([
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => 'Portfolio 1017Studios',
        'url'             => route('portfolio'),
        'itemListElement' => $itemListElement,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<script type="application/ld+json">{!! $portfolioJsonLd !!}</script>
@endsection

@section('content')

<div style="padding-top:var(--nav-h)"></div>

<section class="section" style="padding-bottom:4rem">
    <div class="container">
        {{-- Header --}}
        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:2rem;margin-bottom:3rem">
            <div>
                <span class="label reveal">Our Work</span>
                <h1 class="reveal reveal-delay-1" style="font-size:clamp(3.5rem,9vw,9rem)">Selected<br><em style="font-family:var(--font-serif);font-style:italic">Portfolio</em></h1>
            </div>
            @if ($categories->isNotEmpty())
            <div class="reveal reveal-delay-2" style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center">
                <a href="{{ route('portfolio') }}"
                   style="display:inline-block;padding:7px 16px;font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;border:1px solid {{ !request('category') ? 'rgba(240,237,232,.5)' : 'rgba(240,237,232,.12)' }};color:{{ !request('category') ? 'var(--white)' : 'rgba(240,237,232,.4)' }};transition:all .2s;border-radius:1px">
                    All
                </a>
                @foreach ($categories as $cat)
                <a href="{{ route('portfolio', ['category' => $cat]) }}"
                   style="display:inline-block;padding:7px 16px;font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;border:1px solid {{ request('category') === $cat ? 'rgba(240,237,232,.5)' : 'rgba(240,237,232,.12)' }};color:{{ request('category') === $cat ? 'var(--white)' : 'rgba(240,237,232,.4)' }};transition:all .2s;border-radius:1px">
                    {{ $cat }}
                </a>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Grid --}}
        <div class="portfolio-grid">
            @forelse ($portfolios as $item)
            @php
                $hasImage   = !empty($item->thumbnail);
                $hasWebLink = !empty($item->project_url);
                $imgSrc     = $hasImage ? asset('storage/'.$item->thumbnail) : null;

                // Determine click behavior:
                // - Punya thumbnail & TIDAK punya URL  → lightbox preview gambar
                // - Punya URL (web project)            → new tab ke project_url
                // - Tidak keduanya                     → tidak ada aksi
                $isWebProject    = $hasWebLink;
                $isImagePreview  = $hasImage && !$hasWebLink;
            @endphp

            <div class="portfolio-item reveal"
                 style="transition-delay:{{ ($loop->index % 4) * 0.08 }}s;{{ ($isWebProject || $isImagePreview) ? 'cursor:pointer' : '' }}"
                 @if($isImagePreview)
                     onclick="openLightbox('{{ $imgSrc }}', '{{ addslashes($item->title) }}', '{{ addslashes($item->category ?? '') }}')"
                 @elseif($isWebProject)
                     onclick="window.open('{{ $item->project_url }}', '_blank', 'noopener')"
                 @endif
            >
                @if ($hasImage)
                    <img src="{{ $imgSrc }}" alt="{{ $item->title }}" class="portfolio-img" loading="lazy">
                @else
                    <div class="portfolio-img" style="background:rgb({{ 22 + $loop->index * 4 }},{{ 22 + $loop->index * 4 }},{{ 22 + $loop->index * 4 }});display:flex;align-items:center;justify-content:center">
                        <span style="font-family:var(--font-display);letter-spacing:.1em;opacity:.15;font-size:1.2rem">{{ strtoupper(substr($item->title,0,2)) }}</span>
                    </div>
                @endif

                <div class="portfolio-overlay">
                    <span class="label" style="margin-bottom:.4rem;color:rgba(240,237,232,.5)">{{ $item->category }}</span>
                    <h4 style="font-family:var(--font-display);font-size:1.4rem;letter-spacing:.05em;margin-bottom:.3rem;color:var(--white)">{{ $item->title }}</h4>
                    @if ($item->client)
                        <p style="font-size:.78rem;color:rgba(240,237,232,.45);margin-bottom:.5rem">{{ $item->client }}</p>
                    @endif

                    {{-- Action indicator --}}
                    @if ($isWebProject)
                    <div style="display:inline-flex;align-items:center;gap:5px;font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--white);border-bottom:1px solid rgba(240,237,232,.3);padding-bottom:2px;margin-top:.25rem">
                        Buka Website
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    </div>
                    @elseif ($isImagePreview)
                    <div style="display:inline-flex;align-items:center;gap:5px;font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--white);border-bottom:1px solid rgba(240,237,232,.3);padding-bottom:2px;margin-top:.25rem">
                        Preview
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div style="grid-column:span 12;text-align:center;padding:6rem 0;color:rgba(240,237,232,.2)">
                <p style="font-family:var(--font-display);font-size:2rem;margin-bottom:1rem;letter-spacing:.1em">COMING SOON</p>
                <p style="font-size:.9rem">Kami sedang menyusun portofolio terbaik kami.</p>
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

{{-- ============================================================
     LIGHTBOX MODAL — image preview
     ============================================================ --}}
<div id="lightbox" aria-modal="true" role="dialog"
     style="display:none;position:fixed;inset:0;z-index:99990;
            background:rgba(10,10,10,0.95);backdrop-filter:blur(12px);
            align-items:center;justify-content:center;padding:2rem;
            flex-direction:column;">

    {{-- Close button --}}
    <button id="lbClose"
            style="position:absolute;top:1.5rem;right:1.5rem;
                   background:none;border:1px solid rgba(240,237,232,.2);
                   color:var(--white);width:44px;height:44px;border-radius:50%;
                   font-size:1.1rem;cursor:pointer;display:flex;align-items:center;
                   justify-content:center;transition:background .2s;z-index:2;">
        ✕
    </button>

    {{-- Image wrapper — clicking inside here does NOT close --}}
    <div id="lbImgWrap" style="position:relative;max-width:90vw;max-height:80vh;z-index:1;">
        <img id="lightboxImg" src="" alt=""
             style="max-width:100%;max-height:78vh;object-fit:contain;display:block;border-radius:2px;">
    </div>

    {{-- Caption --}}
    <div style="margin-top:1.2rem;text-align:center;z-index:1;pointer-events:none;">
        <div id="lightboxTitle" style="font-family:var(--font-display);font-size:1.4rem;letter-spacing:.08em;color:var(--white)"></div>
        <div id="lightboxCat"   style="font-size:.72rem;letter-spacing:.16em;text-transform:uppercase;color:var(--mid-gray);margin-top:.3rem"></div>
        <p style="font-size:.74rem;color:rgba(240,237,232,.22);margin-top:.75rem">Klik di luar gambar atau tekan ESC untuk menutup</p>
    </div>
</div>

<style>
#lightbox.lb-open  { display:flex !important; }
#lightbox          { animation:none; }
#lightbox.lb-open  { animation: lbFadeIn .22s ease forwards; }
@keyframes lbFadeIn  { from{opacity:0} to{opacity:1} }
#lbImgWrap img     { animation: lbScaleIn .3s cubic-bezier(0.16,1,0.3,1) forwards; }
@keyframes lbScaleIn { from{transform:scale(0.92);opacity:0} to{transform:scale(1);opacity:1} }
#lbClose:hover     { background:rgba(255,255,255,.12) !important; }
.portfolio-item[onclick] { cursor:pointer; }
</style>

<script>
(function () {
    var lb      = document.getElementById('lightbox');
    var lbImg   = document.getElementById('lightboxImg');
    var lbTitle = document.getElementById('lightboxTitle');
    var lbCat   = document.getElementById('lightboxCat');
    var lbWrap  = document.getElementById('lbImgWrap');
    var lbClose = document.getElementById('lbClose');

    // Open
    window.openLightbox = function (src, title, category) {
        lbImg.src         = src;
        lbImg.alt         = title;
        lbTitle.textContent = title;
        lbCat.textContent   = category;
        lb.style.display  = 'flex';
        // rAF so display:flex renders before class triggers animation
        requestAnimationFrame(function () {
            lb.classList.add('lb-open');
        });
        document.body.style.overflow = 'hidden';
    };

    // Close
    function closeLightbox() {
        lb.classList.remove('lb-open');
        lb.style.display = 'none';
        lbImg.src        = '';
        document.body.style.overflow = '';
    }

    // Close button
    lbClose.addEventListener('click', function (e) {
        e.stopPropagation();
        closeLightbox();
    });

    // Click on backdrop (not on image or caption)
    lb.addEventListener('click', function (e) {
        // if click target is the lightbox itself (backdrop), close
        if (e.target === lb) {
            closeLightbox();
        }
    });

    // Stop image/caption clicks from bubbling to backdrop
    lbWrap.addEventListener('click', function (e) { e.stopPropagation(); });

    // ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && lb.classList.contains('lb-open')) {
            closeLightbox();
        }
    });
})();
</script>

@endsection
