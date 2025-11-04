<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $guarded = [];

    public function invoices()
    {
        return $this->hasMany(Invoices::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
