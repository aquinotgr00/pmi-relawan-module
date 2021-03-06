<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;
use BajakLautMalaka\PmiRelawan\Traits\RelawanTrait;
use DB;

class Membership extends Model
{
    use RelawanTrait;

	protected $guarded = [];
    

    public function parentMember() {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Membership', 'parent_id','id');
    }
    
    public function subMember() {
        return $this->hasMany('BajakLautMalaka\PmiRelawan\Membership', 'parent_id', 'id');
    }

    public function units()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\UnitVolunteer', 'membership_id', 'id');
    }
}
