<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class location extends Model
{
    use HasFactory;
    protected $fillable = [
        'location',
        'city_id'
    ];

    public function location_able(): MorphTo
    {
        return $this->morphTo();
    }
    public function city():object
    {
        return $this->belongsTo(City::class);
    }
    public function order():object
    {
        return $this->hasMany(Order::class);
    }
}
