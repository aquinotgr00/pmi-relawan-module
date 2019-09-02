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
    
    protected $appends = ['name','image_url', 'age', 'achievements', 'assignments', 'trainings'];

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
    
    public function getAchievementsAttribute()
    {
        return $this->qualifications->where('category', 1)->values();
    }
    
    public function getAssignmentsAttribute()
    {
        return $this->qualifications->where('category', 2)->values();
    }
    
    public function getTrainingsAttribute()
    {
        return $this->qualifications->where('category', 3)->values();
    }

    public function unit()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\UnitVolunteer');
    }
    
    public function qualifications()
    {
        return $this->hasMany('BajakLautMalaka\PmiRelawan\Qualification');
    }
    
    public function getImageUrlAttribute()
    {
        return asset(Storage::url($this->image));
    }

    public function sendRegistrationStatus($email, $data)
    {
        dispatch(new SendRegistrationStatus($email, $data));
    }
}
