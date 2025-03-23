@component('mail::message')
# Yeni İşlem Oluşturuldu

Yeni bir işlem oluşturuldu. İşlem detayları aşağıdaki gibidir:

**İşlem Tipi:** {{ $type === 'debt' ? 'Borç' : 'Ödeme' }}
**Tutar:** {{ number_format($transaction->amount, 2) }} ₺
**Müşteri:** {{ $transaction->customer->name }} {{ $transaction->customer->surname }}
**Tarih:** {{ $transaction->transaction_date->format('d.m.Y H:i') }}

@if($transaction->description)
**Açıklama:**
{{ $transaction->description }}
@endif

@component('mail::button', ['url' => route('secretary.customers.show', $transaction->customer_id)])
Müşteriyi Görüntüle
@endcomponent

Saygılarımızla,<br>
{{ config('app.name') }}
@endcomponent 