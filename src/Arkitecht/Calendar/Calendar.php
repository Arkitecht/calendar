<?php

namespace Arkitecht\Calendar;

use Carbon\Carbon;

class Calendar
{

    private $date;
    private $start_of_week;
    private $end_of_week;

    public function __construct($date = null, $startOfWeek = 0, $locale = 'en')
    {
        setlocale(LC_TIME, '');
        $this->setDate($date);
        $this->setStartOfweek($startOfWeek);
        $this->setLocale($locale);

    }

    public function setDate($date = null)
    {
        $this->date = $date ? Carbon::parse($date)->startOfDay() : Carbon::now()->startOfDay();

        return $this;
    }

    public function setLocale($locale = 'en')
    {
        setlocale(LC_TIME, $locale);
        $this->date->setLocale($locale);

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
        return $this->getDateCopy();
    }

    public function getFirstDay($view = 'month')
    {
        if ($view == 'month') {
            return $this->getDateCopy()
                ->startOfMonth()
                ->startOfWeek($this->start_of_week);
        }

        if ($view == 'week') {
            return $this->getDateCopy()
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
                ->copy()
                ->endOfMonth()
                ->endOfWeek($this->end_of_week);
        }

        if ($view == 'week') {
            return $this->date
                ->copy()
                ->endOfWeek($this->end_of_week);
        }

        if ($view == 'day') {
            return $this->date;
        }
    }

    public function getMonthDays()
    {
        $startOfMonth = $this->date
            ->copy()
            ->startOfMonth();

        $day = $this->getFirstDay();

        $endOfMonth = $this->date
            ->copy()
            ->endOfMonth();

        $endDay = $this->getLastDay();

        $dates = [];

        do {
            $dates[] = new Date($day, ($day->lt($startOfMonth) || $day->gt($endOfMonth)));
            $day = $day->copy()->addDay();
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
            return ucwords($this->date->formatLocalized('%b'));
        }

        return $this->date->formatLocalized('%B');
    }

    public function getDayNames($short = false)
    {
        $start = $this->startOfWeek();
        $names = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $start->copy()->addDay($i);
            $names[] = ($short) ? $day->formatLocalized('%a') : $day->formatLocalized('%A');
        }

        return $names;
    }

    private function startOfWeek()
    {
        return $this->getDateCopy()->startOfWeek($this->start_of_week);
    }

    private function getDateCopy()
    {
        $dateCopy = $this->date->copy();
        $dateCopy->setWeekStartsAt($this->start_of_week);
        $dateCopy->setWeekEndsAt($this->end_of_week);

        return $dateCopy;
    }

    public function __get($name)
    {
        return $this->date->{$name};
    }
}
