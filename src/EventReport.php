<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventReport extends Model
{
	use SoftDeletes;

    protected $fillable = ['volunteer_id','title','description','location','image','image_file_name','emergency','village_id'];

    public function participants()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\EventPartisipant');
    }

    public function activities()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\EventActivity');
    }

    public function village()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Village','village_id','id');
    }

}
