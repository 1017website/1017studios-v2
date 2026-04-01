@extends('layouts.app')

@section('content')

<div style="padding-top:var(--nav-h)"></div>

<!-- Hero -->
<section class="section" style="padding-bottom:4rem">
    <div class="container">
        <span class="label reveal">About Us</span>
        <h1 class="reveal reveal-delay-1" style="font-size:clamp(3.5rem,9vw,9rem);margin:.75rem 0">We Are<br><em style="font-family:var(--font-serif);font-style:italic">1017Studios</em></h1>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:clamp(2rem,6vw,6rem);margin-top:4rem;align-items:start">
            <p class="reveal reveal-delay-2" style="font-size:1.1rem;line-height:1.8;color:rgba(255,255,255,.75)">
                Kami adalah studio kreatif dan teknologi yang berbasis di Surabaya — mendedikasikan diri untuk membangun brand identity yang kuat dan produk digital yang luar biasa.
            </p>
            <p class="reveal reveal-delay-3" style="line-height:1.8">
                Dari logo pertama hingga aplikasi pertama, kami percaya bahwa desain dan teknologi yang baik bukan sekadar estetika — melainkan fondasi dari bisnis yang sukses. Setiap proyek adalah kolaborasi, setiap output adalah karya.
            </p>
        </div>
    </div>
</section>

<!-- Values -->
<section class="section" style="padding-top:0">
    <div class="container">
        <div style="border-top:1px solid rgba(255,255,255,.08);padding-top:4rem">
            <span class="label reveal">Our Values</span>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:rgba(255,255,255,.06);margin-top:2rem">
                @foreach ([
                    ['num'=>'01','title'=>'Intentional Design','desc'=>'Every pixel, every word, every interaction is deliberate. We design with purpose and build with precision.'],
                    ['num'=>'02','title'=>'Bold Creativity','desc'=>'We push beyond safe choices. The best brands are born from courage — the willingness to stand out.'],
                    ['num'=>'03','title'=>'Lasting Impact','desc'=>'Our work doesn\'t just look good on launch day. We build things designed to grow with your business.'],
                ] as $i => $val)
                <div class="reveal" style="transition-delay:{{ $i*0.1 }}s;background:var(--black);padding:2.5rem">
                    <div style="font-family:var(--font-display);font-size:.9rem;color:rgba(255,255,255,.15);margin-bottom:1.5rem;letter-spacing:.1em">{{ $val['num'] }}</div>
                    <h3 style="font-family:var(--font-display);font-size:1.4rem;font-style:normal;letter-spacing:.04em;margin-bottom:1rem">{{ $val['title'] }}</h3>
                    <p style="font-size:.9rem;line-height:1.7">{{ $val['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="section stats-section">
    <div class="container">
        <div class="stats-inner">
            <div class="stats-text">
                <span class="label reveal" style="color:rgba(0,0,0,.4)">Track Record</span>
                <h2 class="reveal reveal-delay-1" style="color:#000;margin-top:.5rem">Numbers<br><em style="font-family:var(--font-serif);font-style:italic">Don't Lie</em></h2>
            </div>
            <div class="stats-grid">
                @foreach ([
                    ['num' => $settings['stat_projects'] ?? 150, 'suffix'=>'+','label'=>'Projects Delivered'],
                    ['num' => $settings['stat_clients'] ?? 80,   'suffix'=>'+','label'=>'Happy Clients'],
                    ['num' => $settings['stat_years'] ?? 5,      'suffix'=>'', 'label'=>'Years Experience'],
                    ['num' => $settings['stat_satisfaction'] ?? 98,'suffix'=>'%','label'=>'Satisfaction Rate'],
                ] as $i => $s)
                <div class="stat-item reveal" style="transition-delay:{{ $i*0.1 }}s">
                    <div class="stat-num" data-count="{{ $s['num'] }}" data-suffix="{{ $s['suffix'] }}">0{{ $s['suffix'] }}</div>
                    <div class="stat-label">{{ $s['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
@if ($testimonials->isNotEmpty())
<section class="section">
    <div class="container">
        <span class="label reveal">Client Words</span>
        <h2 class="reveal reveal-delay-1" style="margin:.75rem 0 3rem">What They<br><em style="font-family:var(--font-serif);font-style:italic">Say About Us</em></h2>
        <div class="testimonials-grid">
            @foreach ($testimonials as $t)
            <div class="testimonial-card reveal" style="transition-delay:{{ $loop->index*0.1 }}s">
                <p class="testimonial-quote">"{{ $t->quote }}"</p>
                <div class="testimonial-author">
                    @if($t->avatar)
                        <img src="{{ asset('storage/'.$t->avatar) }}" alt="{{ $t->name }}" class="testimonial-avatar">
                    @else
                        <div class="testimonial-avatar" style="display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.1rem">{{ substr($t->name,0,1) }}</div>
                    @endif
                    <div>
                        <div class="testimonial-name">{{ $t->name }}</div>
                        <div class="testimonial-role">{{ $t->role }}@if($t->company), {{ $t->company }}@endif</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA -->
<section style="border-top:1px solid rgba(255,255,255,.08);padding:5rem 0">
    <div class="container" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:2rem">
        <h2 class="reveal" style="font-size:clamp(2rem,5vw,5rem)">Work With<br><em style="font-family:var(--font-serif);font-style:italic">Us?</em></h2>
        <div class="reveal reveal-delay-1" style="display:flex;gap:1rem;flex-wrap:wrap">
            <a href="{{ route('contact') }}" class="btn"><span>Get In Touch</span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
            <a href="https://wa.me/{{ $settings['whatsapp'] ?? '6281234567890' }}" target="_blank" class="btn" style="border-color:rgba(255,255,255,.2)"><span>WhatsApp</span></a>
        </div>
    </div>
</section>

@endsection
