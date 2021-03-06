<?php

namespace BajakLautMalaka\PmiRelawan;

use BajakLautMalaka\PmiRelawan\Scopes\OrderByLatestScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
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

    public function appUser() {
        return $this->hasOneThrough('App\User','BajakLautMalaka\PmiRelawan\Volunteer','id','id','volunteer_id','user_id');
    }

    public function admin() {
        return $this->belongsTo('BajakLautMalaka\PmiAdmin\Admin','admin_id');
    }

    public function participants()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\EventParticipant');
    }

    public function joinStatus()
    {
        $user = Auth::user()->load('volunteer');
        return $this->hasOne('BajakLautMalaka\PmiRelawan\EventParticipant')->where('volunteer_id',$user->volunteer->id);
    }

    public function activities()
    {
    	return $this->hasMany('BajakLautMalaka\PmiRelawan\EventActivity');
    }

    public function village()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Village','village_id','id');
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', 1);
    }

    public function getImageUrlAttribute()
    {
        return asset(Storage::url($this->image));
    }
}
