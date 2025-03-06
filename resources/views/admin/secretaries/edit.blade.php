@extends('admin.dashboard')

@section('title', 'Sekreter Düzenle')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sekreter Düzenle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.secretaries.update', $secretary) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $secretary->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $secretary->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">Yeni Şifre</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            <small class="text-muted">Şifreyi değiştirmek istemiyorsanız boş bırakın</small>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password_confirmation" class="form-label">Yeni Şifre Tekrar</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.secretaries.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">Güncelle</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 