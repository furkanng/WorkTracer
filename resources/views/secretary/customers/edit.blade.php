@extends('secretary.dashboard')

@section('title', 'Müşteri Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Müşteri Düzenle</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('secretary.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Ad <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                        id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="surname" class="form-label">Soyad <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('surname') is-invalid @enderror" 
                        id="surname" name="surname" value="{{ old('surname', $customer->surname) }}" required>
                    @error('surname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="company_name" class="form-label">Firma Adı</label>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                        id="company_name" name="company_name" value="{{ old('company_name', $customer->company_name) }}">
                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Telefon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                        id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-posta</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                        id="email" name="email" value="{{ old('email', $customer->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tax_number" class="form-label">Vergi Numarası</label>
                    <input type="text" class="form-control @error('tax_number') is-invalid @enderror" 
                        id="tax_number" name="tax_number" value="{{ old('tax_number', $customer->tax_number) }}">
                    @error('tax_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tax_office" class="form-label">Vergi Dairesi</label>
                    <input type="text" class="form-control @error('tax_office') is-invalid @enderror" 
                        id="tax_office" name="tax_office" value="{{ old('tax_office', $customer->tax_office) }}">
                    @error('tax_office')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="address" class="form-label">Adres <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                        id="address" name="address" rows="3" required>{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="notes" class="form-label">Notlar</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                        id="notes" name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('secretary.customers.index') }}" class="btn btn-secondary">İptal</a>
                <button type="submit" class="btn btn-primary">Güncelle</button>
            </div>
        </form>
    </div>
</div>
@endsection 