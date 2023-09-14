<?php

use Arkitecht\Calendar\Date;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    /** @test */
    function can_set_is_today()
    {
        $date = new Date(Carbon::now()->subDay());
        $this->assertFalse($date->isToday());

        $date = new Date(Carbon::now());
        $this->assertTrue($date->isToday());
    }

    /** @test */
    function can_get_date_as_date_string()
    {
        $date = new Date(Carbon::parse('2019-01-01'));
        $this->assertEquals('2019-01-01', $date->toDateString());
    }

    /** @test */
    function can_get_date_properties()
    {
        $date = new Date(Carbon::parse('2019-01-15'));
        $this->assertEquals(1, $date->month);
        $this->assertEquals(15, $date->day);
        $this->assertEquals(2019, $date->year);
    }
}
