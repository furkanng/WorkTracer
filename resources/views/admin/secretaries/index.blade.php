@extends('admin.dashboard')

@section('title', 'Sekreterler')

@section('content')
<div class="card">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h5 class="mb-0">Sekreterler</h5>
        <a href="{{ route('admin.secretaries.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Yeni Sekreter
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
                        <th>E-posta</th>
                        <th>Kayıt Tarihi</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($secretaries as $secretary)
                    <tr>
                        <td>{{ $secretary->id }}</td>
                        <td>{{ $secretary->name }}</td>
                        <td>{{ $secretary->email }}</td>
                        <td>{{ $secretary->created_at->format('d.m.Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.secretaries.edit', $secretary) }}" 
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                    <span class="d-none d-md-inline">Düzenle</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="bi bi-inbox h4 text-muted"></i>
                            <p class="text-muted mb-0">Henüz sekreter bulunmuyor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $secretaries->links() }}
        </div>
    </div>
</div>
@endsection 