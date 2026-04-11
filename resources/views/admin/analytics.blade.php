@extends('layouts.admin')

@section('admin-content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:.5rem">
    <div>
        <h1 class="admin-page-title" style="margin-bottom:.25rem">Visitor Analytics</h1>
        <p class="admin-page-sub">Track page views, unique visitors, devices, and traffic sources.</p>
    </div>
    {{-- Range selector --}}
    <form method="GET" action="{{ route('admin.analytics') }}" style="display:flex;gap:.5rem;align-items:center">
        <select name="range" onchange="this.form.submit()"
            style="background:var(--bg-3);border:1px solid rgba(255,255,255,.12);color:var(--white);padding:.5rem .85rem;font-size:.82rem;font-family:inherit;cursor:pointer">
            <option value="7"   {{ $range=='7'  ?'selected':'' }}>Last 7 days</option>
            <option value="30"  {{ $range=='30' ?'selected':'' }}>Last 30 days</option>
            <option value="90"  {{ $range=='90' ?'selected':'' }}>Last 90 days</option>
            <option value="365" {{ $range=='365'?'selected':'' }}>Last 1 year</option>
        </select>
    </form>
</div>

{{-- Summary Cards --}}
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

{{-- Daily Chart --}}
<div class="admin-section-card" style="margin-bottom:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <div class="admin-section-card-title" style="margin:0">Daily Traffic (Last {{ $range }} Days)</div>
        <div style="display:flex;gap:1rem;font-size:.75rem;color:rgba(255,255,255,.4)">
            <span style="display:flex;align-items:center;gap:.4rem"><span style="width:12px;height:3px;background:#d4c5a9;display:inline-block"></span> Views</span>
            <span style="display:flex;align-items:center;gap:.4rem"><span style="width:12px;height:3px;background:rgba(212,197,169,.35);display:inline-block"></span> Unique</span>
        </div>
    </div>
    <canvas id="dailyChart" height="90"></canvas>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem">

    {{-- Top Pages --}}
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
                <div style="width:{{ $pct }}%;background:var(--accent);height:100%;border-radius:2px;transition:width .6s"></div>
            </div>
        </div>
        @empty
        <p style="color:var(--mid-gray);font-size:.85rem">No data yet.</p>
        @endforelse
    </div>

    {{-- Top Referrers --}}
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
                <div style="width:{{ $pct2 }}%;background:rgba(212,197,169,.5);height:100%;border-radius:2px;transition:width .6s"></div>
            </div>
        </div>
        @empty
        <p style="color:var(--mid-gray);font-size:.85rem">No external referrers yet.</p>
        @endforelse
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:2rem">

    {{-- Monthly Chart --}}
    <div class="admin-section-card">
        <div class="admin-section-card-title">Monthly Overview ({{ now()->year }})</div>
        <canvas id="monthlyChart" height="110"></canvas>
    </div>

    {{-- Device Breakdown --}}
    <div class="admin-section-card">
        <div class="admin-section-card-title">Device Breakdown</div>
        <canvas id="deviceChart" height="180"></canvas>
        <div style="display:flex;flex-direction:column;gap:.6rem;margin-top:1.2rem;font-size:.82rem">
            @php
                $dTotal = max($deviceDesktop + $deviceMobile + $deviceTablet, 1);
            @endphp
            <div style="display:flex;justify-content:space-between">
                <span style="display:flex;align-items:center;gap:.5rem">
                    <span style="width:10px;height:10px;background:#d4c5a9;border-radius:50%;display:inline-block"></span> Desktop
                </span>
                <span style="color:var(--mid-gray)">{{ $deviceDesktop }} ({{ round($deviceDesktop/$dTotal*100) }}%)</span>
            </div>
            <div style="display:flex;justify-content:space-between">
                <span style="display:flex;align-items:center;gap:.5rem">
                    <span style="width:10px;height:10px;background:rgba(212,197,169,.5);border-radius:50%;display:inline-block"></span> Mobile
                </span>
                <span style="color:var(--mid-gray)">{{ $deviceMobile }} ({{ round($deviceMobile/$dTotal*100) }}%)</span>
            </div>
            <div style="display:flex;justify-content:space-between">
                <span style="display:flex;align-items:center;gap:.5rem">
                    <span style="width:10px;height:10px;background:rgba(212,197,169,.2);border-radius:50%;display:inline-block"></span> Tablet
                </span>
                <span style="color:var(--mid-gray)">{{ $deviceTablet }} ({{ round($deviceTablet/$dTotal*100) }}%)</span>
            </div>
        </div>
    </div>
</div>

{{-- Purge old data --}}
<div style="text-align:right">
    <form method="POST" action="{{ route('admin.analytics.purge') }}" onsubmit="return confirm('Purge visitor logs older than 1 year?')">
        @csrf
        <button type="submit" style="background:transparent;border:1px solid rgba(255,80,80,.25);color:rgba(255,120,120,.6);padding:.5rem 1.2rem;font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;font-family:inherit;transition:all .2s"
            onmouseover="this.style.borderColor='rgba(255,80,80,.5)';this.style.color='rgba(255,120,120,.9)'"
            onmouseout="this.style.borderColor='rgba(255,80,80,.25)';this.style.color='rgba(255,120,120,.6)'">
            Purge Old Logs (&gt;1 year)
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.color = 'rgba(255,255,255,.35)';
Chart.defaults.borderColor = 'rgba(255,255,255,.06)';
Chart.defaults.font.family = 'DM Sans, sans-serif';

const accent = '#d4c5a9';
const accentFaded = 'rgba(212,197,169,0.2)';

// ── Daily chart ─────────────────────────────────────────────────────────────
new Chart(document.getElementById('dailyChart'), {
    type: 'line',
    data: {
        labels: @json($chartDates),
        datasets: [
            {
                label: 'Views',
                data: @json($chartViews),
                borderColor: accent,
                backgroundColor: 'rgba(212,197,169,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: @if(count($chartDates) > 30) 0 @else 3 @endif,
                borderWidth: 1.5,
            },
            {
                label: 'Unique Visitors',
                data: @json($chartUniqueVisitors),
                borderColor: 'rgba(212,197,169,0.35)',
                backgroundColor: 'transparent',
                fill: false,
                tension: 0.4,
                pointRadius: 0,
                borderWidth: 1.5,
                borderDash: [4, 3],
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { maxTicksLimit: 12 } },
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// ── Monthly chart ────────────────────────────────────────────────────────────
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [
            {
                label: 'Views',
                data: @json($monthViews2),
                backgroundColor: 'rgba(212,197,169,0.25)',
                borderColor: accent,
                borderWidth: 1,
                borderRadius: 3,
            },
            {
                label: 'Unique',
                data: @json($monthUnique),
                backgroundColor: 'rgba(212,197,169,0.08)',
                borderColor: 'rgba(212,197,169,0.3)',
                borderWidth: 1,
                borderRadius: 3,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: {},
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// ── Device doughnut ──────────────────────────────────────────────────────────
new Chart(document.getElementById('deviceChart'), {
    type: 'doughnut',
    data: {
        labels: ['Desktop', 'Mobile', 'Tablet'],
        datasets: [{
            data: [{{ $deviceDesktop }}, {{ $deviceMobile }}, {{ $deviceTablet }}],
            backgroundColor: [accent, 'rgba(212,197,169,0.45)', 'rgba(212,197,169,0.15)'],
            borderColor: 'transparent',
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
</script>

@endsection
