<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

    protected $fillable = ['outlet_id', 'category_id', 'product_id', 'quantity','scheduled_date','status'];

    public function product(){
        return $this->belongsTo(Products::class);
    }

    public function outlet(){
        return $this->belongsTo(Outlets::class);
    }

    public function gasCategory(){
        return $this->belongsTo(GasCategory::class,'category_id');
    }
}
