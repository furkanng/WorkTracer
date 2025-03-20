@extends('admin.dashboard')

@section('title', 'Fatura Detayı')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">Fatura Bilgileri</h5>
                        <span class="badge bg-primary">Fatura No: {{ $invoice->invoice_no }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <h6 class="text-muted mb-3">Fatura Detayları</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Oluşturulma Tarihi:</span>
                                    <span class="fw-medium">{{ $invoice->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Oluşturan Kullanıcı:</span>
                                    <span class="fw-medium">{{ $invoice->transaction->user->name}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <h6 class="text-muted mb-3">Müşteri Bilgileri</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Ad Soyad:</span>
                                    <span class="fw-medium">{{ $invoice->customer->full_name }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Telefon:</span>
                                    <span class="fw-medium">{{ $invoice->customer->phone }}</span>
                                </div>
                                @if($invoice->customer->company_name)
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Firma Adı:</span>
                                    <span class="fw-medium">{{ $invoice->customer->company_name }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if($invoice->type == "debt")
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h6 class="mb-0 text-primary">Fatura Kalemleri</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                <tr>
                                                    <th>Ürün/Hizmet</th>
                                                    <th>Miktar</th>
                                                    <th>Birim Fiyat</th>
                                                    <th class="text-end">Toplam</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($invoice->items as $item)
                                                    <tr>
                                                        <td>{{ $item->price->name }}</td>
                                                        <td>{{ $item->quantity }} {{ $item->price->unit }}</td>
                                                        <td>{{ number_format($item->price->unit_price, 2) }} ₺</td>
                                                        <td class="text-end">{{ number_format($item->total, 2) }} ₺</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>Genel Toplam:</strong></td>
                                                    <td class="text-end"><strong class="text-primary">{{ number_format($invoice->total_amount, 2) }} ₺</strong></td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="d-flex">
                                <span class="text-end"><strong>Alınan Tutar:</strong></span>
                                <span class="text-end mx-4"><strong class="text-primary">{{ number_format($invoice->total_amount, 2) }} ₺</strong></span>
                            </div>

                        @endif


                        @if($invoice->description)
                            <div class="col-12">
                                <div class="p-3 border rounded">
                                    <h6 class="text-muted mb-3">Açıklama</h6>
                                    <p class="mb-0">{{ $invoice->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 