<?php

namespace Revo\Sidecar\Filters;

use Carbon\CarbonPeriod;
use Carbon\Carbon;

class DateHelpers
{
    public static function availableRanges() : array {
        return [
            "today"         => static::periodFor("today"),
            "yesterday"     => static::periodFor("yesterday"),
            "last7days"     => static::periodFor("last7days"),
            "last30days"    => static::periodFor("last30days"),
            "last90days"    => static::periodFor("last90days"),
            "lastMonth"     => static::periodFor("lastMonth"),
            "lastYear"      => static::periodFor("lastYear"),
            "weekToDate"    => static::periodFor("weekToDate"),
            "monthToDate"   => static::periodFor("monthToDate"),
            "quarterToDate" => static::periodFor("quarterToDate"),
            "yearToDate"    => static::periodFor("yearToDate"),
            "1quarter"      => static::periodFor("1quarter"),
            "2quarter"      => static::periodFor("2quarter"),
            "3quarter"      => static::periodFor("3quarter"),
            "4quarter"      => static::periodFor("4quarter"),
        ];
    }

    public static function periodFor($range) : CarbonPeriod {
        if ($range == 'yesterday') {
            return CarbonPeriod::since(Carbon::yesterday()->startOfDay())->until(Carbon::yesterday()->startOfDay());
        }
        if ($range == 'last7days') {
            return CarbonPeriod::since(now()->subDays(7)->startOfDay())->until(now()->endOfDay());
        }
        if ($range == 'last30days') {
            return CarbonPeriod::since(now()->subDays(30)->startOfDay())->until(now()->endOfDay());
        }
        if ($range == 'last90days') {
            return CarbonPeriod::since(now()->subDays(90)->startOfDay())->until(now()->endOfDay());
        }
        if ($range == 'lastMonth') {
            return CarbonPeriod::since(now()->subMonth()->startOfMonth())->until(now()->subMonth()->endOfMonth());
        }
        if($range == 'lastYear') {
            return CarbonPeriod::since(now()->subYear()->startOfYear())->until(now()->subYear()->endOfYear());
        }
        if($range == 'weekToDate') {
            return CarbonPeriod::since(now()->startOfWeek())->until(now()->endOfDay());
        }
        if($range == 'monthToDate') {
            return CarbonPeriod::since(now()->startOfMonth())->until(now()->endOfDay());
        }
        if($range == 'quarterToDate') {
            return CarbonPeriod::since(now()->startOfQuarter())->until(now()->endOfDay());
        }
        if($range == 'yearToDate') {
            return CarbonPeriod::since(now()->startOfYear())->until(now()->endOfDay());
        }
        if($range == '1quarter') {
            return CarbonPeriod::since(now()->startOfYear())->until(now()->endOfDay());
        }
        if($range == '2quarter') {
            return CarbonPeriod::since(now()->startOfYear())->until(now()->endOfDay());
        }
        if($range == '3quarter') {
            return CarbonPeriod::since(now()->startOfYear())->until(now()->endOfDay());
        }
        if($range == '4quarter') {
            return CarbonPeriod::since(now()->startOfYear())->until(now()->endOfDay());
        }
        return CarbonPeriod::since(now()->startOfDay())->until(now()->endOfDay());
    }
}