<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'inventory_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
