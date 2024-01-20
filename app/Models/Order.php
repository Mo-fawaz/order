<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['uuid','user_id','food_id','price_order','order_type','location_id'];
    public function food():object
    {
        return $this->belongsTo(Food::class);
    }
    public function user():object
    {
        return $this->belongsTo(User::class);
    }
    public function location():object
    {
        return $this->belongsTo(location::class);
    }
}
