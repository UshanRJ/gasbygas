<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutletProduct extends Model
{
    protected $fillable = ['outlet_id', 'product_id', 'stock'];

    public function product(){
        return $this->belongsTo(Products::class);
    }

    public function outlet(){
        return $this->belongsTo(Outlets::class);
    }
}
