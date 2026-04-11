<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\SeoComposer;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Attach SeoComposer to all public home views
        // This merges DB seo_settings into the $seo array on every public page
        View::composer([
            'home.index',
            'home.services',
            'home.portfolio',
            'home.about',
            'home.contact',
            'home.reviews',
        ], SeoComposer::class);
    }
}
