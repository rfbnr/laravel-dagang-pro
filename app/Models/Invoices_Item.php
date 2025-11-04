<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices_Item extends Model
{
    protected $table = 'invoices_items';
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoices::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
