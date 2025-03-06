@component('mail::message')
# Görev Durumu Güncellendi

Teknisyen **{{ $task->technician->name }}** tarafından görev durumu güncellendi.

**Görev:** {{ $task->title }}  
**Müşteri:** {{ $task->customer->full_name }}  

@php
    $statusLabels = [
        'pending' => 'Beklemede',
        'in_progress' => 'Devam Ediyor',
        'completed' => 'Tamamlandı'
    ];
@endphp

**Eski Durum:** {{ $statusLabels[$oldStatus] }}  
**Yeni Durum:** {{ $statusLabels[$newStatus] }}  

@if($newStatus === 'completed')
**Tamamlanma Tarihi:** {{ $task->completed_at->format('d.m.Y H:i') }}
@endif

@component('mail::button', ['url' => route('admin.tasks.show', $task)])
Görevi Görüntüle
@endcomponent

Saygılarımızla,<br>
{{ config('app.name') }}
@endcomponent 