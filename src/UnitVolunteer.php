<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class UnitVolunteer extends Model
{
    protected $guarded = [];

    public function membership()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\Membership','membership_id','id');
    }

    public function city()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\City','city_id','id');
    }

    public function subMembership()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\Membership','sub_member_id','id')->where('parent_id','<>',NULL);
    }
}
