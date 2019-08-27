<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class EventPartisipant extends Model
{
	protected $table = 'event_participants';
    protected $fillable = ['volunteer_id','event_report_id','admin_id'];

    public function event()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\EventReport','event_report_id','id');
    }
}
