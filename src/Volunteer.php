<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Volunteer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'phone', 'image', 'dob', 'birthplace', 'gender', 'religion', 'blood_type', 'unit_id',
        'address', 'province', 'city', 'subdistrict', 'subdivision', 'postal_code', 'membership', 'user_id', 'verified'
    ];
    
    protected $appends = ['name','image_url', 'age'];

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
        return asset((Storage::url($this->image)));
    }
}
