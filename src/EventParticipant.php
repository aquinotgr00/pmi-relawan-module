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

    public function volunteer()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Volunteer');
    }

    public function activities()
    {
        return $this->hasManyThrough('BajakLautMalaka\PmiRelawan\EventActivity', 'BajakLautMalaka\PmiRelawan\EventReport','id','event_report_id','event_report_id')->latest();
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', 1);
    }
}
