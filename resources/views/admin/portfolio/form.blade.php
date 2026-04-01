@extends('layouts.admin')

@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 class="admin-page-title">{{ isset($portfolio) ? 'Edit Project' : 'New Project' }}</h1>
        <p class="admin-page-sub">{{ isset($portfolio) ? 'Update portfolio item details.' : 'Add a new project to your portfolio.' }}</p>
    </div>
    <a href="{{ route('admin.portfolio.index') }}" class="action-btn">← Back</a>
</div>

<form method="POST" action="{{ isset($portfolio) ? route('admin.portfolio.update', $portfolio) : route('admin.portfolio.store') }}" enctype="multipart/form-data">
    @csrf
    @if (isset($portfolio)) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">
        <div>
            <div class="admin-section-card">
                <div class="admin-section-card-title">Project Details</div>
                <div class="admin-form">
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-input" value="{{ old('title', $portfolio->title ?? '') }}" required>
                        @error('title')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
                    </div>
                    <div class="admin-form-row">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-input" value="{{ old('category', $portfolio->category ?? '') }}" placeholder="e.g. Branding, Web, App">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Client Name</label>
                            <input type="text" name="client" class="form-input" value="{{ old('client', $portfolio->client ?? '') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="4">{{ old('description', $portfolio->description ?? '') }}</textarea>
                    </div>
                    <div class="admin-form-row">
                        <div class="form-group">
                            <label class="form-label">Project URL</label>
                            <input type="url" name="project_url" class="form-input" value="{{ old('project_url', $portfolio->project_url ?? '') }}" placeholder="https://">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Year</label>
                            <input type="number" name="year" class="form-input" value="{{ old('year', $portfolio->year ?? date('Y')) }}" min="2000" max="{{ date('Y') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tags (comma separated)</label>
                        <input type="text" name="tags" class="form-input" value="{{ old('tags', $portfolio->tags ?? '') }}" placeholder="Logo, Identity, Web">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="admin-section-card">
                <div class="admin-section-card-title">Thumbnail</div>
                @if (isset($portfolio) && $portfolio->thumbnail)
                    <img src="{{ asset('storage/'.$portfolio->thumbnail) }}" style="width:100%;aspect-ratio:16/10;object-fit:cover;filter:grayscale(1);margin-bottom:1rem">
                @else
                    <div style="width:100%;aspect-ratio:16/10;background:rgba(255,255,255,.04);display:flex;align-items:center;justify-content:center;margin-bottom:1rem" id="preview-container">
                        <img id="thumb-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;filter:grayscale(1)">
                        <span id="preview-placeholder" style="font-size:.75rem;color:var(--mid-gray)">No image</span>
                    </div>
                @endif
                <input type="file" name="thumbnail" class="form-input" accept="image/*" data-preview="thumb-preview" onchange="document.getElementById('preview-placeholder')?.style.setProperty('display','none');document.getElementById('thumb-preview').style.display='block'" style="padding:10px">
                <p style="font-size:.72rem;margin-top:.5rem;color:rgba(255,255,255,.3)">JPG, PNG, WebP. Recommended: 1200×750px</p>
            </div>

            <div class="admin-section-card">
                <div class="admin-section-card-title">Publish Settings</div>
                <div class="form-group" style="margin-bottom:1rem">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="order" class="form-input" value="{{ old('order', $portfolio->order ?? 0) }}" min="0">
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $portfolio->is_active ?? true) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:white">
                        <span style="font-size:.85rem">Active (visible on website)</span>
                    </label>
                </div>
                <div class="form-group" style="margin-top:1rem">
                    <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $portfolio->is_featured ?? false) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:white">
                        <span style="font-size:.85rem">Featured (shown on homepage)</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-dark" style="width:100%;justify-content:center">
                <span>{{ isset($portfolio) ? 'Save Changes' : 'Create Project' }}</span>
            </button>
        </div>
    </div>
</form>

@endsection
