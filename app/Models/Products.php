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

    public function gasCategry(){
        return $this->belongsTo(GasCategory::class);
    }

    public function orderItem(){
        return $this->hasMany(OrderItem::class);
}


}
