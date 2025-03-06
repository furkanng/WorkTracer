@extends('admin.dashboard')

@section('title', 'Teknisyen Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Teknisyen Düzenle - {{ $user->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Ad Soyad</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Yeni Şifre (Opsiyonel)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                    id="password" name="password">
                <div class="form-text">Şifreyi değiştirmek istemiyorsanız boş bırakın.</div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Yeni Şifre Tekrar</label>
                <input type="password" class="form-control" 
                    id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary">Güncelle</button>
        </form>
    </div>
</div>
@endsection 