@extends('technician.dashboard')

@section('title', 'Mesajlar')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Mesajlar - {{ $user->name }}</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            @foreach($messages as $message)
            <div class="mb-2">
                <strong>{{ $message->sender->name }}:</strong>
                <p class="mb-1">{{ $message->content }}</p>
                <small class="text-muted">{{ $message->created_at->format('d.m.Y H:i') }}</small>
            </div>
            @endforeach
        </div>
        <form action="{{ route('technician.messages.store', $user) }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="content" class="form-control" placeholder="Mesajınızı yazın..." required>
                <button class="btn btn-primary" type="submit">Gönder</button>
            </div>
        </form>
    </div>
</div>
@endsection 