<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'category',
        'stock',
        'unit',
        'min_stock',
        'unit_price',
        'notes',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function menuIngredients()
    {
        return $this->hasMany(MenuIngredient::class);
    }

    public function reconciliations()
    {
        return $this->hasMany(StockReconciliation::class);
    }
}
