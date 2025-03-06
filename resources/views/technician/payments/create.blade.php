@extends('technician.dashboard')

@section('title', 'Ödeme Ekle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Ödeme Ekle - {{ $task->title }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('technician.payments.store', $task) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="transaction_type" class="form-label">İşlem Tipi</label>
                <select class="form-select" id="transaction_type" name="transaction_type" required>
                    <option value="debit">Borç</option>
                    <option value="credit">Alacak</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Tutar</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="document_no" class="form-label">Belge No</label>
                <input type="text" class="form-control" id="document_no" name="document_no">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Açıklama</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kaydet</button>
        </form>
    </div>
</div>
@endsection 