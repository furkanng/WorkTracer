@extends('admin.dashboard')

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

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Bakiye Durumu</h5>
            </div>
            <div class="card-body">
                <h3 class="@if($customer->total_debt > 0) text-danger @else text-success @endif">
                    {{ number_format($customer->total_debt, 2) }} ₺
                </h3>
                <p class="text-muted mb-0">Güncel Borç Durumu</p>
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

                <form action="{{ route('admin.customers.transactions.store', $customer) }}" method="POST">
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

        <!-- İşlem Geçmişi -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">İşlem Geçmişi</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tarih</th>
                                <th>İşlem</th>
                                <th>Tutar</th>
                                <th class="d-none d-md-table-cell">Belge No</th>
                                <th class="d-none d-md-table-cell">Kullanıcı</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->transactions()->latest()->get() as $transaction)
                            <tr class="cursor-pointer" onclick="showTransactionDetails(this, '{{ $transaction->document_no }}', '{{ $transaction->description }}')">
                                <td>{{ $transaction->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    @if($transaction->type == 'payment')
                                        <span class="badge bg-success">Ödeme</span>
                                    @else
                                        <span class="badge bg-danger">Borç</span>
                                    @endif
                                </td>
                                <td>{{ number_format($transaction->amount, 2) }} ₺</td>
                                <td class="d-none d-md-table-cell">{{ $transaction->document_no }}</td>
                                <td class="d-none d-md-table-cell">{{ $transaction->user->name }}</td>
                            </tr>
                            <!-- Mobil görünümde detay satırı -->
                            <tr class="d-none transaction-details bg-light">
                                <td colspan="3">
                                    <div class="p-2">
                                        @if($transaction->document_no)
                                            <strong>Belge No:</strong> {{ $transaction->document_no }}<br>
                                        @endif
                             
                                            <strong>Kullanıcı:</strong> {{ $transaction->user->name }}
                            
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-info-circle text-info h4"></i><br>
                                    Henüz işlem bulunmuyor
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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