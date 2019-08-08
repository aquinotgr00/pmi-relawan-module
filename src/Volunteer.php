<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Volunteer extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'birth_place', 'dob', 'gender', 'religion',
    'phone', 'blood', 'province', 'district', 'sub_district', 'awards', 'assignments',
    'trainings', 'image', 'image_file', 'unit', 'type', 'sub_type'
    ];

    /**
     * volunteer belong to user.
     * 
     * @return belongsTo
     */
    public function getUser()
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
}