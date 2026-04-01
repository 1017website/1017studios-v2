<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('order')->paginate(15);
        $pageTitle = 'Portfolio';
        return view('admin.portfolio.index', compact('portfolios', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Add Portfolio';
        return view('admin.portfolio.form', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'category'    => 'nullable|string|max:100',
            'client'      => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'project_url' => 'nullable|url',
            'year'        => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'tags'        => 'nullable|string|max:255',
            'order'       => 'nullable|integer|min:0',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('portfolio', 'public');
        }

        $validated['is_active']   = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        Portfolio::create($validated);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item created successfully.');
    }

    public function edit(Portfolio $portfolio)
    {
        $pageTitle = 'Edit Portfolio';
        return view('admin.portfolio.form', compact('portfolio', 'pageTitle'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'category'    => 'nullable|string|max:100',
            'client'      => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'project_url' => 'nullable|url',
            'year'        => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'tags'        => 'nullable|string|max:255',
            'order'       => 'nullable|integer|min:0',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($portfolio->thumbnail) Storage::disk('public')->delete($portfolio->thumbnail);
            $validated['thumbnail'] = $request->file('thumbnail')->store('portfolio', 'public');
        }

        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $portfolio->update($validated);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->thumbnail) Storage::disk('public')->delete($portfolio->thumbnail);
        $portfolio->delete();
        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item deleted.');
    }
}
