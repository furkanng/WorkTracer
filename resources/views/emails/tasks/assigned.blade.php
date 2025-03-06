@component('mail::message')
# Yeni Görev Atandı

Sayın {{ $task->technician->name }},

Size yeni bir görev atandı:

**Görev:** {{ $task->title }}  
**Müşteri:** {{ $task->customer->full_name }}  
**Adres:** {{ $task->address }}  
**Planlanan Tarih:** {{ $task->scheduled_date->format('d.m.Y H:i') }}  

@switch($task->priority)
    @case('low')
        **Öncelik:** Düşük
        @break
    @case('medium')
        **Öncelik:** Normal
        @break
    @case('high')
        **Öncelik:** Yüksek
        @break
    @case('urgent')
        **Öncelik:** Acil
        @break
@endswitch

**Görev Açıklaması:**  
{{ $task->description }}

@component('mail::button', ['url' => route('technician.tasks.show', $task)])
Görevi Görüntüle
@endcomponent

İyi çalışmalar,<br>
{{ config('app.name') }}
@endcomponent 