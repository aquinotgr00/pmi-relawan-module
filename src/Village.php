<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $fillable = ['province_id','city_id','subdistrict_id','name'];
    
    protected $appends = ['city_id'];

    public function subdistrict()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Subdistrict');
    }

    public function getCityIdAttribute()
    {
    	return $this->subdistrict->city_id;
    }
}
