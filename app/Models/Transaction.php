<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'branch_id',
        'amount',
        'payment_method',
        'status',
        'merchant_id',
        'cash_paid',
        'cash_change',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cash_paid' => 'decimal:2',
        'cash_change' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
