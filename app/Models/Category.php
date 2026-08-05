<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['branch_id', 'name'])]
class Category extends Model
{
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
