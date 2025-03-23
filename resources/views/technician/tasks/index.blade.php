@extends('technician.dashboard')

@section('title', 'Görevlerim')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Görev Listesi</h5>
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
                        <th>Başlık</th>
                        <th class="d-none d-md-table-cell">Müşteri</th>
                        <th>Durum</th>
                        <th>Aciliyet</th>
                        <th class="d-none d-sm-table-cell">Tarih</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span>{{ $task->title }}</span>
                                <small class="text-muted d-md-none">{{ $task->customer->full_name }}</small>
                                <span class="badge bg-secondary d-sm-none">{{ $task->scheduled_date->format('d.m.Y H:i') }}</span>
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $task->customer->full_name }}</td>
                        <td>
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
                        </td>
                        <td>
                            <div class="d-flex gap-2 align-items-center">
                                @switch($task->priority)
                                    @case('low')
                                        <span class="badge bg-success">Düşük</span>
                                        @break
                                    @case('medium')
                                        <span class="badge bg-info">Normal</span>
                                        @break
                                    @case('high')
                                        <span class="badge bg-warning">Yüksek</span>
                                        @break
                                    @case('urgent')
                                        <span class="badge bg-danger">Acil</span>
                                        @break
                                @endswitch
                            </div>
                        </td>
                        <td class="d-none d-sm-table-cell">{{ $task->scheduled_date->format('d.m.Y H:i') }}</td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('technician.tasks.show', $task) }}" 
                                    class="btn btn-sm btn-info" 
                                    title="Detay">
                                    <i class="bi bi-eye"></i>
                                    <span class="d-none d-md-inline">Detay</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-inbox h4 text-muted"></i>
                            <p class="text-muted mb-0">Henüz görev bulunmuyor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $tasks->links() }}
        </div>
    </div>
</div>
@endsection 