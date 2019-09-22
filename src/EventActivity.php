<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class EventActivity extends Model
{
    use SoftDeletes;

    protected $appends = ['media_url'];
    
    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\EventReport','event_report_id');
    }

    public function volunteer()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Volunteer');
    }

    public function getMediaUrlAttribute()
    {
        return asset(Storage::url($this->comment_attachment)); 
    }
}
