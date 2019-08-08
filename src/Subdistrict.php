<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $fillable = ['province_id','city_id','name','type'];
    
    public function city()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\City');
    }

    public function urbanVillages()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\UrbanVillage');
    }
}
