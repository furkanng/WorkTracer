@extends('admin.dashboard')

@section('title', 'Yeni Sekreter')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Yeni Sekreter Ekle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.secretaries.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">Şifre <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password_confirmation" class="form-label">Şifre Tekrar <span class="text-danger">*</span></label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.secretaries.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 