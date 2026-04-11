<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'page', 'meta_title', 'meta_description', 'meta_keywords',
        'og_title', 'og_description', 'og_image', 'schema_json',
    ];

    /**
     * Get all SEO settings keyed by page name.
     */
    public static function allKeyed(): array
    {
        return static::all()->keyBy('page')->toArray();
    }

    /**
     * Get settings for a specific page, or return empty array.
     */
    public static function forPage(string $page): array
    {
        return static::where('page', $page)->first()?->toArray() ?? [];
    }
}
