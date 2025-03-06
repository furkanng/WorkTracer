<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'company_name',
        'tax_number',
        'tax_office',
        'phone',
        'email',
        'address',
        'balance',
        'notes'
    ];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function transactions()
    {
        return $this->hasMany(CustomerTransaction::class);
    }

    public function getTotalDebtAttribute()
    {
        $debts = $this->transactions()->where('type', 'debt')->sum('amount');
        $payments = $this->transactions()->where('type', 'payment')->sum('amount');
        return $debts - $payments;
    }
} 