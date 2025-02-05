<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = ['order_id','first_name','last_name','mobile','nic','business_id','email','address','district','certificate_image','user_type'];

    protected $casts = [
        'certificate_image' => 'array'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function getFullNameAttribute(){
        return $this->firstname .' '. $this->lastname;
}
}
