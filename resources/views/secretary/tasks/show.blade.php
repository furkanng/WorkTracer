@extends('secretary.dashboard')

@section('title', 'Görev Detayı')

@section('content')
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Görev Bilgileri</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('secretary.tasks.edit', $task) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Düzenle
                        </a>
                        <form action="{{ route('secretary.tasks.destroy', $task) }}" method="POST" class="d-inline">
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
@endsection 