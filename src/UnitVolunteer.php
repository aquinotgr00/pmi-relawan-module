<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class UnitVolunteer extends Model
{
    protected $guarded = [];

    public function subtype()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\SubMemberType','submember_type_id','id');
    }

    public function city()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\City','city_id','id');
    }
}
