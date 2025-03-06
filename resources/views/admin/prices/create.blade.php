@extends('admin.dashboard')

@section('title', 'Yeni Fiyat')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Yeni Fiyat Ekle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.prices.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Hizmet/Ürün Adı <span class="text-danger">*</span></label>
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
                            <label for="brand_id" class="form-label">Marka</label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" 
                                    id="brand_id" name="brand_id">
                                <option value="">Seçin...</option>
                                @foreach(\App\Models\Brand::where('is_active', true)->orderBy('name')->get() as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="unit_price" class="form-label">Birim Fiyat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror"
                                       id="unit_price" name="unit_price" value="{{ old('unit_price') }}" required>
                                <span class="input-group-text">₺</span>
                            </div>
                            @error('unit_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="unit" class="form-label">Birim <span class="text-danger">*</span></label>
                            <select class="form-select @error('unit') is-invalid @enderror" 
                                    id="unit" name="unit" required>
                                <option value="">Seçin...</option>
                                <option value="adet" {{ old('unit') == 'adet' ? 'selected' : '' }}>Adet</option>
                                <option value="saat" {{ old('unit') == 'saat' ? 'selected' : '' }}>Saat</option>
                                <option value="gün" {{ old('unit') == 'gün' ? 'selected' : '' }}>Gün</option>
                                <option value="ay" {{ old('unit') == 'ay' ? 'selected' : '' }}>Ay</option>
                                <option value="metre" {{ old('unit') == 'metre' ? 'selected' : '' }}>Metre</option>
                                <option value="m²" {{ old('unit') == 'm²' ? 'selected' : '' }}>Metrekare</option>
                            </select>
                            @error('unit')
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
                            <a href="{{ route('admin.prices.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 