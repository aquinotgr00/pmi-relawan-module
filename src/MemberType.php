<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class MemberType extends Model
{
    protected $guarded = [];

    public function subtypes()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\SubMemberType');
    }
}
