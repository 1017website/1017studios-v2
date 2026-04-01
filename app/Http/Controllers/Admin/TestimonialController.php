<?php
// ============================================================
// TestimonialController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('order')->paginate(20);
        $pageTitle    = 'Testimonials';
        return view('admin.testimonials.index', compact('testimonials', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Add Testimonial';
        return view('admin.testimonials.form', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'role'    => 'nullable|string|max:100',
            'company' => 'nullable|string|max:100',
            'quote'   => 'required|string|max:600',
            'avatar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'order'   => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial)
    {
        $pageTitle = 'Edit Testimonial';
        return view('admin.testimonials.form', compact('testimonial', 'pageTitle'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'role'    => 'nullable|string|max:100',
            'company' => 'nullable|string|max:100',
            'quote'   => 'required|string|max:600',
            'avatar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'order'   => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar) Storage::disk('public')->delete($testimonial->avatar);
            $validated['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->avatar) Storage::disk('public')->delete($testimonial->avatar);
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
