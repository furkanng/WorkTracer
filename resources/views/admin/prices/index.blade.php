@extends('admin.dashboard')

@section('title', 'Fiyatlandırma')

@section('content')
<div class="card">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h5 class="mb-0">Fiyat Listesi</h5>
        <a href="{{ route('admin.prices.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Yeni Fiyat
        </a>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ad</th>
                        <th>Marka</th>
                        <th>Birim Fiyat</th>
                        <th>Birim</th>
                        <th>Durum</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prices as $price)
                    <tr>
                        <td>{{ $price->id }}</td>
                        <td>
                            {{ $price->name }}
                            @if($price->description)
                                <br>
                                <small class="text-muted">{{ $price->description }}</small>
                            @endif
                        </td>
                        <td>{{ $price->brand->name }}</td>
                        <td>{{ number_format($price->unit_price, 2) }} ₺</td>
                        <td>{{ $price->unit }}</td>
                        <td>
                            @if($price->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Pasif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.prices.edit', $price) }}" 
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                    <span class="d-none d-md-inline">Düzenle</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-inbox h4 text-muted"></i>
                            <p class="text-muted mb-0">Henüz fiyat bulunmuyor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $prices->links() }}
        </div>
    </div>
</div>
@endsection 