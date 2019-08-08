<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['province_id','name','type','postal_code'];

    public function province()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Province');
    }

    public function subdistricts()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\Subdistrict');
    }
}
