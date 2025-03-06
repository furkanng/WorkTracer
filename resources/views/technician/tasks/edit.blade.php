@extends('technician.dashboard')

@section('title', 'Müşteri Detayı')

@section('content')
<div class="row g-4">
    <!-- Müşteri Bilgileri -->
    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Müşteri Bilgileri</h5>
            </div>
            <div class="card-body">
                <h6>{{ $customer->full_name }}</h6>
                @if($customer->company_name)
                    <p class="text-muted mb-1">{{ $customer->company_name }}</p>
                @endif
                <div class="d-flex flex-column gap-2">
                    <div>
                        <strong><i class="bi bi-telephone"></i> Telefon:</strong><br>
                        {{ $customer->phone }}
                    </div>
                    @if($customer->email)
                        <div>
                            <strong><i class="bi bi-envelope"></i> E-posta:</strong><br>
                            {{ $customer->email }}
                        </div>
                    @endif
                    <div>
                        <strong><i class="bi bi-geo-alt"></i> Adres:</strong><br>
                        {{ $customer->address }}
                    </div>
                    @if($customer->tax_number)
                        <div>
                            <strong><i class="bi bi-building"></i> Vergi Bilgileri:</strong><br>
                            No: {{ $customer->tax_number }}<br>
                            Daire: {{ $customer->tax_office }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- İşlem Ekleme ve Geçmiş -->
    <div class="col-12 col-lg-8">
        <!-- İşlem Ekleme Formu -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">İşlem Ekle</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('technician.customers.transactions.store', $customer) }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label for="type" class="form-label">İşlem Tipi</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="payment">Ödeme Al</option>
                                <option value="debt">Borç Ekle</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="amount" class="form-label">Tutar</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                                    id="amount" name="amount" required>
                                <span class="input-group-text">₺</span>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="document_no" class="form-label">Belge No</label>
                            <input type="text" class="form-control @error('document_no') is-invalid @enderror"
                                id="document_no" name="document_no">
                            @error('document_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="2"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">İşlemi Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showTransactionDetails(row, documentNo, description) {
    // Sadece mobil görünümde çalışır
    if (window.innerWidth < 768) {
        const detailsRow = row.nextElementSibling;
        if (detailsRow && detailsRow.classList.contains('transaction-details')) {
            detailsRow.classList.toggle('d-none');
        }
    }
}
</script>

<style>
@media (max-width: 767.98px) {
    .cursor-pointer {
        cursor: pointer;
    }
    .transaction-details:not(.d-none) {
        display: table-row !important;
    }
}
</style>
@endpush
@endsection