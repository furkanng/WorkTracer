@extends('technician.dashboard')

@section('title', 'Ödeme Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Ödeme Düzenle - {{ $task->title }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('technician.payments.update', [$task, $transaction]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="transaction_type" class="form-label">İşlem Tipi</label>
                <select class="form-select" id="transaction_type" name="transaction_type" required>
                    <option value="debt" {{ $transaction->transaction_type == 'debit' ? 'selected' : '' }}>Borç</option>
                    <option value="payment" {{ $transaction->transaction_type == 'credit' ? 'selected' : '' }}>Alacak</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Tutar</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ $transaction->amount }}" required>
            </div>
            <div class="mb-3">
                <label for="document_no" class="form-label">Belge No</label>
                <input type="text" class="form-control" id="document_no" name="document_no" value="{{ $transaction->document_no }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Açıklama</label>
                <textarea class="form-control" id="description" name="description">{{ $transaction->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
        </form>
    </div>
</div>
@endsection 