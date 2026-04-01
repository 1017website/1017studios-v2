@extends('layouts.admin')

@section('admin-content')

<h1 class="admin-page-title">Settings</h1>
<p class="admin-page-sub">Manage your company information and site content.</p>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="admin-section-card">
        <div class="admin-section-card-title">Company Information</div>
        <div class="admin-form">
            <div class="admin-form-row">
                <div class="form-group">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-input" value="{{ old('company_name', $settings['company_name'] ?? '1017Studios') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tagline</label>
                    <input type="text" name="tagline" class="form-input" value="{{ old('tagline', $settings['tagline'] ?? 'We build brands that move people.') }}">
                </div>
            </div>
            <div class="admin-form-row">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $settings['email'] ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">WhatsApp Number</label>
                    <input type="text" name="whatsapp" class="form-input" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" placeholder="628xxxxxxxxxx">
                    <small style="color:rgba(255,255,255,.3);font-size:.72rem">Format: 628xxxxxxxxxx (country code, no +)</small>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-textarea" rows="2">{{ old('address', $settings['address'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="admin-section-card">
        <div class="admin-section-card-title">Statistics (Homepage)</div>
        <div class="admin-form">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem">
                <div class="form-group">
                    <label class="form-label">Projects Delivered</label>
                    <input type="number" name="stat_projects" class="form-input" value="{{ old('stat_projects', $settings['stat_projects'] ?? 150) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Happy Clients</label>
                    <input type="number" name="stat_clients" class="form-input" value="{{ old('stat_clients', $settings['stat_clients'] ?? 80) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Years of Experience</label>
                    <input type="number" name="stat_years" class="form-input" value="{{ old('stat_years', $settings['stat_years'] ?? 5) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Satisfaction (%)</label>
                    <input type="number" name="stat_satisfaction" class="form-input" value="{{ old('stat_satisfaction', $settings['stat_satisfaction'] ?? 98) }}" max="100">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-section-card">
        <div class="admin-section-card-title">Social Media</div>
        <div class="admin-form">
            <div class="admin-form-row">
                <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" name="instagram" class="form-input" value="{{ old('instagram', $settings['instagram'] ?? '') }}" placeholder="https://instagram.com/...">
                </div>
                <div class="form-group">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="url" name="linkedin" class="form-input" value="{{ old('linkedin', $settings['linkedin'] ?? '') }}" placeholder="https://linkedin.com/...">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-section-card">
        <div class="admin-section-card-title">SEO</div>
        <div class="admin-form">
            <div class="form-group">
                <label class="form-label">Meta Description</label>
                <textarea name="meta_description" class="form-textarea" rows="2">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-dark" style="padding:18px 48px">
        <span>Save Settings</span>
    </button>
</form>

@endsection
