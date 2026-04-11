@extends('layouts.app')

@section('content')

<div style="padding-top:var(--nav-h)"></div>

<!-- Hero -->
<section class="section" style="padding-bottom:3rem">
    <div class="container">
        <span class="label reveal">Google Reviews</span>
        <h1 class="reveal reveal-delay-1" style="font-size:clamp(3rem,8vw,8rem);margin:.75rem 0 2rem">
            Kata Mereka<br><em style="font-family:var(--font-serif);font-style:italic">Tentang Kami</em>
        </h1>

        @if($error)
            <div style="border:1px solid rgba(255,255,255,.1);padding:2rem;max-width:600px;color:rgba(255,255,255,.5);font-size:.9rem">
                ⚠️ {{ $error }}
            </div>

        @elseif($budget_exceeded)
            {{-- Budget guard triggered — tampilan ramah untuk pengunjung --}}
            <div style="border:1px solid rgba(255,200,100,.15);padding:2.5rem;max-width:640px;background:rgba(255,200,100,.03)">
                <div style="font-size:1.1rem;margin-bottom:.75rem">Ulasan Sementara Tidak Tersedia</div>
                <p style="color:rgba(255,255,255,.5);font-size:.9rem;line-height:1.7;margin:0 0 1.5rem">
                    Kami membatasi penggunaan API untuk menjaga biaya tetap nol.
                    Ulasan Google akan kembali tersedia bulan depan — atau segera setelah admin mereset limit.
                </p>
                <a href="https://search.google.com/local/reviews?placeid={{ config('services.google.place_id') }}"
                   target="_blank" rel="noopener noreferrer"
                   style="font-size:.82rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.5);text-decoration:none;border-bottom:1px solid rgba(255,255,255,.2);padding-bottom:2px">
                    Lihat langsung di Google Maps ↗
                </a>
            </div>

        @else
            {{-- Place summary --}}
            @if($place)
            <div class="reveal reveal-delay-2" style="display:inline-flex;align-items:center;gap:2rem;border:1px solid rgba(255,255,255,.1);padding:1.5rem 2.5rem;margin-bottom:4rem">
                <div>
                    <div style="font-size:.75rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:.4rem">{{ $place['name'] }}</div>
                    <div style="display:flex;align-items:center;gap:.75rem">
                        <span style="font-size:2.5rem;font-family:var(--font-display);letter-spacing:-.02em">{{ number_format($place['rating'], 1) }}</span>
                        <div>
                            <div style="display:flex;gap:3px;margin-bottom:.3rem">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                         fill="{{ $i <= floor($place['rating']) ? '#FBBC04' : 'rgba(255,255,255,.15)' }}">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                @endfor
                            </div>
                            <div style="font-size:.8rem;color:rgba(255,255,255,.4)">{{ number_format($place['total']) }} ulasan di Google</div>
                        </div>
                    </div>
                </div>
                <div style="width:1px;height:60px;background:rgba(255,255,255,.08)"></div>
                <a href="{{ $place['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                   style="display:inline-flex;align-items:center;gap:.6rem;font-size:.82rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.6);text-decoration:none;transition:color .2s"
                   onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    Lihat di Google Maps
                </a>
            </div>
            @endif
        @endif
    </div>
</section>

@if(!$error && !$budget_exceeded && count($reviews) > 0)

{{-- Filter --}}
<section style="padding-bottom:2rem">
    <div class="container">
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center">
            <span style="font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.35);margin-right:.5rem">Filter:</span>
            <button onclick="filterReviews(0)" id="filter-0" class="review-filter-btn active">Semua</button>
            @foreach([5,4,3,2,1] as $star)
            <button onclick="filterReviews({{ $star }})" id="filter-{{ $star }}" class="review-filter-btn">{{ $star }} ★</button>
            @endforeach
        </div>
    </div>
</section>

{{-- Reviews Grid --}}
<section class="section" style="padding-top:0">
    <div class="container">
        <div id="reviews-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.5rem">
            @foreach($reviews as $review)
            <div class="review-card reveal" data-rating="{{ $review['rating'] }}"
                 style="border:1px solid rgba(255,255,255,.08);padding:2rem;transition:border-color .3s"
                 onmouseover="this.style.borderColor='rgba(255,255,255,.2)'"
                 onmouseout="this.style.borderColor='rgba(255,255,255,.08)'">

                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem">
                    @if($review['photo'])
                        <img src="{{ $review['photo'] }}" alt="{{ $review['author'] }}"
                             style="width:44px;height:44px;border-radius:50%;object-fit:cover">
                    @else
                        <div style="width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.1rem;flex-shrink:0">
                            {{ strtoupper(substr($review['author'], 0, 1)) }}
                        </div>
                    @endif
                    <div style="flex:1;min-width:0">
                        <a href="{{ $review['profile_url'] }}" target="_blank" rel="noopener noreferrer"
                           style="font-size:.9rem;font-weight:500;color:var(--white);text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ $review['author'] }}
                        </a>
                        <div style="font-size:.75rem;color:rgba(255,255,255,.35);margin-top:2px">{{ $review['time'] }}</div>
                    </div>
                    <svg width="18" height="18" viewBox="0 0 24 24" style="flex-shrink:0;opacity:.3">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                </div>

                <div style="display:flex;gap:3px;margin-bottom:1rem">
                    @for($i = 1; $i <= 5; $i++)
                        <svg width="14" height="14" viewBox="0 0 24 24"
                             fill="{{ $i <= $review['rating'] ? '#FBBC04' : 'rgba(255,255,255,.15)' }}">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    @endfor
                </div>

                @if($review['text'])
                <p class="review-text" style="font-size:.88rem;line-height:1.75;color:rgba(255,255,255,.7);margin:0;display:-webkit-box;-webkit-line-clamp:5;-webkit-box-orient:vertical;overflow:hidden">
                    {{ $review['text'] }}
                </p>
                @if(strlen($review['text']) > 280)
                <button onclick="toggleReview(this)" style="margin-top:.6rem;background:none;border:none;color:rgba(255,255,255,.4);font-size:.78rem;cursor:pointer;padding:0;font-family:inherit;letter-spacing:.05em;text-transform:uppercase"
                    onmouseover="this.style.color='rgba(255,255,255,.8)'" onmouseout="this.style.color='rgba(255,255,255,.4)'">
                    Baca selengkapnya ↓
                </button>
                @endif
                @else
                <p style="font-size:.85rem;color:rgba(255,255,255,.3);font-style:italic;margin:0">— Tanpa komentar tertulis —</p>
                @endif
            </div>
            @endforeach
        </div>

        <div id="no-results" style="display:none;text-align:center;padding:4rem;color:rgba(255,255,255,.3)">
            Tidak ada ulasan dengan rating ini.
        </div>

        @if($place)
        <div style="text-align:center;margin-top:5rem;padding-top:4rem;border-top:1px solid rgba(255,255,255,.06)">
            <p class="reveal" style="color:rgba(255,255,255,.4);margin-bottom:1.5rem;font-size:.95rem">
                Pernah bekerja sama dengan kami? Kami sangat menghargai ulasan Anda.
            </p>
            <a href="{{ $place['maps_url'] }}" target="_blank" rel="noopener noreferrer" class="btn reveal reveal-delay-1">
                Tulis Ulasan di Google
            </a>
        </div>
        @endif
    </div>
</section>

@elseif(!$error && !$budget_exceeded && count($reviews) === 0)
<section class="section" style="padding-top:0">
    <div class="container" style="text-align:center;color:rgba(255,255,255,.4);padding:4rem 0">
        Belum ada ulasan yang tersedia.
        @if($place)
        <br><br>
        <a href="{{ $place['maps_url'] }}" target="_blank" rel="noopener noreferrer" class="btn">
            Jadilah yang Pertama — Tulis Ulasan
        </a>
        @endif
    </div>
</section>
@endif

<style>
.review-filter-btn {
    padding:.4rem 1rem;font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;
    border:1px solid rgba(255,255,255,.12);background:transparent;color:rgba(255,255,255,.4);
    cursor:pointer;font-family:inherit;transition:all .2s;
}
.review-filter-btn.active,.review-filter-btn:hover {
    background:rgba(255,255,255,.08);color:var(--white);border-color:rgba(255,255,255,.3);
}
.review-card.hidden { display:none !important; }
</style>

<script>
function filterReviews(rating) {
    document.querySelectorAll('.review-filter-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('filter-' + rating).classList.add('active');
    const cards = document.querySelectorAll('.review-card');
    let visible = 0;
    cards.forEach(card => {
        const show = rating === 0 || parseInt(card.dataset.rating) === rating;
        card.classList.toggle('hidden', !show);
        if (show) visible++;
    });
    document.getElementById('no-results').style.display = visible === 0 ? 'block' : 'none';
}
function toggleReview(btn) {
    const p = btn.previousElementSibling;
    const expanded = p.style.webkitLineClamp === 'unset';
    p.style.webkitLineClamp = expanded ? '5' : 'unset';
    p.style.overflow = expanded ? 'hidden' : 'visible';
    p.style.display = expanded ? '-webkit-box' : 'block';
    btn.textContent = expanded ? 'Baca selengkapnya ↓' : 'Sembunyikan ↑';
}
</script>

@endsection
