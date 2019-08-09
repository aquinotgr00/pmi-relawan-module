<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $fillable = ['province_id','city_id','subdistrict_id','name'];
    
    public function subdistrict()
    {
        return $this->belongsTo('BajakLautMalaka\PmiRelawan\Subdistrict');
    }
}
