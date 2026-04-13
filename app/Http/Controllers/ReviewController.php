<?php

namespace App\Http\Controllers;

use App\Services\GoogleReviewService;
use App\Models\Setting;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private GoogleReviewService $reviewService) {}

    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $data     = $this->reviewService->getReviews();

        $seo = [
            'title'       => 'Review Klien | 1017Studios — Testimoni Google',
            'description' => 'Baca ulasan nyata dari klien 1017Studios di Google. Studio branding dan digital agency terpercaya di Surabaya.',
            'keywords'    => 'review 1017studios, testimoni klien surabaya, google review agency surabaya',
            'canonical'   => route('reviews'),
            'type'        => 'website',
        ];

        return view('home.reviews', array_merge(compact('settings', 'seo'), $data));
    }

    /**
     * Admin: force-refresh the Google review cache.
     */
    public function refresh(Request $request)
    {
        $this->reviewService->refresh();
        return back()->with('success', 'Google Reviews refreshed successfully.');
    }
}
