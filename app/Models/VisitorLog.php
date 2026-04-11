<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'url', 'page_name', 'ip', 'session_id',
        'referrer', 'user_agent', 'device',
    ];

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeToday($q)
    {
        return $q->whereDate('created_at', today());
    }

    public function scopeThisMonth($q)
    {
        return $q->whereMonth('created_at', now()->month)
                 ->whereYear('created_at',  now()->year);
    }

    public function scopeThisYear($q)
    {
        return $q->whereYear('created_at', now()->year);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Detect device type from user-agent string.
     */
    public static function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) return 'tablet';
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) return 'mobile';
        return 'desktop';
    }

    /**
     * Clean & shorten a referrer URL to just the domain.
     */
    public static function parseReferrer(?string $ref): ?string
    {
        if (!$ref) return null;
        $host = parse_url($ref, PHP_URL_HOST);
        return $host ?: $ref;
    }
}
