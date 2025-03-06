@extends('admin.dashboard')

@section('title', 'Yeni Marka')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Yeni Marka Ekle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.brands.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Marka Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror"
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                            @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 