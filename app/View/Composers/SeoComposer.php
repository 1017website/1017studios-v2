<?php

namespace App\View\Composers;

use App\Models\SeoSetting;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class SeoComposer
{
    /**
     * Map route names → seo_settings.page keys
     */
    private array $routePageMap = [
        'home'      => 'home',
        'services'  => 'services',
        'portfolio' => 'portfolio',
        'about'     => 'about',
        'contact'   => 'contact',
        'reviews'   => 'reviews',
    ];

    public function compose(View $view): void
    {
        // Detect current page key from route name
        $routeName = request()->route()?->getName() ?? '';
        $pageKey   = $this->routePageMap[$routeName] ?? null;

        if (!$pageKey) return;

        // Load from cache (5 min) to avoid DB hit on every page load
        $dbSeo = Cache::remember("seo_settings_{$pageKey}", now()->addMinutes(5), function () use ($pageKey) {
            return SeoSetting::where('page', $pageKey)->first();
        });

        if (!$dbSeo) return;

        // Merge DB values into the $seo array already passed by the controller
        // DB values take priority; controller defaults are kept as fallback
        $existing = $view->getData()['seo'] ?? [];

        $merged = $existing;

        if (!empty($dbSeo->meta_title))       $merged['title']       = $dbSeo->meta_title;
        if (!empty($dbSeo->meta_description)) $merged['description'] = $dbSeo->meta_description;
        if (!empty($dbSeo->meta_keywords))    $merged['keywords']    = $dbSeo->meta_keywords;

        // Open Graph overrides
        if (!empty($dbSeo->og_title))         $merged['og_title']       = $dbSeo->og_title;
        if (!empty($dbSeo->og_description))   $merged['og_description'] = $dbSeo->og_description;
        if (!empty($dbSeo->og_image))         $merged['image']          = $dbSeo->og_image;

        // JSON-LD (stored as raw JSON string from admin)
        if (!empty($dbSeo->schema_json))      $merged['custom_schema']  = $dbSeo->schema_json;

        $view->with('seo', $merged);

        // Also pass schema separately so layout can inject it
        if (!empty($dbSeo->schema_json)) {
            $view->with('customSchemaJson', $dbSeo->schema_json);
        }
    }
}
