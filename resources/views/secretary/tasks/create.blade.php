@extends('secretary.dashboard')

@section('title', 'Yeni Görev')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Yeni Görev Oluştur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('secretary.tasks.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="title" class="form-label">Görev Başlığı <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}" required>
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
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <a href="{{ route('secretary.customers.create') }}" class="text-primary small">
                                        <i class="bi bi-plus-circle"></i> Yeni Müşteri Ekle
                                    </a>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="technician_id" class="form-label">Teknisyen <span class="text-danger">*</span></label>
                                <select class="form-select @error('technician_id') is-invalid @enderror"
                                        id="technician_id" name="technician_id" required>
                                    <option value="">Teknisyen Seçin</option>
                                    @foreach($technicians as $technician)
                                        <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
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
                                        <option value="{{ $type->id }}" {{ old('task_type_id') == $type->id ? 'selected' : '' }}>
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
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Beklemede</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>Devam Ediyor</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="priority" class="form-label">Aciliyet <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror"
                                        id="priority" name="priority" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                        Düşük
                                    </option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>
                                        Normal
                                    </option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                        Yüksek
                                    </option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>
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
                                       id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" required>
                                @error('scheduled_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Adres <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Görev Açıklaması <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="brand_id" class="form-label">Marka</label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" 
                                        id="brand_id" name="brand_id">
                                    <option value="">Seçin...</option>
                                    @foreach(\App\Models\Brand::where('is_active', true)->orderBy('name')->get() as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex gap-2 justify-content-end">
                                <a href="{{ route('secretary.tasks.index') }}" class="btn btn-secondary">İptal</a>
                                <button type="submit" class="btn btn-primary">Görevi Oluştur</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 