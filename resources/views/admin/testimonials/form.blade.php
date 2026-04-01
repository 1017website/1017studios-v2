@extends('layouts.admin')

@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 class="admin-page-title">{{ isset($testimonial) ? 'Edit Testimonial' : 'New Testimonial' }}</h1>
        <p class="admin-page-sub">{{ isset($testimonial) ? 'Update client testimonial.' : 'Add a new client review.' }}</p>
    </div>
    <a href="{{ route('admin.testimonials.index') }}" class="action-btn">← Back</a>
</div>

<form method="POST" action="{{ isset($testimonial) ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" enctype="multipart/form-data">
    @csrf
    @if (isset($testimonial)) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;max-width:900px">
        <div class="admin-section-card">
            <div class="admin-section-card-title">Testimonial Details</div>
            <div class="admin-form">
                <div class="admin-form-row">
                    <div class="form-group">
                        <label class="form-label">Client Name *</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $testimonial->name ?? '') }}" required>
                        @error('name')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role / Position</label>
                        <input type="text" name="role" class="form-input" value="{{ old('role', $testimonial->role ?? '') }}" placeholder="CEO, Marketing Director...">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Company</label>
                    <input type="text" name="company" class="form-input" value="{{ old('company', $testimonial->company ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Quote / Review *</label>
                    <textarea name="quote" class="form-textarea" rows="5" required maxlength="600">{{ old('quote', $testimonial->quote ?? '') }}</textarea>
                    @error('quote')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="order" class="form-input" value="{{ old('order', $testimonial->order ?? 0) }}" min="0">
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:white">
                        <span style="font-size:.85rem">Active (visible on website)</span>
                    </label>
                </div>
            </div>
        </div>

        <div>
            <div class="admin-section-card">
                <div class="admin-section-card-title">Avatar Photo</div>
                <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.08);margin:0 auto 1.5rem;overflow:hidden;display:flex;align-items:center;justify-content:center">
                    @if(isset($testimonial) && $testimonial->avatar)
                        <img src="{{ asset('storage/'.$testimonial->avatar) }}" id="avatar-preview" style="width:100%;height:100%;object-fit:cover;filter:grayscale(1)">
                    @else
                        <img id="avatar-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;filter:grayscale(1)">
                        <span style="font-family:var(--font-display);font-size:1.5rem;opacity:.3">?</span>
                    @endif
                </div>
                <input type="file" name="avatar" class="form-input" accept="image/*" data-preview="avatar-preview" style="padding:10px;font-size:.8rem">
                <p style="font-size:.72rem;margin-top:.5rem;color:rgba(255,255,255,.3)">Square image recommended. Max 1MB.</p>
            </div>

            <button type="submit" class="btn btn-dark" style="width:100%;justify-content:center">
                <span>{{ isset($testimonial) ? 'Save Changes' : 'Add Testimonial' }}</span>
            </button>
        </div>
    </div>
</form>

@endsection
