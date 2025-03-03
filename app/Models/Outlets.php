<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlets extends Model
{
    
    protected $fillable = ['name', 'slug', 'address', 'phone','email'];

    public function outletProduct(){
        return $this->hasMany(OutletProduct::class);
    }
}
