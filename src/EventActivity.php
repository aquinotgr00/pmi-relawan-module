<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class EventActivity extends Model
{
    protected $guarded = [];

    public function event()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\EventReport');
    }
}
