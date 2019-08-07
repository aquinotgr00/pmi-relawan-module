<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class SubMemberType extends Model
{
    protected $guarded = [];

    public function type()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\MemberType');
    }
}
