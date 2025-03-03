<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','name','slug','image','weight','price','description','is_active','is_stock','on_sale'];

    protected $casts = [
        'image' => 'array'
    ];

    public function gasCategory(){
        return $this->belongsTo(GasCategory::class,'category_id');
    }

    public function orderItem(){
        return $this->hasMany(OrderItem::class);
    }

    public function outletProduct(){
        return $this->hasMany(OutletProduct::class);
    }
    public function schedule(){
        return $this->hasMany(Schedule::class);
    }

}
