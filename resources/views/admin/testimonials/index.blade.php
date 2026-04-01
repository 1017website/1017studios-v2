@extends('layouts.admin')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 class="admin-page-title">Testimonials</h1>
        <p class="admin-page-sub">Manage client reviews shown on your website.</p>
    </div>
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-dark"><span>+ Add Testimonial</span></a>
</div>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <span class="admin-table-title">All Testimonials ({{ $testimonials->total() }})</span>
    </div>
    <table>
        <thead>
            <tr><th>Name</th><th>Role</th><th>Company</th><th>Quote</th><th>Order</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse ($testimonials as $t)
            <tr>
                <td style="font-weight:500">
                    <div style="display:flex;align-items:center;gap:.75rem">
                        @if($t->avatar)
                            <img src="{{ asset('storage/'.$t->avatar) }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;filter:grayscale(1)">
                        @else
                            <div style="width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;font-size:.85rem;font-family:var(--font-display)">{{ substr($t->name,0,1) }}</div>
                        @endif
                        {{ $t->name }}
                    </div>
                </td>
                <td style="color:var(--mid-gray)">{{ $t->role }}</td>
                <td style="color:var(--mid-gray)">{{ $t->company }}</td>
                <td style="color:rgba(255,255,255,.4);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-style:italic">"{{ $t->quote }}"</td>
                <td style="color:var(--mid-gray)">{{ $t->order }}</td>
                <td><span class="badge {{ $t->is_active ? 'badge-active' : 'badge-draft' }}">{{ $t->is_active ? 'Active' : 'Draft' }}</span></td>
                <td>
                    <div style="display:flex;gap:.5rem">
                        <a href="{{ route('admin.testimonials.edit', $t) }}" class="action-btn">Edit</a>
                        <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="color:var(--mid-gray);text-align:center;padding:3rem">No testimonials yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:1.5rem">{{ $testimonials->links() }}</div>
@endsection
