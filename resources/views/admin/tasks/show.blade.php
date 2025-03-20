@extends('admin.dashboard')

@section('title', 'Görev Detayı')

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Görev Bilgileri</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Düzenle
                    </a>
                    <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Emin misiniz?')">
                            <i class="bi bi-trash"></i> Sil
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <h4>{{ $task->title }}</h4>
                        <div class="d-flex gap-2 align-items-center mb-3">
                            @switch($task->status)
                                @case('pending')
                                    <span class="badge bg-warning">Beklemede</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-info">Devam Ediyor</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success">Tamamlandı</span>
                                    @break
                            @endswitch
                            @switch($task->priority)
                                @case('low')
                                    <span class="badge bg-success">Düşük Öncelik</span>
                                    @break
                                @case('medium')
                                    <span class="badge bg-info">Normal Öncelik</span>
                                    @break
                                @case('high')
                                    <span class="badge bg-warning">Yüksek Öncelik</span>
                                    @break
                                @case('urgent')
                                    <span class="badge bg-danger">Acil</span>
                                    @break
                            @endswitch
                            <span class="badge bg-secondary">{{ $task->taskType->name }}</span>
                            <span class="badge bg-danger">{{ $task->brand?->name }}</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <h6>Görev Açıklaması</h6>
                        <p class="mb-4">{{ $task->description }}</p>
                    </div>

                    <div class="col-12">
                        <h6>Adres</h6>
                        <p class="mb-4">{{ $task->address }}</p>
                    </div>

                    <div class="col-md-6">
                        <h6>Planlanan Tarih</h6>
                        <p>{{ $task->scheduled_date->format('d.m.Y H:i') }}</p>
                    </div>

                    @if($task->completed_at)
                    <div class="col-md-6">
                        <h6>Tamamlanma Tarihi</h6>
                        <p>{{ $task->completed_at->format('d.m.Y H:i') }}</p>
                    </div>
                    @endif
                </div>

                <div class="col-12">
                    <h6>Faturalar</h6>
                    @if($task->invoices()->count() >= 1)
                        <ul class="list-group mb-4">
                            @foreach($task->invoices as $invoice)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span
                                    class="{{$invoice->type == "debt" ? "text-danger" : "text-success"}}"
                                    >Fatura No: {{ $invoice->invoice_no }} - Tarih: {{ $invoice->created_at->format('d.m.Y') }}</span>
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary">Görüntüle</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Bu göreve ait fatura bulunmamaktadır.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Müşteri Bilgileri</h5>
            </div>
            <div class="card-body">
                <h6>{{ $task->customer->full_name }}</h6>
                @if($task->customer->company_name)
                    <p class="text-muted mb-2">{{ $task->customer->company_name }}</p>
                @endif
                <div class="d-flex flex-column gap-2">
                    <div>
                        <i class="bi bi-telephone"></i>
                        <a href="tel:{{ $task->customer->phone }}" class="text-decoration-none">
                            {{ $task->customer->phone }}
                        </a>
                    </div>
                    @if($task->customer->email)
                        <div>
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:{{ $task->customer->email }}" class="text-decoration-none">
                                {{ $task->customer->email }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Teknisyen Bilgileri</h5>
            </div>
            <div class="card-body">
                <h6>{{ $task->technician->name }}</h6>
                <div class="d-flex flex-column gap-2">
                    <div>
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:{{ $task->technician->email }}" class="text-decoration-none">
                            {{ $task->technician->email }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- İşlem formu -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Yeni İşlem Ekle</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.customers.transactions.store', $task->customer) }}" method="POST" id="transactionForm">
            @csrf
            <input hidden="" name="task_id" value="{{$task->id}}">
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
                                        @foreach(\App\Models\PriceList::where('is_active', true)
                                            ->when($task->brand_id, function($query) use ($task) {
                                                return $query->where('brand_id', $task->brand_id);
                                            })
                                            ->with('brand')
                                            ->get() as $price)
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

        if (priceSelect.value && quantityInput.value) {
            const selectedOption = priceSelect.options[priceSelect.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price);
            const quantity = parseFloat(quantityInput.value);
            amountInput.value = (price * quantity).toFixed(2);
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
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
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
</script>
@endpush
@endsection