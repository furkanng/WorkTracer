<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'amount',
        'description'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
} 