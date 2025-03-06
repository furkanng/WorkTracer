@extends('admin.dashboard')

@section('title', 'Teknisyenler')

@section('content')
<div class="card">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h5 class="mb-0">Teknisyen Listesi</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Yeni Teknisyen
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
                        <th>Ad Soyad</th>
                        <th>Email</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($technicians as $technician)
                    <tr>
                        <td>{{ $technician->id }}</td>
                        <td>{{ $technician->name }}</td>
                        <td>{{ $technician->email }}</td>
                        <td>{{ $technician->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.edit', $technician) }}" 
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
                        <td colspan="4" class="text-center py-4">
                            <i class="bi bi-people h4 text-muted"></i>
                            <p class="text-muted mb-0">Henüz teknisyen bulunmuyor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $technicians->links() }}
        </div>
    </div>
</div>
@endsection 