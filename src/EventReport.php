<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventReport extends Model
{
	use SoftDeletes;

    protected $fillable = ['volunteer_id','title','description','type','location','image','image_file_name','emergency','village_id'];

    protected $appends = ['members'];

    public function partisipants()
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

    public function getMembersAttribute()
    {
        $members = [];
        if ($this->partisipants->count() > 0) {
            foreach ($this->partisipants->where('request_join',1) as $key => $value) {
                $members[] = $value;
            }
        }
        return $members;
    }
}
