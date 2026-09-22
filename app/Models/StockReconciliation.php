<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReconciliation extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'inventory_id',
        'user_id',
        'date',
        'opening_stock',
        'stock_in',
        'stock_out_waste',
        'closing_stock',
        'use_physical',
        'plu_sales',
        'diff',
        'unit_price',
        'loss_cost',
        'reason',
        'notes',
    ];

    protected $casts = [
        'date'            => 'date',
        'opening_stock'   => 'decimal:3',
        'stock_in'        => 'decimal:3',
        'stock_out_waste' => 'decimal:3',
        'closing_stock'   => 'decimal:3',
        'use_physical'    => 'decimal:3',
        'plu_sales'       => 'decimal:3',
        'diff'            => 'decimal:3',
        'unit_price'      => 'decimal:2',
        'loss_cost'       => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
