@extends('layouts.admin')

@section('admin-content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:.5rem">
    <div>
        <h1 class="admin-page-title" style="margin-bottom:.25rem">Visitor Analytics</h1>
        <p class="admin-page-sub">Track page views, unique visitors, devices, and traffic sources.</p>
    </div>
    <form method="GET" action="{{ route('admin.analytics') }}" style="display:flex;gap:.5rem;align-items:center">
        <select name="range" onchange="this.form.submit()"
            style="background:var(--bg-3);border:1px solid var(--cms-border);color:var(--white);padding:.5rem .85rem;font-size:.82rem;font-family:inherit;cursor:pointer">
            <option value="7"   {{ $range=='7'  ?'selected':'' }}>Last 7 days</option>
            <option value="30"  {{ $range=='30' ?'selected':'' }}>Last 30 days</option>
            <option value="90"  {{ $range=='90' ?'selected':'' }}>Last 90 days</option>
            <option value="365" {{ $range=='365'?'selected':'' }}>Last 1 year</option>
        </select>
    </form>
</div>

{{-- Visitor Summary Cards --}}
<div class="admin-stats" style="grid-template-columns:repeat(5,1fr);margin-bottom:2rem">
    <div class="admin-stat-card">
        <div class="admin-stat-value">{{ number_format($totalViews) }}</div>
        <div class="admin-stat-label">Total Views ({{ $range }}d)</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-value">{{ number_format($uniqueTotal) }}</div>
        <div class="admin-stat-label">Unique Visitors ({{ $range }}d)</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-value">{{ number_format($todayViews) }}</div>
        <div class="admin-stat-label">Views Today</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-value">{{ number_format($uniqueToday) }}</div>
        <div class="admin-stat-label">Unique Today</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-value">{{ number_format($monthViews) }}</div>
        <div class="admin-stat-label">Views This Month</div>
    </div>
</div>

{{-- ── Google API Budget Monitor ─────────────────────────────────────────── --}}
<div class="admin-section-card" style="margin-bottom:2rem;border-color:{{ $googleUsage['is_exceeded'] ? 'var(--cms-danger-border)' : 'var(--cms-border)' }}">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem">
        <div>
            <div class="admin-section-card-title" style="margin:0">
                Google Places API — Budget Monitor
                @if($googleUsage['is_exceeded'])
                    <span style="margin-left:.75rem;font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;color:var(--cms-danger);border:1px solid var(--cms-danger-border);padding:2px 8px">LIMIT REACHED</span>
                @else
                    <span style="margin-left:.75rem;font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;color:var(--cms-success);border:1px solid var(--cms-success-border);padding:2px 8px">ACTIVE</span>
                @endif
            </div>
            <div style="font-size:.78rem;color:var(--cms-muted);margin-top:.3rem">
                Cache: {{ $googleUsage['cache_ttl'] }} menit &nbsp;·&nbsp; Status: {{ $googleUsage['cache_expires'] }}
            </div>
        </div>
        <div style="display:flex;gap:.75rem">
            {{-- Refresh cache --}}
            <form method="POST" action="{{ route('admin.reviews.refresh') }}">
                @csrf
                <button type="submit" class="action-btn" style="padding:.5rem 1.1rem;font-size:.75rem">
                    ↻ Refresh Cache
                </button>
            </form>
            {{-- Reset counter --}}
            <form method="POST" action="{{ route('admin.analytics.reset-google') }}"
                  onsubmit="return confirm('Reset counter API Google bulan ini? Hanya lakukan jika yakin limit belum tercapai.')">
                @csrf
                <button type="submit" class="action-btn danger" style="padding:.5rem 1.1rem;font-size:.75rem">
                    Reset Counter
                </button>
            </form>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
        <div style="background:rgba(255,255,255,.03);border:1px solid var(--cms-border);padding:1rem">
            <div style="font-size:1.6rem;font-family:var(--font-display);letter-spacing:-.02em">{{ $googleUsage['count'] }}</div>
            <div style="font-size:.73rem;color:var(--cms-muted);margin-top:.2rem;text-transform:uppercase;letter-spacing:.07em">Request Bulan Ini</div>
        </div>
        <div style="background:rgba(255,255,255,.03);border:1px solid var(--cms-border);padding:1rem">
            <div style="font-size:1.6rem;font-family:var(--font-display);letter-spacing:-.02em">{{ $googleUsage['limit'] }}</div>
            <div style="font-size:.73rem;color:var(--cms-muted);margin-top:.2rem;text-transform:uppercase;letter-spacing:.07em">Batas Bulanan</div>
        </div>
        <div style="background:rgba(255,255,255,.03);border:1px solid var(--cms-border);padding:1rem">
            <div style="font-size:1.6rem;font-family:var(--font-display);letter-spacing:-.02em">${{ $googleUsage['estimated_cost'] }}</div>
            <div style="font-size:.73rem;color:var(--cms-muted);margin-top:.2rem;text-transform:uppercase;letter-spacing:.07em">Estimasi Biaya</div>
        </div>
        <div style="background:rgba(255,255,255,.03);border:1px solid var(--cms-border);padding:1rem">
            <div style="font-size:1.6rem;font-family:var(--font-display);letter-spacing:-.02em">{{ $googleUsage['remaining'] }}</div>
            <div style="font-size:.73rem;color:var(--cms-muted);margin-top:.2rem;text-transform:uppercase;letter-spacing:.07em">Sisa Request</div>
        </div>
    </div>

    {{-- Progress bar --}}
    <div>
        <div style="display:flex;justify-content:space-between;font-size:.75rem;color:var(--cms-muted);margin-bottom:.5rem">
            <span>Penggunaan bulan ini</span>
            <span>{{ $googleUsage['percentage'] }}% dari limit</span>
        </div>
        <div style="background:rgba(255,255,255,.06);height:6px;border-radius:3px">
            <div style="
                width:{{ $googleUsage['percentage'] }}%;
                height:100%;
                border-radius:3px;
                background:{{ $googleUsage['percentage'] >= 100 ? 'rgba(255,80,80,.8)' : ($googleUsage['percentage'] >= 80 ? 'rgba(255,180,50,.8)' : 'var(--accent)') }};
                transition:width .6s
            "></div>
        </div>
        <div style="font-size:.72rem;color:var(--cms-muted);margin-top:.6rem">
            Free tier Google: ~{{ number_format($googleUsage['free_tier_max']) }} request/bulan ($200 credit).
            Limit Anda: {{ $googleUsage['limit'] }} request — <strong style="color:var(--cms-muted)">jauh di bawah biaya</strong>.
            Ubah via <code style="font-size:.7rem;background:rgba(255,255,255,.06);padding:1px 5px">GOOGLE_REVIEWS_MONTHLY_LIMIT</code> di .env
        </div>
    </div>
</div>
{{-- ────────────────────────────────────────────────────────────────────── --}}

{{-- Daily Chart --}}
<div class="admin-section-card" style="margin-bottom:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <div class="admin-section-card-title" style="margin:0">Daily Traffic (Last {{ $range }} Days)</div>
        <div style="display:flex;gap:1rem;font-size:.75rem;color:var(--cms-muted)">
            <span style="display:flex;align-items:center;gap:.4rem"><span style="width:12px;height:3px;background:var(--cms-accent);display:inline-block"></span> Views</span>
            <span style="display:flex;align-items:center;gap:.4rem"><span style="width:12px;height:3px;background:var(--cms-chart-secondary);display:inline-block"></span> Unique</span>
        </div>
    </div>
    <canvas id="dailyChart" height="90"></canvas>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem">
    <div class="admin-section-card">
        <div class="admin-section-card-title">Top Pages</div>
        @forelse($topPages as $pg)
        @php $pct = $totalViews > 0 ? round($pg->views / $totalViews * 100) : 0; @endphp
        <div style="margin-bottom:1rem">
            <div style="display:flex;justify-content:space-between;margin-bottom:.35rem;font-size:.85rem">
                <span>{{ $pg->page_name ?? 'Unknown' }}</span>
                <span style="color:var(--mid-gray)">{{ number_format($pg->views) }} <span style="font-size:.72rem">({{ $pct }}%)</span></span>
            </div>
            <div style="background:rgba(255,255,255,.06);height:4px;border-radius:2px">
                <div style="width:{{ $pct }}%;background:var(--accent);height:100%;border-radius:2px"></div>
            </div>
        </div>
        @empty
        <p style="color:var(--mid-gray);font-size:.85rem">No data yet.</p>
        @endforelse
    </div>

    <div class="admin-section-card">
        <div class="admin-section-card-title">Traffic Sources</div>
        @forelse($topReferrers as $ref)
        @php $pct2 = $totalViews > 0 ? round($ref->visits / $totalViews * 100) : 0; @endphp
        <div style="margin-bottom:1rem">
            <div style="display:flex;justify-content:space-between;margin-bottom:.35rem;font-size:.85rem">
                <span style="word-break:break-all">{{ $ref->referrer }}</span>
                <span style="color:var(--mid-gray);white-space:nowrap;margin-left:.5rem">{{ number_format($ref->visits) }} <span style="font-size:.72rem">({{ $pct2 }}%)</span></span>
            </div>
            <div style="background:rgba(255,255,255,.06);height:4px;border-radius:2px">
                <div style="width:{{ $pct2 }}%;background:var(--cms-chart-secondary);height:100%;border-radius:2px"></div>
            </div>
        </div>
        @empty
        <p style="color:var(--mid-gray);font-size:.85rem">No external referrers yet.</p>
        @endforelse
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:2rem">
    <div class="admin-section-card">
        <div class="admin-section-card-title">Monthly Overview ({{ now()->year }})</div>
        <canvas id="monthlyChart" height="110"></canvas>
    </div>
    <div class="admin-section-card">
        <div class="admin-section-card-title">Device Breakdown</div>
        <canvas id="deviceChart" height="180"></canvas>
        @php $dTotal = max($deviceDesktop + $deviceMobile + $deviceTablet, 1); @endphp
        <div style="display:flex;flex-direction:column;gap:.6rem;margin-top:1.2rem;font-size:.82rem">
            <div style="display:flex;justify-content:space-between">
                <span><span style="width:10px;height:10px;background:var(--cms-accent);border-radius:50%;display:inline-block;margin-right:.4rem"></span>Desktop</span>
                <span style="color:var(--mid-gray)">{{ $deviceDesktop }} ({{ round($deviceDesktop/$dTotal*100) }}%)</span>
            </div>
            <div style="display:flex;justify-content:space-between">
                <span><span style="width:10px;height:10px;background:var(--cms-chart-secondary);border-radius:50%;display:inline-block;margin-right:.4rem"></span>Mobile</span>
                <span style="color:var(--mid-gray)">{{ $deviceMobile }} ({{ round($deviceMobile/$dTotal*100) }}%)</span>
            </div>
            <div style="display:flex;justify-content:space-between">
                <span><span style="width:10px;height:10px;background:var(--cms-chart-tertiary);border-radius:50%;display:inline-block;margin-right:.4rem"></span>Tablet</span>
                <span style="color:var(--mid-gray)">{{ $deviceTablet }} ({{ round($deviceTablet/$dTotal*100) }}%)</span>
            </div>
        </div>
    </div>
</div>

<div style="text-align:right">
    <form method="POST" action="{{ route('admin.analytics.purge') }}" onsubmit="return confirm('Purge visitor logs older than 1 year?')">
        @csrf
        <button type="submit" style="background:transparent;border:1px solid var(--cms-danger-border);color:var(--cms-danger);padding:.5rem 1.2rem;font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;font-family:inherit">
            Purge Old Logs (&gt;1 year)
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const cmsTheme = getComputedStyle(document.documentElement);
const cmsColor = name => cmsTheme.getPropertyValue(name).trim();
Chart.defaults.color = cmsColor('--cms-muted');
Chart.defaults.borderColor = cmsColor('--cms-border');
Chart.defaults.font.family = 'DM Sans, sans-serif';
const accent = cmsColor('--cms-accent');
const secondary = cmsColor('--cms-chart-secondary');
const tertiary = cmsColor('--cms-chart-tertiary');

new Chart(document.getElementById('dailyChart'), {
    type: 'line',
    data: {
        labels: @json($chartDates),
        datasets: [
            { label:'Views', data:@json($chartViews), borderColor:accent, backgroundColor:'rgba(212,197,169,0.08)', fill:true, tension:0.4, pointRadius:{{ count($chartDates) > 30 ? 0 : 3 }}, borderWidth:1.5 },
            { label:'Unique', data:@json($chartUniqueVisitors), borderColor:secondary, fill:false, tension:0.4, pointRadius:0, borderWidth:2, borderDash:[4,3] }
        ]
    },
    options: { responsive:true, interaction:{mode:'index',intersect:false}, plugins:{legend:{display:false}}, scales:{ x:{ticks:{maxTicksLimit:12}}, y:{beginAtZero:true,ticks:{precision:0}} } }
});

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [
            { label:'Views', data:@json($monthViews2), backgroundColor:accent, borderColor:accent, borderWidth:1, borderRadius:3 },
            { label:'Unique', data:@json($monthUnique), backgroundColor:secondary, borderColor:secondary, borderWidth:1, borderRadius:3 }
        ]
    },
    options: { responsive:true, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,ticks:{precision:0}} } }
});

new Chart(document.getElementById('deviceChart'), {
    type: 'doughnut',
    data: {
        labels: ['Desktop','Mobile','Tablet'],
        datasets: [{ data:[{{ $deviceDesktop }},{{ $deviceMobile }},{{ $deviceTablet }}], backgroundColor:[accent,secondary,tertiary], borderColor:cmsColor('--bg-card'), borderWidth:2 }]
    },
    options: { responsive:true, cutout:'65%', plugins:{legend:{display:false}} }
});
</script>

@endsection
