<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImagePathAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('storage/categories/default.jpg');
    }
}
