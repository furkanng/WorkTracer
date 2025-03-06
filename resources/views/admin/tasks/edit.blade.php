@extends('admin.dashboard')

@section('title', 'Görev Düzenle')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Görev Düzenle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="title" class="form-label">Görev Başlığı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                id="title" name="title" value="{{ old('title', $task->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="customer_id" class="form-label">Müşteri <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                id="customer_id" name="customer_id" required>
                                <option value="">Müşteri Seçin</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                        {{ old('customer_id', $task->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="technician_id" class="form-label">Teknisyen <span class="text-danger">*</span></label>
                            <select class="form-select @error('technician_id') is-invalid @enderror" 
                                id="technician_id" name="technician_id" required>
                                <option value="">Teknisyen Seçin</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->id }}" 
                                        {{ old('technician_id', $task->technician_id) == $technician->id ? 'selected' : '' }}>
                                        {{ $technician->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('technician_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="task_type_id" class="form-label">Görev Türü <span class="text-danger">*</span></label>
                            <select class="form-select @error('task_type_id') is-invalid @enderror" 
                                id="task_type_id" name="task_type_id" required>
                                <option value="">Görev Türü Seçin</option>
                                @foreach($taskTypes as $type)
                                    <option value="{{ $type->id }}" 
                                        {{ old('task_type_id', $task->task_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('task_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label">Durum <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                                <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>
                                    Beklemede
                                </option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>
                                    Devam Ediyor
                                </option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>
                                    Tamamlandı
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="priority" class="form-label">Aciliyet <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" 
                                id="priority" name="priority" required>
                                <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>
                                    Düşük
                                </option>
                                <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>
                                    Normal
                                </option>
                                <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>
                                    Yüksek
                                </option>
                                <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>
                                    Acil
                                </option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="scheduled_date" class="form-label">Planlanan Tarih <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('scheduled_date') is-invalid @enderror" 
                                id="scheduled_date" name="scheduled_date" 
                                value="{{ old('scheduled_date', $task->scheduled_date->format('Y-m-d\TH:i')) }}" required>
                            @error('scheduled_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Adres <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" rows="3" required>{{ old('address', $task->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Görev Açıklaması <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="4" required>{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="brand_id" class="form-label">Marka</label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" 
                                    id="brand_id" name="brand_id">
                                <option value="">Seçin...</option>
                                @foreach(\App\Models\Brand::where('is_active', true)->orderBy('name')->get() as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $task->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 