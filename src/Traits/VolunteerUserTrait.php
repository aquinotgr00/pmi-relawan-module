<?php

namespace BajakLautMalaka\PmiRelawan\Traits;

trait VolunteerUserTrait
{
    /**
     * User hasOne relation with Volunteer.
     *
     * @return mixed
     */
    public function volunteer()
    {
        return $this->hasOne('\BajakLautMalaka\PmiRelawan\Volunteer');
    }
}