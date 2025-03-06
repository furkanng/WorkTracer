@extends('secretary.dashboard')

@section('title', 'Müşteriler')

@section('content')
<div class="card">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h5 class="mb-0">Müşteri Listesi</h5>
        <a href="{{ route('secretary.customers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Yeni Müşteri
        </a>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ad Soyad</th>
                        <th class="d-none d-md-table-cell">Firma</th>
                        <th class="d-none d-sm-table-cell">Telefon</th>
                        <th class="d-none d-lg-table-cell">Email</th>
                        <th>Bakiye</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>
                            {{ $customer->full_name }}
                            <div class="d-sm-none text-muted small">{{ $customer->phone }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $customer->company_name }}</td>
                        <td class="d-none d-sm-table-cell">{{ $customer->phone }}</td>
                        <td class="d-none d-lg-table-cell">{{ $customer->email }}</td>
                        <td>
                            <span class="@if($customer->balance > 0) text-danger @else text-success @endif">
                                {{ number_format($customer->balance, 2) }} ₺
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('secretary.customers.show', $customer) }}" 
                                    class="btn btn-sm btn-info" 
                                    title="İşlemler">
                                    <i class="bi bi-currency-exchange"></i>
                                    <span class="d-none d-md-inline">İşlemler</span>
                                </a>
                                <a href="{{ route('secretary.customers.edit', $customer) }}" 
                                    class="btn btn-sm btn-primary"
                                    title="Düzenle">
                                    <i class="bi bi-pencil"></i>
                                    <span class="d-none d-md-inline">Düzenle</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-info-circle text-info h4"></i><br>
                            Henüz müşteri bulunmuyor
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection 