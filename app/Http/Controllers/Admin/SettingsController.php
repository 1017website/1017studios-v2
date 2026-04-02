<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings       = Setting::pluck('value', 'key')->toArray();
        $unreadMessages = \App\Models\Message::where('is_read', false)->count();
        $pageTitle      = 'Settings';
        return view('admin.settings', compact('settings', 'unreadMessages', 'pageTitle'));
    }

    public function update(Request $request)
    {
        $keys = [
            'company_name', 'tagline', 'email', 'whatsapp', 'address', 'maps_url',
            'stat_projects', 'stat_clients', 'stat_years', 'stat_satisfaction',
            'instagram', 'linkedin', 'meta_description',
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key, '')]
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Settings saved successfully.');
    }
}
