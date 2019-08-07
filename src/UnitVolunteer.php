<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class UnitVolunteer extends Model
{
    protected $guarded = [];

    public function subtype()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\SubMemberType');
    }

    public function city()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\City');
    }
}
