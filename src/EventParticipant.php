<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
	protected $fillable = ['volunteer_id','event_report_id','admin_id'];

    protected $hidden = ['created_at','updated_at'];

    public function event()
    {
    	return $this->belongsTo('BajakLautMalaka\PmiRelawan\EventReport','event_report_id','id');
    }
}
