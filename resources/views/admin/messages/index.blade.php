@extends('layouts.admin')

@section('admin-content')

<h1 class="admin-page-title">Messages</h1>
<p class="admin-page-sub">Contact form submissions from your website.</p>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <span class="admin-table-title">All Messages ({{ $messages->total() }})</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Service</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($messages as $msg)
            <tr style="{{ !$msg->is_read ? 'background:rgba(255,255,255,.02)' : '' }}">
                <td style="font-weight:{{ !$msg->is_read ? '500' : '400' }}">{{ $msg->name }}</td>
                <td><a href="mailto:{{ $msg->email }}" style="color:var(--mid-gray);border-bottom:1px solid rgba(255,255,255,.1)">{{ $msg->email }}</a></td>
                <td style="color:var(--mid-gray)">{{ $msg->phone ?? '-' }}</td>
                <td>{{ $msg->service ?? '-' }}</td>
                <td style="color:rgba(255,255,255,.5);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $msg->message }}</td>
                <td style="color:var(--mid-gray);white-space:nowrap">{{ $msg->created_at->format('d M Y, H:i') }}</td>
                <td><span class="badge {{ $msg->is_read ? 'badge-draft' : 'badge-active' }}">{{ $msg->is_read ? 'Read' : 'New' }}</span></td>
                <td>
                    <div style="display:flex;gap:.5rem">
                        <a href="{{ route('admin.messages.show', $msg) }}" class="action-btn">View</a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone ?? '') }}" target="_blank" class="action-btn" style="{{ $msg->phone ? '' : 'opacity:.3;pointer-events:none' }}">WA</a>
                        <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn danger">Del</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="color:var(--mid-gray);text-align:center;padding:3rem">No messages yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:1.5rem">{{ $messages->links() }}</div>

@endsection
