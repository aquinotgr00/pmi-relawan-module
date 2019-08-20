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

    public static function getCode()
    {
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'memberships'");
        $number     = $statement[0]->Auto_increment;
        $length     = 2;
        $obj        = new Membership;
        $code       = $obj->setLeadingZeroCode($number,$length);
        return $code;
    }

    public function getRecursive()
    {
        return DB::table(DB::raw('memberships AS m1'))
            ->join(DB::raw('memberships AS m2'), 'm1.parent_id', '=', 'm2.id')
            ->join(DB::raw('memberships AS m3'), 'm3.parent_id', '=', 'm2.id')
            ->get();
    }
}
