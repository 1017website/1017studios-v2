<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->input('range', '30'); // days: 7 | 30 | 90 | 365

        $from = now()->subDays((int)$range)->startOfDay();

        // ── Summary cards ────────────────────────────────────────────────────
        $totalViews   = VisitorLog::where('created_at', '>=', $from)->count();
        $todayViews   = VisitorLog::today()->count();
        $monthViews   = VisitorLog::thisMonth()->count();

        // Unique visitors = distinct session_ids
        $uniqueTotal  = VisitorLog::where('created_at', '>=', $from)
                            ->distinct('session_id')->count('session_id');
        $uniqueToday  = VisitorLog::today()
                            ->distinct('session_id')->count('session_id');

        // ── Daily views chart (last N days) ──────────────────────────────────
        $dailyViews = VisitorLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as views'),
                DB::raw('COUNT(DISTINCT session_id) as unique_visitors')
            )
            ->where('created_at', '>=', $from)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill in missing dates with 0
        $chartDates          = [];
        $chartViews          = [];
        $chartUniqueVisitors = [];
        for ($i = (int)$range - 1; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $chartDates[]          = now()->subDays($i)->format('d M');
            $chartViews[]          = $dailyViews[$d]->views          ?? 0;
            $chartUniqueVisitors[] = $dailyViews[$d]->unique_visitors ?? 0;
        }

        // ── Top pages ────────────────────────────────────────────────────────
        $topPages = VisitorLog::select('page_name', DB::raw('COUNT(*) as views'))
            ->where('created_at', '>=', $from)
            ->groupBy('page_name')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // ── Top referrers ────────────────────────────────────────────────────
        $topReferrers = VisitorLog::select('referrer', DB::raw('COUNT(*) as visits'))
            ->where('created_at', '>=', $from)
            ->whereNotNull('referrer')
            ->groupBy('referrer')
            ->orderByDesc('visits')
            ->limit(10)
            ->get();

        // ── Device breakdown ─────────────────────────────────────────────────
        $devices = VisitorLog::select('device', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $from)
            ->groupBy('device')
            ->get()
            ->keyBy('device');

        $deviceDesktop = $devices['desktop']->count ?? 0;
        $deviceMobile  = $devices['mobile']->count  ?? 0;
        $deviceTablet  = $devices['tablet']->count  ?? 0;
        $deviceTotal   = max($deviceDesktop + $deviceMobile + $deviceTablet, 1);

        // ── Monthly summary (this year) ───────────────────────────────────────
        $monthlyViews = VisitorLog::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as views'),
                DB::raw('COUNT(DISTINCT session_id) as unique_visitors')
            )
            ->thisYear()
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months      = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $monthViews2 = [];
        $monthUnique = [];
        foreach (range(1, 12) as $m) {
            $monthViews2[] = $monthlyViews[$m]->views          ?? 0;
            $monthUnique[] = $monthlyViews[$m]->unique_visitors ?? 0;
        }

        $unreadMessages = Message::where('is_read', false)->count();
        $pageTitle      = 'Visitor Analytics';

        return view('admin.analytics', compact(
            'range',
            'totalViews', 'todayViews', 'monthViews',
            'uniqueTotal', 'uniqueToday',
            'chartDates', 'chartViews', 'chartUniqueVisitors',
            'topPages', 'topReferrers',
            'deviceDesktop', 'deviceMobile', 'deviceTablet', 'deviceTotal',
            'months', 'monthViews2', 'monthUnique',
            'unreadMessages', 'pageTitle'
        ));
    }

    /**
     * Clear old visitor logs (keep last 365 days).
     */
    public function purge(Request $request)
    {
        $deleted = VisitorLog::where('created_at', '<', now()->subDays(365))->delete();
        return back()->with('success', "Purged {$deleted} old visitor records.");
    }
}
