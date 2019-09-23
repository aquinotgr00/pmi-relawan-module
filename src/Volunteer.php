<?php

namespace BajakLautMalaka\PmiRelawan;

use BajakLautMalaka\PmiRelawan\Jobs\SendRegistrationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Volunteer extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'phone', 'image', 'dob', 'birthplace', 'gender', 'religion', 'blood_type', 'unit_id',
    'address', 'province', 'city', 'subdistrict', 'subdivision', 'postal_code', 'membership', 'user_id', 'verified'
    ];
    
    //protected $appends = ['name','image_url', 'age', 'achievements', 'assignments', 'trainings'];
    protected $appends = ['name','image_url', 'age'];
    protected $hidden = ['qualifications'];

    /**
     * Scope a query to only include verified volunteers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', 1);
    }

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function getNameAttribute()
    {
        return $this->user->name;
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->dob)->age;
    }
    
    public function achievements()
    {
        return $this->hasMany('BajakLautMalaka\PmiRelawan\Qualification');
    }
    
    public function assignments()
    {
        return $this->hasMany('BajakLautMalaka\PmiRelawan\Qualification');
    }
    
    public function trainings()
    {
        return $this->hasMany('BajakLautMalaka\PmiRelawan\Qualification');
    }

    public function unit()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\UnitVolunteer');
    }
    
    public function qualifications()
    {
        return $this->hasMany('BajakLautMalaka\PmiRelawan\Qualification');
    }

    public function participants()
    {
        return $this->hasMany('BajakLautMalaka\PmiRelawan\EventParticipant');
    }

    public function activities()
    {
        return $this->hasMany('BajakLautMalaka\PmiRelawan\EventActivity');
    }
    
    public function getImageUrlAttribute()
    {
        return asset(Storage::url($this->image)); 
    }
}
