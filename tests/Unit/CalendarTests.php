<?php

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CalendarTests extends TestCase
{
    /** @test */
    function can_get_a_calendar_class()
    {
        $calendar = new Arkitecht\Calendar\Calendar();
        $this->assertNotNull($calendar);
    }

    /** @test */
    function can_set_initial_date_on_calendar_in_constructor()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-01-01');

        $this->assertEquals(Carbon::parse('2019-01-01')->startOfDay(), $calendar->getDate());
    }

    /** @test */
    function can_set_initial_date_on_calendar_with_method()
    {
        $calendar = new Arkitecht\Calendar\Calendar();
        $this->assertEquals(Carbon::now()->startOfDay(), $calendar->getDate());


        $calendar->setDate('2019-01-01');
        $this->assertEquals(Carbon::parse('2019-01-01')->startOfDay(), $calendar->getDate());
    }

    /** @test */
    function can_default_to_current_date_in_constructor()
    {
        $calendar = new Arkitecht\Calendar\Calendar();

        $this->assertEquals(Carbon::now()->startOfDay(), $calendar->getDate());
    }

    /** @test */
    function can_default_to_current_date_with_method()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-01-01');
        $this->assertEquals(Carbon::parse('2019-01-01')->startOfDay(), $calendar->getDate());

        $calendar->setDate();
        $this->assertEquals(Carbon::now()->startOfDay(), $calendar->getDate());
    }

    /** @test */
    function can_set_start_date_in_constructor()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-01-01', 2);

        $this->assertEquals(2, $calendar->getStartOfweek());
    }

    /** @test */
    function can_set_of_week_with_method()
    {
        $calendar = new Arkitecht\Calendar\Calendar();
        $calendar->setStartOfweek(2);

        $this->assertEquals(2, $calendar->getStartOfweek());
    }

    /** @test */
    function cant_set_start_date_with_invalid_high_value()
    {
        $calendar = new Arkitecht\Calendar\Calendar();
        try {
            $calendar->setStartOfweek(9);
        } catch (Exception $e) {
            $this->assertEquals('Start day must be a value between 0 (Sunday) and 6 (Saturday)', $e->getMessage());

            return;
        }

        $this->fail('Failed to throw exception on invalid start day');
    }

    /** @test */
    function cant_set_start_date_with_invalid_low_value()
    {
        $calendar = new Arkitecht\Calendar\Calendar();
        try {
            $calendar->setStartOfweek(-2);
        } catch (Exception $e) {
            $this->assertEquals('Start day must be a value between 0 (Sunday) and 6 (Saturday)', $e->getMessage());

            return;
        }

        $this->fail('Failed to throw exception on invalid start day');
    }

    /** @test */
    function does_set_end_of_week_on_set_start_of_week()
    {
        $calendar = new Arkitecht\Calendar\Calendar();


        //Sun - Sat
        $calendar->setStartOfweek();
        $this->assertEquals(0, $calendar->getStartOfweek());
        $this->assertEquals(6, $calendar->getEndOfweek());

        $calendar->setStartOfweek(0);
        $this->assertEquals(0, $calendar->getStartOfweek());
        $this->assertEquals(6, $calendar->getEndOfweek());

        //Mon - Sun
        $calendar->setStartOfweek(1);
        $this->assertEquals(1, $calendar->getStartOfweek());
        $this->assertEquals(0, $calendar->getEndOfweek());

        //Tues - Mon
        $calendar->setStartOfweek(2);
        $this->assertEquals(2, $calendar->getStartOfweek());
        $this->assertEquals(1, $calendar->getEndOfweek());
    }

    /** @test */
    function can_get_first_day_of_month_view()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01');
        $firstDay = $calendar->getFirstDay();

        $this->assertEquals('2019-09-29', $firstDay->toDateString());
    }

    /** @test */
    function can_get_first_day_of_week_view()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01');
        $firstDay = $calendar->getFirstDay('week');

        $this->assertEquals('2019-09-29', $firstDay->toDateString());
    }


    /** @test */
    function can_get_last_day_of_month_view()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01');
        $lastDay = $calendar->getLastDay();

        $this->assertEquals('2019-11-02', $lastDay->toDateString());
    }

    /** @test */
    function can_get_last_day_of_week_view()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01');
        $lastDay = $calendar->getLastDay('week');

        $this->assertEquals('2019-10-05', $lastDay->toDateString());
    }

    /** @test */
    function can_get_month_days()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01');
        $days = $calendar->getMonthDays();

        $this->assertEquals('2019-09-29', $days[0]->toDateString());
        $this->assertTrue($days[0]->isDisabled());

        $this->assertEquals('2019-10-01', $days[2]->toDateString());
        $this->assertFalse($days[2]->isDisabled());

        $this->assertEquals('2019-11-02', $days[ count($days) - 1 ]->toDateString());
        $this->assertTrue($days[ count($days) - 1 ]->isDisabled());
    }

    /** @test */
    function can_get_month_week_days()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01');
        $weeks = $calendar->getMonthDaysByWeek();

        $this->assertEquals('2019-09-29', $weeks[0][0]->toDateString());
        $this->assertEquals('2019-10-05', $weeks[0][6]->toDateString());
        $this->assertTrue($weeks[0][0]->isDisabled());
        $this->assertFalse($weeks[0][6]->isDisabled());

        $this->assertEquals('2019-10-06', $weeks[1][0]->toDateString());
        $this->assertEquals('2019-10-12', $weeks[1][6]->toDateString());

        $this->assertEquals('2019-10-13', $weeks[2][0]->toDateString());
        $this->assertEquals('2019-10-19', $weeks[2][6]->toDateString());

        $this->assertEquals('2019-10-20', $weeks[3][0]->toDateString());
        $this->assertEquals('2019-10-26', $weeks[3][6]->toDateString());

        $this->assertEquals('2019-10-27', $weeks[4][0]->toDateString());
        $this->assertEquals('2019-11-02', $weeks[4][6]->toDateString());
        $this->assertFalse($weeks[4][0]->isDisabled());
        $this->assertTrue($weeks[4][6]->isDisabled());
    }

    /** @test */
    function can_get_month_week_days_with_alt_start()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01', 1);
        $weeks = $calendar->getMonthDaysByWeek();

        $this->assertEquals('2019-09-30', $weeks[0][0]->toDateString());
        $this->assertEquals('2019-10-06', $weeks[0][6]->toDateString());
        $this->assertTrue($weeks[0][0]->isDisabled());
        $this->assertFalse($weeks[0][6]->isDisabled());

        $this->assertEquals('2019-10-07', $weeks[1][0]->toDateString());
        $this->assertEquals('2019-10-13', $weeks[1][6]->toDateString());

        $this->assertEquals('2019-10-14', $weeks[2][0]->toDateString());
        $this->assertEquals('2019-10-20', $weeks[2][6]->toDateString());

        $this->assertEquals('2019-10-21', $weeks[3][0]->toDateString());
        $this->assertEquals('2019-10-27', $weeks[3][6]->toDateString());

        $this->assertEquals('2019-10-28', $weeks[4][0]->toDateString());
        $this->assertEquals('2019-11-03', $weeks[4][6]->toDateString());
        $this->assertFalse($weeks[4][0]->isDisabled());
        $this->assertTrue($weeks[4][6]->isDisabled());
    }

    /** @test */
    function can_get_month_name()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01', 1);
        $this->assertEquals('October', $calendar->getMonthName());
    }

    /** @test */
    function can_set_locale()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01', 1, 'es');
        $this->assertEquals('octubre', $calendar->getMonthName());
    }

    /** @test */
    function can_get_short_month_name()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01', 1);
        $this->assertEquals('Oct', $calendar->getMonthName(true));
    }

    /** @test */
    function can_get_day_names()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01');
        $names = $calendar->getDayNames();

        $this->assertEquals(7, count($names));
        $this->assertEquals('Sunday', $names[0]);
        $this->assertEquals('Saturday', $names[6]);
    }

    /** @test */
    function can_get_day_name_alt_start_of_week()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01', 2);
        $names = $calendar->getDayNames();

        $this->assertEquals(7, count($names));
        $this->assertEquals('Tuesday', $names[0]);
        $this->assertEquals('Monday', $names[6]);
    }

    /** @test */
    function can_get_short_day_names()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01');
        $names = $calendar->getDayNames(true);

        $this->assertEquals(7, count($names));
        $this->assertEquals('Sun', $names[0]);
        $this->assertEquals('Sat', $names[6]);
    }

    /** @test */
    function can_get_day_names_by_locale()
    {
        $calendar = new Arkitecht\Calendar\Calendar('2019-10-01', 0, 'es');
        $names = $calendar->getDayNames();

        $this->assertEquals(7, count($names));
        $this->assertEquals('domingo', $names[0]);
        $this->assertEquals('sábado', $names[6]);
    }


}
