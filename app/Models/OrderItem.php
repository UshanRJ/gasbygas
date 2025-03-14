<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','product_id','quantity','unit_amount','total_amount','order_token', 'scheduled_date'];

    public function product(){
        return $this->belongsTo(Products::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    protected $casts = [
        'scheduled_date' => 'date:Y-m-d',
    ];


}
