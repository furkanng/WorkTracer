@component('mail::message')
# Yeni Mesajınız Var

**{{ $message->sender->name }}** size yeni bir mesaj gönderdi.

@component('mail::panel')
{{ Str::limit($message->content, 100) }}
@endcomponent

@component('mail::button', ['url' => route($message->receiver->role->name . '.messages.index')])
Mesajları Görüntüle
@endcomponent

Saygılarımızla,<br>
{{ config('app.name') }}
@endcomponent 