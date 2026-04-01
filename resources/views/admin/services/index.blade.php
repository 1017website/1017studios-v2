{{-- resources/views/admin/services/index.blade.php --}}
@extends('layouts.admin')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 class="admin-page-title">Services</h1>
        <p class="admin-page-sub">Manage the services shown on your website.</p>
    </div>
    <a href="{{ route('admin.services.create') }}" class="btn btn-dark"><span>+ Add Service</span></a>
</div>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <span class="admin-table-title">All Services ({{ $services->total() }})</span>
    </div>
    <table>
        <thead>
            <tr><th>Order</th><th>Title</th><th>Tags</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse ($services as $service)
            <tr>
                <td style="color:var(--mid-gray)">{{ $service->order }}</td>
                <td style="font-weight:500">{{ $service->title }}</td>
                <td style="color:rgba(255,255,255,.4);font-size:.82rem">{{ Str::limit($service->tags, 60) }}</td>
                <td><span class="badge {{ $service->is_active ? 'badge-active' : 'badge-draft' }}">{{ $service->is_active ? 'Active' : 'Draft' }}</span></td>
                <td>
                    <div style="display:flex;gap:.5rem">
                        <a href="{{ route('admin.services.edit', $service) }}" class="action-btn">Edit</a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="color:var(--mid-gray);text-align:center;padding:3rem">No services yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:1.5rem">{{ $services->links() }}</div>
@endsection
