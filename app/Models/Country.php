<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = ['uuid','name'];
    protected $hidden = ['id','created_at','updated_at'];
    public function city():object
    {
        return $this->hasOne(City::class);
    }

}
