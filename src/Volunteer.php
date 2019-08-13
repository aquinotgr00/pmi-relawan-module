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
        'name', 'phone', 'image', 'dob', 'birthplace', 'gender', 'religion', 'blood_type', 
        'address', 'province', 'city', 'subdistrict', 'subdivision', 'postal_code', 'unit', 'membership', 'user_id'
    ];
    
    protected $appends = ['image_url'];

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }

    public static function getByType(Type $var = null)
    {
        # code...
    }

    public static function getBySubType(Type $var = null)
    {
        # code...
    }

    public static function getByCity(Type $var = null)
    {
        # code...
    }

    public static function getByProvince(Type $var = null)
    {
        # code...
    }

    public static function getByUnit(Type $var = null)
    {
        # code...
    }

    public static function getByKeyword(Type $var = null)
    {
        # code...
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
