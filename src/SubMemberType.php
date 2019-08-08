<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class SubMemberType extends Model
{
    protected $guarded = [];

    public function type()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\MemberType','member_type_id','id');
    }

    public function units()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\UnitVolunteer','submember_type_id','id');
    }
}
