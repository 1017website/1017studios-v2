@extends('layouts.admin')

@section('admin-content')

<h1 class="admin-page-title">SEO Settings</h1>
<p class="admin-page-sub">Manage meta tags, Open Graph, and structured data per page.</p>

{{-- Page Tabs --}}
<div id="seo-tabs" style="display:flex;gap:.5rem;margin-bottom:2rem;flex-wrap:wrap">
    @foreach($pages as $key => $label)
    <button
        onclick="switchTab('{{ $key }}')"
        id="tab-btn-{{ $key }}"
        style="padding:.55rem 1.2rem;font-size:.8rem;letter-spacing:.08em;text-transform:uppercase;border:1px solid rgba(255,255,255,.12);background:transparent;color:rgba(255,255,255,.4);cursor:pointer;font-family:inherit;transition:all .2s"
        class="seo-tab-btn {{ $loop->first ? 'tab-active' : '' }}"
    >{{ $label }}</button>
    @endforeach
</div>

@foreach($pages as $key => $label)
@php $s = $seoSettings[$key] ?? null; @endphp

<div id="tab-{{ $key }}" class="seo-tab-panel" style="{{ $loop->first ? '' : 'display:none' }}">
<form method="POST" action="{{ route('admin.seo.update') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="page" value="{{ $key }}">

    {{-- Meta Tags --}}
    <div class="admin-section-card">
        <div class="admin-section-card-title">Meta Tags — {{ $label }}</div>
        <div class="admin-form">
            <div class="form-group">
                <label class="form-label">Meta Title <span style="color:rgba(255,255,255,.3)">(max 200 chars)</span></label>
                <input type="text" name="meta_title" class="form-input" maxlength="200"
                    value="{{ old('meta_title', $s['meta_title'] ?? '') }}"
                    placeholder="e.g. 1017Studios | Branding & Digital Agency Surabaya">
                <small style="color:rgba(255,255,255,.3);font-size:.72rem">Leave blank to use the default title from HomeController.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Meta Description <span style="color:rgba(255,255,255,.3)">(max 320 chars)</span></label>
                <textarea name="meta_description" class="form-textarea" rows="3" maxlength="320"
                    placeholder="Short description shown in Google search results...">{{ old('meta_description', $s['meta_description'] ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Meta Keywords <span style="color:rgba(255,255,255,.3)">(comma separated)</span></label>
                <input type="text" name="meta_keywords" class="form-input" maxlength="500"
                    value="{{ old('meta_keywords', $s['meta_keywords'] ?? '') }}"
                    placeholder="branding agency surabaya, web developer surabaya, ...">
            </div>
        </div>
    </div>

    {{-- Open Graph --}}
    <div class="admin-section-card">
        <div class="admin-section-card-title">Open Graph / Social Media</div>
        <div class="admin-form">
            <div class="admin-form-row">
                <div class="form-group">
                    <label class="form-label">OG Title</label>
                    <input type="text" name="og_title" class="form-input" maxlength="200"
                        value="{{ old('og_title', $s['og_title'] ?? '') }}"
                        placeholder="Title when shared on Facebook, Twitter, etc.">
                </div>
                <div class="form-group">
                    <label class="form-label">OG Image URL <span style="color:rgba(255,255,255,.3)">(1200×630px recommended)</span></label>
                    <input type="url" name="og_image" class="form-input" maxlength="500"
                        value="{{ old('og_image', $s['og_image'] ?? '') }}"
                        placeholder="https://1017studios.com/images/og-home.jpg">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">OG Description</label>
                <textarea name="og_description" class="form-textarea" rows="2" maxlength="320"
                    placeholder="Description shown when page is shared on social media...">{{ old('og_description', $s['og_description'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Schema / JSON-LD --}}
    <div class="admin-section-card">
        <div class="admin-section-card-title">Schema / Structured Data (JSON-LD)</div>
        <div class="admin-form">
            <div class="form-group">
                <label class="form-label">JSON-LD Script</label>
                <textarea name="schema_json" class="form-textarea" rows="10"
                    style="font-family:monospace;font-size:.82rem"
                    placeholder="Paste JSON-LD here, e.g: @@context, @@type LocalBusiness, name, url, address...">{{ old('schema_json', $s['schema_json'] ?? '') }}</textarea>
                @error('schema_json')
                    <small style="color:#ff8080">{{ $message }}</small>
                @enderror
                <small style="color:rgba(255,255,255,.3);font-size:.72rem">Paste valid JSON-LD. Will be injected in a &lt;script type="application/ld+json"&gt; tag.</small>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:1rem;margin-top:1.5rem">
        <button type="submit" class="action-btn" style="padding:.75rem 2rem;font-size:.85rem">
            Save {{ $label }} SEO
        </button>
    </div>
</form>
</div>
@endforeach

{{-- Preview box --}}
<div class="admin-section-card" style="margin-top:2rem">
    <div class="admin-section-card-title">Google Search Preview</div>
    <div style="background:#fff;border-radius:8px;padding:1.5rem 2rem;max-width:600px">
        <div id="preview-title" style="color:#1a0dab;font-size:1.1rem;font-family:Arial,sans-serif;cursor:pointer;text-decoration:none">
            1017Studios | Branding & Digital Agency Surabaya
        </div>
        <div style="color:#006621;font-size:.82rem;font-family:Arial,sans-serif;margin:.2rem 0">
            https://1017studios.com/
        </div>
        <div id="preview-desc" style="color:#545454;font-size:.88rem;font-family:Arial,sans-serif;line-height:1.55">
            Studio branding dan teknologi di Surabaya. Kami merancang brand identity, memproduksi video iklan, dan membangun website serta aplikasi.
        </div>
    </div>
</div>

<style>
.seo-tab-btn.tab-active {
    background: rgba(255,255,255,.08);
    color: var(--white);
    border-color: rgba(255,255,255,.3);
}
</style>

<script>
function switchTab(key) {
    document.querySelectorAll('.seo-tab-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.seo-tab-btn').forEach(b => b.classList.remove('tab-active'));
    document.getElementById('tab-' + key).style.display = 'block';
    document.getElementById('tab-btn-' + key).classList.add('tab-active');
}

// Live preview update
function updatePreview() {
    const activePanel = document.querySelector('.seo-tab-panel:not([style*="display:none"])');
    if (!activePanel) return;
    const title = activePanel.querySelector('[name="meta_title"]')?.value;
    const desc  = activePanel.querySelector('[name="meta_description"]')?.value;
    if (title) document.getElementById('preview-title').textContent = title;
    if (desc)  document.getElementById('preview-desc').textContent  = desc;
}
document.querySelectorAll('[name="meta_title"],[name="meta_description"]').forEach(el => {
    el.addEventListener('input', updatePreview);
});
</script>

@endsection