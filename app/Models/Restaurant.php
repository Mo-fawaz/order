<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = ['uuid', 'name', 'type_id'];
    protected $hidden = ['id','created_at','updated_at'];
    public function food():object
    {
        return $this->hasMany(Food::class);
    }
    public function type():object
    {
        return $this->belongsTo(Type::class);
    }
    public function location():MorphOne
    {
     return $this->morphOne(location::class,'location_able');

    }
    public function phone():MorphOne
    {
     return $this->morphOne(Phone::class,'phone_able');

    }
}
