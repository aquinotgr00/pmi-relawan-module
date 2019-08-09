<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Volunteer extends Model
{
    use SoftDeletes;
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function qualifications()
    {
        return $this->hasMany('BajakLautMalaka\PmiRelawan\Qualification');
    }
}
