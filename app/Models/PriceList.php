<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'unit_price',
        'unit',
        'is_active',
        'brand_id'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
} 