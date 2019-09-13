<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventActivity extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function event()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\EventReport','event_report_id');
    }
}
