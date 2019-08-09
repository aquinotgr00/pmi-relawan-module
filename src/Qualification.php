<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    protected $fillable = ['description', 'category'];
    
    public function volunteer()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Volunteer');
    }
}
