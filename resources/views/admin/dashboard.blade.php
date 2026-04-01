@extends('layouts.admin')

@section('admin-content')

<h1 class="admin-page-title">Dashboard</h1>
<p class="admin-page-sub">Welcome back, {{ Auth::user()->name ?? 'Admin' }}. Here's what's happening.</p>

<!-- Stats -->
<div class="admin-stats">
    <div class="admin-stat-card">
        <div class="admin-stat-value">{{ $stats['portfolio'] ?? 0 }}</div>
        <div class="admin-stat-label">Portfolio Items</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-value">{{ $stats['services'] ?? 0 }}</div>
        <div class="admin-stat-label">Services</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-value">{{ $stats['testimonials'] ?? 0 }}</div>
        <div class="admin-stat-label">Testimonials</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-value">{{ $stats['messages'] ?? 0 }}</div>
        <div class="admin-stat-label">New Messages</div>
    </div>
</div>

<!-- Recent Messages -->
<div class="admin-table-wrap" style="margin-bottom:2rem">
    <div class="admin-table-header">
        <span class="admin-table-title">Recent Messages</span>
        <a href="{{ route('admin.messages.index') }}" class="action-btn">View All</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Service</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentMessages ?? [] as $msg)
            <tr>
                <td>{{ $msg->name }}</td>
                <td style="color:var(--mid-gray)">{{ $msg->email }}</td>
                <td>{{ $msg->service }}</td>
                <td style="color:var(--mid-gray)">{{ $msg->created_at->format('d M Y') }}</td>
                <td><span class="badge {{ $msg->is_read ? 'badge-draft' : 'badge-active' }}">{{ $msg->is_read ? 'Read' : 'New' }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" style="color:var(--mid-gray);text-align:center;padding:2rem">No messages yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Quick Actions -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem">
    <a href="{{ route('admin.portfolio.create') }}" class="admin-stat-card" style="display:flex;align-items:center;gap:1rem;text-decoration:none;transition:border-color .2s">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <div>
            <div style="font-size:.85rem;font-weight:500">Add Portfolio Item</div>
            <div class="admin-stat-label" style="margin-top:2px">Showcase new work</div>
        </div>
    </a>
    <a href="{{ route('admin.services.create') }}" class="admin-stat-card" style="display:flex;align-items:center;gap:1rem;text-decoration:none;transition:border-color .2s">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <div>
            <div style="font-size:.85rem;font-weight:500">Add Service</div>
            <div class="admin-stat-label" style="margin-top:2px">List a new offering</div>
        </div>
    </a>
    <a href="{{ route('admin.testimonials.create') }}" class="admin-stat-card" style="display:flex;align-items:center;gap:1rem;text-decoration:none;transition:border-color .2s">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <div>
            <div style="font-size:.85rem;font-weight:500">Add Testimonial</div>
            <div class="admin-stat-label" style="margin-top:2px">Add client review</div>
        </div>
    </a>
</div>

@endsection
