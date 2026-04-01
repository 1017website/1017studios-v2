@extends('layouts.admin')

@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 class="admin-page-title">Message Detail</h1>
        <p class="admin-page-sub">From {{ $message->name }} — {{ $message->created_at->format('d M Y, H:i') }}</p>
    </div>
    <a href="{{ route('admin.messages.index') }}" class="action-btn">← Back</a>
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:1.5rem;max-width:900px">
    <div class="admin-section-card">
        <div class="admin-section-card-title">Message</div>
        <p style="font-size:1rem;line-height:1.8;color:rgba(255,255,255,.8)">{{ $message->message }}</p>
    </div>

    <div>
        <div class="admin-section-card">
            <div class="admin-section-card-title">Sender Info</div>
            <div style="display:flex;flex-direction:column;gap:1rem">
                <div>
                    <div class="form-label" style="margin-bottom:4px">Name</div>
                    <div style="font-size:.9rem">{{ $message->name }}</div>
                </div>
                <div>
                    <div class="form-label" style="margin-bottom:4px">Email</div>
                    <a href="mailto:{{ $message->email }}" style="font-size:.9rem;border-bottom:1px solid rgba(255,255,255,.15)">{{ $message->email }}</a>
                </div>
                @if($message->phone)
                <div>
                    <div class="form-label" style="margin-bottom:4px">Phone / WhatsApp</div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank" style="font-size:.9rem;border-bottom:1px solid rgba(255,255,255,.15)">{{ $message->phone }}</a>
                </div>
                @endif
                @if($message->service)
                <div>
                    <div class="form-label" style="margin-bottom:4px">Interested In</div>
                    <div style="font-size:.9rem">{{ $message->service }}</div>
                </div>
                @endif
                <div>
                    <div class="form-label" style="margin-bottom:4px">Received</div>
                    <div style="font-size:.9rem;color:rgba(255,255,255,.5)">{{ $message->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:.75rem">
            @if($message->phone)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank" class="btn" style="width:100%;justify-content:center">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                <span>Reply via WhatsApp</span>
            </a>
            @endif
            <a href="mailto:{{ $message->email }}" class="btn" style="width:100%;justify-content:center"><span>Reply via Email</span></a>
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn danger" style="width:100%;justify-content:center">Delete Message</button>
            </form>
        </div>
    </div>
</div>

@endsection
