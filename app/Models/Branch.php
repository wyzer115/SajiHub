<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'address', 'phone', 'status', 'status_note'])]
class Branch extends Model
{
    public function admin()
    {
        return $this->hasOne(User::class)->where('role', 'admin_cabang');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
