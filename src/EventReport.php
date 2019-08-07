<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventReport extends Model
{
	use SoftDeletes;

    protected $fillable = ['volunteer_id','title','description','type','location','image','image_file_name','emergency'];

    public function partisipants()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\EventPartisipant');
    }

    public function activities()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\EventActivity');
    }
}
