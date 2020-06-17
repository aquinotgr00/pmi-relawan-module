<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name'];

    protected $hidden = ['created_at','updated_at'];

    public function cities()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\City');
    }
}
