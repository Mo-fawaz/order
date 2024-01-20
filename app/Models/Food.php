<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $fillable = ['uuid','restaurant_id', 'name', 'components', 'price'];
    
    public function restaurant():object
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function order():object
    {
        return $this->hasMany(Order::class);
    }
}
