<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['uuid', 'name','country_id'];
    protected $hidden = ['id','created_at','updated_at'];
    public function country():object
    {
        return $this->belongsTo(Country::class);
    }
    public function location():object
    {
        return $this->hasMany(location::class);
    }
}
