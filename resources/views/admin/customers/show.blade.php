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
                <h3 class="@if($customer->balance > 0) text-danger @else text-success @endif">
                {{ number_format($customer->balance, 2) }} ₺
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
                        <div class="col-md-6">
                            <label for="type" class="form-label">İşlem Türü <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror"
                                    id="type" name="type" required>
                                <option value="">Seçin...</option>
                                <option value="debt" {{ old('type') == 'debt' ? 'selected' : '' }}>Borç</option>
                                <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>Ödeme</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="paymentAmountContainer" class="col-md-6 d-none">
                            <label for="payment_amount" class="form-label">Ödeme Tutarı <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                                       id="payment_amount" name="amount" value="{{ old('amount') }}">
                                <span class="input-group-text">₺</span>
                            </div>
                            @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="priceItemsContainer" class="col-12 d-none">
                            <!-- Dinamik olarak eklenecek ürün/hizmet satırları -->
                            <div class="price-items">
                                <div class="price-item mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Ürün/Hizmet</label>
                                            <select class="form-select price-select" name="items[0][price_id]">
                                                <option value="">Seçin...</option>
                                                @foreach(\App\Models\PriceList::where('is_active', true)->with('brand')->get() as $price)
                                                    <option value="{{ $price->id }}"
                                                            data-price="{{ $price->unit_price }}"
                                                            data-unit="{{ $price->unit }}">
                                                        {{ $price->name }}
                                                        @if($price->brand)
                                                            - {{ $price->brand->name }}
                                                        @endif
                                                        ({{ number_format($price->unit_price, 2) }} ₺/{{ $price->unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Miktar</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" class="form-control quantity-input"
                                                       name="items[0][quantity]" value="1">
                                                <span class="input-group-text unit-text">adet</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Tutar</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control amount-input"
                                                       name="items[0][amount]" readonly>
                                                <span class="input-group-text">₺</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="button" class="btn btn-secondary" id="addPriceItem">
                                    <i class="bi bi-plus-lg"></i> Yeni Ürün/Hizmet Ekle
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-md-9 text-end">
                                    <strong>Toplam Tutar:</strong>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="totalAmount"
                                               name="total_amount" readonly>
                                        <span class="input-group-text">₺</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary">İşlemi Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- İşlem Geçmişi -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Fatura Geçmişi</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tarih</th>
                                <th>Fatura No</th>
                                <th>Tür</th>
                                <th>Tutar</th>
                                <th class="d-none d-md-table-cell">Kullanıcı</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr class="cursor-pointer" onclick="showInvoiceDetails(this, '{{ $invoice->invoice_no }}')">
                                <td>{{ $invoice->created_at->format('d.m.Y H:i') }}</td>
                                <td>{{ $invoice->invoice_no }}</td>
                                <td>
                                    @if($invoice->type == 'payment')
                                        <span class="badge bg-success">Ödeme</span>
                                    @else
                                        <span class="badge bg-danger">Borç</span>
                                    @endif
                                </td>
                                <td>{{ number_format($invoice->total_amount, 2) }} ₺</td>
                                <td class="d-none d-md-table-cell">{{ $invoice->user->name }}</td>
                                <td class="d-none d-md-table-cell"><a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary">Görüntüle</a>
                                </td>
                            </tr>
                            <!-- Mobil görünümde detay satırı -->
                            <tr class="d-none invoice-details bg-light">
                                <td colspan="5">
                                    <div class="p-2">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>Fatura No:</strong> {{ $invoice->invoice_no }}
                                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Detay
                                            </a>
                                        </div>
                                        @if($invoice->type == 'debt')
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Ürün/Hizmet</th>
                                                            <th>Miktar</th>
                                                            <th>Birim Fiyat</th>
                                                            <th>Toplam</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($invoice->items as $item)
                                                            <tr>
                                                                <td>{{ $item->price->name }}</td>
                                                                <td>{{ $item->quantity }} {{ $item->price->unit }}</td>
                                                                <td>{{ number_format($item->unit_price, 2) }} ₺</td>
                                                                <td>{{ number_format($item->total, 2) }} ₺</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                        @if($invoice->notes)
                                            <div class="mt-2">
                                                <strong>Açıklama:</strong><br>
                                                {{ $invoice->notes }}
                                            </div>
                                        @endif
                                        <div class="mt-2">
                                            <strong>Kullanıcı:</strong> {{ $invoice->user->name }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-info-circle text-info h4"></i><br>
                                    Henüz fatura bulunmuyor
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($invoices->hasPages())
                    <div class="card-footer">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const priceItemsContainer = document.getElementById('priceItemsContainer');
    const paymentAmountContainer = document.getElementById('paymentAmountContainer');
    const addPriceItemBtn = document.getElementById('addPriceItem');
    const priceItems = document.querySelector('.price-items');
    let itemCount = 1;

    function updateAmount(row) {
        const priceSelect = row.querySelector('.price-select');
        const quantityInput = row.querySelector('.quantity-input');
        const amountInput = row.querySelector('.amount-input');
        const unitText = row.querySelector('.unit-text');

        if (priceSelect.value && quantityInput.value) {
            const selectedOption = priceSelect.options[priceSelect.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price);
            const quantity = parseFloat(quantityInput.value);
            const unit = selectedOption.dataset.unit;
            amountInput.value = (price * quantity).toFixed(2);
            unitText.textContent = unit;
        }

        updateTotalAmount();
    }

    function updateTotalAmount() {
        const amounts = document.querySelectorAll('.amount-input');
        let total = 0;
        amounts.forEach(input => {
            total += parseFloat(input.value || 0);
        });
        document.getElementById('totalAmount').value = total.toFixed(2);
    }

    function createPriceItem() {
        const template = priceItems.querySelector('.price-item').cloneNode(true);

        // Yeni satırın input ve select elemanlarını güncelle
        template.querySelectorAll('select, input').forEach(element => {
            element.name = element.name.replace('[0]', `[${itemCount}]`);
            if (element.classList.contains('amount-input')) {
                element.value = '';
            }
        });

        // Sil butonu ekle
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'btn btn-danger btn-sm mt-1';
        deleteBtn.innerHTML = '<i class="bi bi-trash"></i> Sil';
        deleteBtn.onclick = function() {
            this.closest('.price-item').remove();
            updateTotalAmount();
        };
        template.querySelector('.row').appendChild(document.createElement('div').appendChild(deleteBtn));

        // Event listener'ları ekle
        template.querySelector('.price-select').addEventListener('change', () => updateAmount(template));
        template.querySelector('.quantity-input').addEventListener('input', () => updateAmount(template));

        priceItems.appendChild(template);
        itemCount++;
    }

    typeSelect.addEventListener('change', function() {
        if (this.value === 'debt') {
            priceItemsContainer.classList.remove('d-none');
            paymentAmountContainer.classList.add('d-none');
        } else if (this.value === 'payment') {
            priceItemsContainer.classList.add('d-none');
            paymentAmountContainer.classList.remove('d-none');
        } else {
            priceItemsContainer.classList.add('d-none');
            paymentAmountContainer.classList.add('d-none');
        }
    });

    addPriceItemBtn.addEventListener('click', createPriceItem);

    // İlk satır için event listener'ları ekle
    const firstRow = priceItems.querySelector('.price-item');
    firstRow.querySelector('.price-select').addEventListener('change', () => updateAmount(firstRow));
    firstRow.querySelector('.quantity-input').addEventListener('input', () => updateAmount(firstRow));

    // Form gönderilmeden önce kontrol
    document.querySelector('form').addEventListener('submit', function(e) {
        if (typeSelect.value === 'debt') {
            const items = document.querySelectorAll('.price-select');
            let hasSelection = false;
            items.forEach(select => {
                if (select.value) hasSelection = true;
            });
            if (!hasSelection) {
                e.preventDefault();
                alert('En az bir ürün/hizmet seçmelisiniz.');
                return;
            }
            
            // Seçili ürünlerin required özelliğini ayarla
            items.forEach(select => {
                if (select.value) {
                    select.setAttribute('required', 'required');
                    select.closest('.price-item').querySelector('.quantity-input').setAttribute('required', 'required');
                }
            });
            
            // Ödeme alanını devre dışı bırak
            document.getElementById('payment_amount').removeAttribute('required');
            
        } else if (typeSelect.value === 'payment') {
            const paymentAmount = document.getElementById('payment_amount').value;
            if (!paymentAmount || paymentAmount <= 0) {
                e.preventDefault();
                alert('Lütfen geçerli bir ödeme tutarı giriniz.');
                return;
            }
            
            // Ödeme alanını required yap
            document.getElementById('payment_amount').setAttribute('required', 'required');
            
            // Ürün seçimlerini devre dışı bırak
            document.querySelectorAll('.price-select, .quantity-input').forEach(element => {
                element.removeAttribute('required');
            });
        }
    });

    // Sayfa yüklendiğinde mevcut durumu kontrol et
    if (typeSelect.value === 'debt') {
        priceItemsContainer.classList.remove('d-none');
        paymentAmountContainer.classList.add('d-none');
    } else if (typeSelect.value === 'payment') {
        priceItemsContainer.classList.add('d-none');
        paymentAmountContainer.classList.remove('d-none');
    }
});

function showInvoiceDetails(row, invoiceNo) {
    // Sadece mobil görünümde çalışır
    if (window.innerWidth < 768) {
        const detailsRow = row.nextElementSibling;
        if (detailsRow && detailsRow.classList.contains('invoice-details')) {
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
    .invoice-details:not(.d-none) {
        display: table-row !important;
    }
}
</style>
@endpush
@endsection 