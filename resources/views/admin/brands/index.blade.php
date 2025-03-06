@extends('admin.dashboard')

@section('title', 'Markalar')

@section('content')
<div class="card">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h5 class="mb-0">Marka Listesi</h5>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Yeni Marka
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
                        <th>Durum</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>
                        <td>
                            {{ $brand->name }}
                            @if($brand->description)
                                <br>
                                <small class="text-muted">{{ $brand->description }}</small>
                            @endif
                        </td>
                        <td>
                            @if($brand->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Pasif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.brands.edit', $brand) }}" 
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                    <span class="d-none d-md-inline">Düzenle</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="bi bi-inbox h4 text-muted"></i>
                            <p class="text-muted mb-0">Henüz marka bulunmuyor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $brands->links() }}
        </div>
    </div>
</div>
@endsection 