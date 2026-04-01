<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services  = Service::orderBy('order')->paginate(20);
        $pageTitle = 'Services';
        return view('admin.services.index', compact('services', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Add Service';
        return view('admin.services.form', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:150',
            'short_description' => 'required|string|max:400',
            'full_description'  => 'nullable|string',
            'icon_svg'          => 'nullable|string',
            'tags'              => 'nullable|string|max:255',
            'order'             => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $pageTitle = 'Edit Service';
        return view('admin.services.form', compact('service', 'pageTitle'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:150',
            'short_description' => 'required|string|max:400',
            'full_description'  => 'nullable|string',
            'icon_svg'          => 'nullable|string',
            'tags'              => 'nullable|string|max:255',
            'order'             => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted.');
    }
}
