<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['branch_id', 'category_id', 'name', 'price', 'image', 'status'])]
class Menu extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public const LOCKED_DEFAULT_IMAGES = [
        'Nasi Goreng Spesial' => 'images/landing/nasi-goreng.jpg',
        'Mie Goreng Seafood'  => 'images/landing/mie-goreng.jpg',
        'Ayam Bakar Madu'     => 'images/landing/ayam-bakar.jpg',
        'Sate Ayam'           => 'images/landing/sate.jpg',
        'French Fries'        => 'images/landing/french-fries.jpg',
        'Pisang Goreng Keju'  => 'images/landing/pisang-goreng.jpg',
        'Es Teh Manis'        => 'images/landing/es-teh.jpg',
        'Jus Alpukat'         => 'images/landing/jus-alpukat.jpg',
        'Kopi Hitam Spesial'  => 'images/landing/kopi-hitam.jpg',
    ];

    public function getImageUrlAttribute(): ?string
    {
        $img = $this->image;

        // If no image is set, lock to standard default menu image
        if (!$img) {
            $img = self::LOCKED_DEFAULT_IMAGES[$this->name] ?? null;
        }

        if (!$img) return null;

        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }

        if (str_starts_with($img, 'images/')) {
            return asset($img);
        }

        return asset('storage/' . $img);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ingredients()
    {
        return $this->hasMany(MenuIngredient::class);
    }

    public function menuIngredients()
    {
        return $this->hasMany(MenuIngredient::class);
    }
}
