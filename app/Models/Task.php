<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'customer_id',
        'technician_id',
        'task_type_id',
        'description',
        'notes',
        'status',
        'completed_at',
        'address',
        'scheduled_date',
        'brand_id'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function taskType()
    {
        return $this->belongsTo(TaskType::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
} 