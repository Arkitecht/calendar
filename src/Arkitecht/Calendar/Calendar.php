<?php

namespace Arkitecht\Calendar;

use Carbon\Carbon;

class Calendar
{

    private $date;
    private $start_of_week;
    private $end_of_week;

    public function __construct($date = null, $startOfWeek = 0, $locale = 'us')
    {
        $this->setDate($date);
        $this->setStartOfweek($startOfWeek);
        $this->setLocale($locale);

    }

    public function setDate($date = null)
    {
        $this->date = $date ? Carbon::parse($date)->startOfDay() : Carbon::now()->startOfDay();

        return $this;
    }

    public function setLocale($locale = 'us')
    {
        $this->date->locale($locale);

        return $this;
    }

    /**
     * @param $startDay
     *
     * @throws \Exception
     */
    public function setStartOfweek($startOfWeek = 0)
    {
        if ($startOfWeek > 6 || $startOfWeek < 0) {
            throw new \Exception('Start day must be a value between 0 (Sunday) and 6 (Saturday)');
        }
        $this->start_of_week = $startOfWeek;
        $this->end_of_week = ($startOfWeek == 0) ? 6 : $startOfWeek - 1;

        return $this;
    }

    public function getStartOfWeek()
    {
        return $this->start_of_week;
    }

    public function getEndOfWeek()
    {
        return $this->end_of_week;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getFirstDay($view = 'month')
    {
        if ($view == 'month') {
            return $this->date
                ->clone()
                ->startOfMonth()
                ->startOfWeek($this->start_of_week);
        }

        if ($view == 'week') {
            return $this->date
                ->clone()
                ->startOfWeek($this->start_of_week);
        }

        if ($view == 'day') {
            return $this->date;
        }
    }

    public function getLastDay($view = 'month')
    {
        if ($view == 'month') {
            return $this->date
                ->clone()
                ->endOfMonth()
                ->endOfWeek($this->end_of_week);
        }

        if ($view == 'week') {
            return $this->date
                ->clone()
                ->endOfWeek($this->end_of_week);
        }

        if ($view == 'day') {
            return $this->date;
        }
    }

    public function getMonthDays()
    {
        $startOfMonth = $this->date
            ->clone()
            ->startOfMonth();

        $day = $this->getFirstDay();

        $endOfMonth = $this->date
            ->clone()
            ->endOfMonth();

        $endDay = $this->getLastDay();

        $dates = [];

        do {
            $dates[] = new Date($day, ($day->lt($startOfMonth) || $day->gt($endOfMonth)));
            $day = $day->clone()->addDay();
        } while ($day->lte($endDay));

        return $dates;
    }

    public function getMonthDaysByWeek()
    {
        return array_chunk($this->getMonthDays(), 7);
    }

    public function getMonthName($short = false)
    {
        if ($short) {
            return $this->date->shortMonthName;
        }

        return $this->date->monthName;
    }

    public function getDayNames($short = false)
    {
        $start = $this->startOfWeek();
        $names = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $start->clone()->addDay($i);
            $names[] = ($short) ? $day->shortDayName : $day->dayName;
        }

        return $names;
    }

    private function startOfWeek()
    {
        return $this->date->clone()->startOfWeek($this->start_of_week);
    }

    public function __get($name)
    {
        return $this->date->{$name};
    }
}
