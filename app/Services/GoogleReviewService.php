<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleReviewService
{
    private string $apiKey;
    private string $placeId;
    private int    $cacheTtl;       // minutes
    private int    $monthlyLimit;   // max API calls per month (default: 500, jauh di bawah free tier 11.700)

    // Harga per 1 request Place Details = $0.017
    // Free tier = $200/bulan = 11.764 request
    // Kita batasi default 500 req/bulan = sangat aman, efektif gratis
    private float $costPerRequest = 0.017;

    public function __construct()
    {
        $this->apiKey       = config('services.google.places_api_key', '');
        $this->placeId      = config('services.google.place_id', '');
        $this->cacheTtl     = (int) config('services.google.cache_ttl', 60);
        $this->monthlyLimit = (int) config('services.google.monthly_request_limit', 500);
    }

    // ── Public API ───────────────────────────────────────────────────────────

    public function getReviews(): array
    {
        if (!$this->apiKey || !$this->placeId) {
            return $this->errorResponse('Google Places API key atau Place ID belum dikonfigurasi.');
        }

        // Serve from cache first — no API call, no counter increment
        $cacheKey = "google_reviews_{$this->placeId}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Budget check BEFORE hitting the API
        if ($this->isBudgetExceeded()) {
            Log::warning('Google Places API: monthly request limit reached, serving fallback.');
            return $this->budgetExceededResponse();
        }

        return Cache::remember($cacheKey, now()->addMinutes($this->cacheTtl), function () {
            $result = $this->fetchFromApi();
            if (!$result['error']) {
                $this->incrementCounter();
            }
            return $result;
        });
    }

    public function refresh(): array
    {
        $cacheKey = "google_reviews_{$this->placeId}";
        Cache::forget($cacheKey);
        return $this->getReviews();
    }

    /**
     * Get this month's usage stats for admin display.
     */
    public function getUsageStats(): array
    {
        $count    = $this->getMonthlyCount();
        $limit    = $this->monthlyLimit;
        $cost     = round($count * $this->costPerRequest, 4);
        $freeTier = 11764; // $200 / $0.017

        return [
            'count'          => $count,
            'limit'          => $limit,
            'remaining'      => max(0, $limit - $count),
            'percentage'     => $limit > 0 ? min(100, round($count / $limit * 100)) : 0,
            'estimated_cost' => $cost,
            'free_tier_max'  => $freeTier,
            'is_exceeded'    => $this->isBudgetExceeded(),
            'cache_ttl'      => $this->cacheTtl,
            'cache_key'      => "google_reviews_{$this->placeId}",
            'cache_expires'  => Cache::has("google_reviews_{$this->placeId}")
                                    ? 'Cached (active)'
                                    : 'Not cached',
        ];
    }

    /**
     * Admin: manually reset this month's counter (use with caution).
     */
    public function resetCounter(): void
    {
        Cache::forget($this->counterKey());
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function fetchFromApi(): array
    {
        try {
            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $this->placeId,
                'fields'   => 'name,rating,user_ratings_total,reviews,url',
                'language' => 'id',
                'key'      => $this->apiKey,
            ]);

            if (!$response->ok()) {
                Log::warning('Google Places API HTTP error', ['status' => $response->status()]);
                return $this->errorResponse('Gagal terhubung ke Google Places API.');
            }

            $data = $response->json();

            if (($data['status'] ?? '') !== 'OK') {
                $msg = $data['error_message'] ?? $data['status'] ?? 'Unknown error';
                Log::warning('Google Places API error', ['status' => $data['status'], 'msg' => $msg]);
                return $this->errorResponse("Google API: {$msg}");
            }

            $result  = $data['result'];
            $reviews = collect($result['reviews'] ?? [])
                ->sortByDesc('time')
                ->values()
                ->map(fn($r) => [
                    'author'      => $r['author_name'],
                    'profile_url' => $r['author_url'] ?? '#',
                    'photo'       => $r['profile_photo_url'] ?? null,
                    'rating'      => (int) $r['rating'],
                    'text'        => $r['text'] ?? '',
                    'time'        => $r['relative_time_description'] ?? '',
                    'timestamp'   => $r['time'] ?? 0,
                ])
                ->all();

            return [
                'error'           => null,
                'budget_exceeded' => false,
                'reviews'         => $reviews,
                'place'           => [
                    'name'     => $result['name'] ?? '1017Studios',
                    'rating'   => $result['rating'] ?? 0,
                    'total'    => $result['user_ratings_total'] ?? 0,
                    'maps_url' => $result['url'] ?? "https://search.google.com/local/reviews?placeid={$this->placeId}",
                ],
            ];

        } catch (\Throwable $e) {
            Log::error('Google Places API exception', ['error' => $e->getMessage()]);
            return $this->errorResponse('Tidak dapat mengambil ulasan saat ini.');
        }
    }

    private function isBudgetExceeded(): bool
    {
        return $this->getMonthlyCount() >= $this->monthlyLimit;
    }

    private function getMonthlyCount(): int
    {
        return (int) Cache::get($this->counterKey(), 0);
    }

    private function incrementCounter(): void
    {
        $key = $this->counterKey();
        if (Cache::has($key)) {
            Cache::increment($key);
        } else {
            // TTL = sampai akhir bulan ini
            $secondsUntilEndOfMonth = now()->endOfMonth()->diffInSeconds(now());
            Cache::put($key, 1, $secondsUntilEndOfMonth);
        }
    }

    private function counterKey(): string
    {
        return 'google_places_monthly_count_' . now()->format('Y_m');
    }

    private function errorResponse(string $message): array
    {
        return ['error' => $message, 'budget_exceeded' => false, 'reviews' => [], 'place' => null];
    }

    private function budgetExceededResponse(): array
    {
        return [
            'error'           => null,
            'budget_exceeded' => true,
            'reviews'         => [],
            'place'           => null,
        ];
    }
}
