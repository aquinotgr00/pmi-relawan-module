<?php

namespace BajakLautMalaka\PmiRelawan;

use BajakLautMalaka\PmiRelawan\Scopes\OrderByLatestScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class EventReport extends Model
{
	use SoftDeletes;

    protected $fillable = ['volunteer_id','title','description','location','image','emergency','village_id'];

    protected $appends = ['image_url'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByLatestScope);
    }

    public function volunteer() {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Volunteer','volunteer_id');
    }

    public function admin() {
        return $this->belongsTo('BajakLautMalaka\PmiAdmin\Admin','admin_id');
    }

    public function participants()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\EventParticipant');
    }

    public function activities()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\EventActivity');
    }

    public function village()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Village','village_id','id');
    }

    public function getImageUrlAttribute()
    {
        return asset(Storage::url($this->image));
    }
}
