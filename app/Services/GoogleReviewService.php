<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleReviewService
{
    private string $apiKey;
    private string $placeId;
    private int $cacheTtl;
    private int $monthlyLimit;
    private float $costPerRequest = 0.017;

    public function __construct()
    {
        $this->apiKey = config('services.google.places_api_key', '');
        $this->placeId = config('services.google.place_id', '');
        $this->cacheTtl = (int) config('services.google.cache_ttl', 60);
        $this->monthlyLimit = (int) config('services.google.monthly_request_limit', 500);
    }

    // ── Public API ───────────────────────────────────────────────────────────

    public function getReviews(): array
    {
        if (!$this->apiKey || !$this->placeId) {
            return $this->errorResponse('Google Places API key atau Place ID belum dikonfigurasi.');
        }

        $cacheKey = "google_reviews_{$this->placeId}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if ($this->isBudgetExceeded()) {
            Log::warning('Google Places API: monthly request limit reached.');
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
        Cache::forget("google_reviews_{$this->placeId}");
        return $this->getReviews();
    }

    public function getUsageStats(): array
    {
        $count = $this->getMonthlyCount();
        $limit = $this->monthlyLimit;

        return [
            'count' => $count,
            'limit' => $limit,
            'remaining' => max(0, $limit - $count),
            'percentage' => $limit > 0 ? min(100, round($count / $limit * 100)) : 0,
            'estimated_cost' => round($count * $this->costPerRequest, 4),
            'free_tier_max' => 11764,
            'is_exceeded' => $this->isBudgetExceeded(),
            'cache_ttl' => $this->cacheTtl,
            'cache_key' => "google_reviews_{$this->placeId}",
            'cache_expires' => Cache::has("google_reviews_{$this->placeId}") ? 'Cached (active)' : 'Not cached',
        ];
    }

    public function resetCounter(): void
    {
        Cache::forget($this->counterKey());
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function fetchFromApi(): array
    {
        try {
            // ── Places API (New) endpoint ────────────────────────────────────
            // Docs: https://developers.google.com/maps/documentation/places/web-service/place-details
            $url = "https://places.googleapis.com/v1/places/{$this->placeId}";

            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Goog-Api-Key' => $this->apiKey,
                    'X-Goog-FieldMask' => 'displayName,rating,userRatingCount,reviews,googleMapsUri',
                    'Accept-Language' => 'id',
                ])
                ->get($url, [
                    'languageCode' => 'id',
                ]);

            if (!$response->ok()) {
                $errBody = $response->json();
                $errMsg = $errBody['error']['message'] ?? 'HTTP ' . $response->status();
                Log::warning('Google Places API (New) error', ['status' => $response->status(), 'msg' => $errMsg]);
                return $this->errorResponse("Google API: {$errMsg}");
            }

            $data = $response->json();
            $reviews = collect($data['reviews'] ?? [])
                ->map(fn($r) => [
                    'author' => $r['authorAttribution']['displayName'] ?? 'Anonymous',
                    'profile_url' => $r['authorAttribution']['uri'] ?? '#',
                    'photo' => $r['authorAttribution']['photoUri'] ?? null,
                    'rating' => (int) ($r['rating'] ?? 0),
                    'text' => $r['text']['text'] ?? '',
                    'time' => $r['relativePublishTimeDescription'] ?? '',
                    'timestamp' => isset($r['publishTime']) ? strtotime($r['publishTime']) : 0,
                ])
                ->sortByDesc('timestamp')
                ->values()
                ->all();

            return [
                'error' => null,
                'budget_exceeded' => false,
                'reviews' => $reviews,
                'place' => [
                    'name' => $data['displayName']['text'] ?? '1017Studios',
                    'rating' => $data['rating'] ?? 0,
                    'total' => $data['userRatingCount'] ?? 0,
                    'maps_url' => $data['googleMapsUri'] ?? "https://search.google.com/local/reviews?placeid={$this->placeId}",
                ],
            ];

        } catch (\Throwable $e) {
            Log::error('Google Places API exception', ['error' => $e->getMessage()]);
            return $this->errorResponse('Tidak dapat mengambil ulasan saat ini: ' . $e->getMessage());
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
            Cache::put($key, 1, now()->endOfMonth()->diffInSeconds(now()));
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
        return ['error' => null, 'budget_exceeded' => true, 'reviews' => [], 'place' => null];
    }
}