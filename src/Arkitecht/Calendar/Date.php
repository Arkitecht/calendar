<?php

namespace Arkitecht\Calendar;

use Carbon\Carbon;

class Date
{
    private $date;
    private $disabled;

    public function __construct(Carbon $date, $disabled = false)
    {
        $this->date = $date->startOfDay();
        $this->disabled = $disabled;
    }

    public function isToday()
    {
        return $this->date->eq(Carbon::now()->startOfDay());
    }

    public function isDisabled()
    {
        return $this->disabled;
    }

    public function toDateString()
    {
        return $this->date->toDateString();
    }

    public function __get($name)
    {
        return $this->date->{$name};
    }
}
