<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['province_id','name','type','postal_code'];

    protected $hidden = ['created_at','updated_at'];

    public function province()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Province');
    }

    public function subdistricts()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\Subdistrict');
    }

    public function units()
    {
      return $this->hasMany('BajakLautMalaka\PmiRelawan\UnitVolunteer');
    }
}
