<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;



class Province extends Model
{
    protected $fillable = ['name'];

    public function cities()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\City');
    }
}
