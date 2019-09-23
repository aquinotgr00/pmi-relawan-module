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

    public function admin()
    {
        return $this->belongsTo('BajakLautMalaka\PmiAdmin\Admin');
    }

    public function volunteer()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Volunteer');
    }

    public function user()
    {
        return $this->hasOneThrough('App\User','BajakLautMalaka\PmiRelawan\Volunteer','id','id','volunteer_id','user_id');
    }

    public function getMediaUrlAttribute()
    {
        return asset(Storage::url($this->comment_attachment)); 
    }
}
