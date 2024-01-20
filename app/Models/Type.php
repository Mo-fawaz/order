<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $fillable = ['uuid', 'name'];
    protected $hidden = ['id','created_at','updated_at'];

    public function restaurant():object
    {
        return $this->hasMany(Restaurant::class);
    }
}
