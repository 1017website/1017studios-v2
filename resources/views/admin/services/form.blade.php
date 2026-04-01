@extends('layouts.admin')

@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 class="admin-page-title">{{ isset($service) ? 'Edit Service' : 'New Service' }}</h1>
        <p class="admin-page-sub">{{ isset($service) ? 'Update service details.' : 'Add a new service offering.' }}</p>
    </div>
    <a href="{{ route('admin.services.index') }}" class="action-btn">← Back</a>
</div>

<form method="POST" action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" style="max-width:800px">
    @csrf
    @if (isset($service)) @method('PUT') @endif

    <div class="admin-section-card">
        <div class="admin-section-card-title">Service Details</div>
        <div class="admin-form">
            <div class="form-group">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-input" value="{{ old('title', $service->title ?? '') }}" required>
                @error('title')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Short Description * <span style="color:rgba(255,255,255,.3)">(shown on cards, max 400 chars)</span></label>
                <textarea name="short_description" class="form-textarea" rows="3" required maxlength="400">{{ old('short_description', $service->short_description ?? '') }}</textarea>
                @error('short_description')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Full Description <span style="color:rgba(255,255,255,.3)">(optional, shown on services page)</span></label>
                <textarea name="full_description" class="form-textarea" rows="5">{{ old('full_description', $service->full_description ?? '') }}</textarea>
            </div>
            <div class="admin-form-row">
                <div class="form-group">
                    <label class="form-label">Tags <span style="color:rgba(255,255,255,.3)">(comma separated)</span></label>
                    <input type="text" name="tags" class="form-input" value="{{ old('tags', $service->tags ?? '') }}" placeholder="Logo Design, Branding, Identity">
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="order" class="form-input" value="{{ old('order', $service->order ?? 0) }}" min="0">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Icon SVG <span style="color:rgba(255,255,255,.3)">(paste raw SVG code)</span></label>
                <textarea name="icon_svg" class="form-textarea" rows="4" placeholder="<svg ...>...</svg>">{{ old('icon_svg', $service->icon_svg ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:white">
                    <span style="font-size:.85rem">Active (visible on website)</span>
                </label>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-dark" style="padding:16px 48px">
        <span>{{ isset($service) ? 'Save Changes' : 'Create Service' }}</span>
    </button>
</form>

@endsection
