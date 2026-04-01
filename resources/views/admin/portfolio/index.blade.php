@extends('layouts.admin')

@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 class="admin-page-title">Portfolio</h1>
        <p class="admin-page-sub">Manage your showcase projects.</p>
    </div>
    <a href="{{ route('admin.portfolio.create') }}" class="btn btn-dark"><span>+ Add New</span></a>
</div>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <span class="admin-table-title">All Projects ({{ $portfolios->total() }})</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Category</th>
                <th>Order</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($portfolios as $item)
            <tr>
                <td>
                    @if ($item->thumbnail)
                        <img src="{{ asset('storage/'.$item->thumbnail) }}" style="width:60px;height:40px;object-fit:cover;filter:grayscale(1)">
                    @else
                        <div style="width:60px;height:40px;background:rgba(255,255,255,.05);display:flex;align-items:center;justify-content:center;font-size:.65rem;color:var(--mid-gray)">No img</div>
                    @endif
                </td>
                <td style="font-weight:500">{{ $item->title }}</td>
                <td style="color:var(--mid-gray)">{{ $item->category }}</td>
                <td style="color:var(--mid-gray)">{{ $item->order }}</td>
                <td><span class="badge {{ $item->is_active ? 'badge-active' : 'badge-draft' }}">{{ $item->is_active ? 'Active' : 'Draft' }}</span></td>
                <td>
                    <div style="display:flex;gap:.5rem">
                        <a href="{{ route('admin.portfolio.edit', $item) }}" class="action-btn">Edit</a>
                        <form method="POST" action="{{ route('admin.portfolio.destroy', $item) }}" onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="color:var(--mid-gray);text-align:center;padding:3rem">No portfolio items yet. <a href="{{ route('admin.portfolio.create') }}" style="color:var(--white);border-bottom:1px solid rgba(255,255,255,.3)">Add one now.</a></td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:1.5rem">
    {{ $portfolios->links() }}
</div>

@endsection
