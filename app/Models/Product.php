<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function invoiceItems()
    {
        return $this->hasMany(Invoices_Item::class);
    }

    public function getImagePathAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('storage/products/default.jpg');
    }
}
