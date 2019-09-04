<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Subdistrict extends Model
{
    protected $fillable = ['province_id','city_id','name','type'];

    protected $hidden = ['type','created_at','updated_at'];
    
    public function city()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\City');
    }

    public function villages()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\Village');
    }

    public static function getForFiltering(Request $request)
    {
    	$subdistrict = static::select(['id','name'])->get();
    	if ($request->has('c_id')) {
    		$subdistrict = static::select(['id','name'])->where('city_id',$request->c_id)->get();
    	}
    	return $subdistrict;
    }
}
