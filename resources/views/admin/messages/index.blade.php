@extends('admin.dashboard')

@section('title', 'Mesajlar')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Kullanıcılar</h5>
    </div>
    <div class="card-body">
        <ul class="list-group">
            @foreach($users as $user)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.messages.show', $user) }}" class="text-decoration-none">
                    {{ $user->name }}
                </a>
                @php
                    $unreadCount = $user->receivedMessages()->where('sender_id', $user->id)->where('is_read', false)->count();
                @endphp
                @if($unreadCount > 0)
                <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection 