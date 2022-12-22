<?php

namespace Tests;

use Carbon\Carbon;
use Revo\Sidecar\ExportFields\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    /** @test */
    public function can_get_opening_hour_with_utc()
    {
        Carbon::setTestNow(Carbon::parse('2022-12-21'));
        
        Date::$openingTime = '05:00:00';
        Date::$timezone = 'Europe/Madrid';
        $this->assertEquals('04:00:00', Date::getUtcOpeningTime());
    }
}