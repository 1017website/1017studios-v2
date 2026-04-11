<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Models\Message;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    private array $pages = [
        'home'      => 'Home',
        'services'  => 'Services',
        'portfolio' => 'Portfolio',
        'about'     => 'About',
        'contact'   => 'Contact',
    ];

    public function index()
    {
        $seoSettings    = SeoSetting::all()->keyBy('page');
        $pages          = $this->pages;
        $unreadMessages = Message::where('is_read', false)->count();
        $pageTitle      = 'SEO Settings';

        return view('admin.seo', compact('seoSettings', 'pages', 'unreadMessages', 'pageTitle'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'page'             => 'required|string|in:' . implode(',', array_keys($this->pages)),
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:320',
            'meta_keywords'    => 'nullable|string|max:500',
            'og_title'         => 'nullable|string|max:200',
            'og_description'   => 'nullable|string|max:320',
            'og_image'         => 'nullable|url|max:500',
            'schema_json'      => 'nullable|string',
        ]);

        // Validate JSON-LD if provided
        if ($request->filled('schema_json')) {
            json_decode($request->schema_json);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['schema_json' => 'Schema JSON is not valid JSON.'])->withInput();
            }
        }

        SeoSetting::updateOrCreate(
            ['page' => $request->page],
            $request->only([
                'meta_title', 'meta_description', 'meta_keywords',
                'og_title', 'og_description', 'og_image', 'schema_json',
            ])
        );

        return redirect()->route('admin.seo')->with('success', 'SEO settings for "' . $this->pages[$request->page] . '" saved.');
    }
}
