<?php

namespace Revo\Sidecar\Enums;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

enum DateRange : string {

    case today          = 'today';
    case yesterday      = 'yesterday';
    case last7days      = 'last7days';
    case last30days     = 'last30days';
    case last90days     = 'last90days';
    case lastMonth      = 'lastMonth';
    case lastYear       = 'lastYear';
    case weekToDate     = 'weekToDate';
    case monthToDate    = 'monthToDate';
    case quarterToDate  = 'quarterToDate';
    case yearToDate     = 'yearToDate';
    case quarter1       = 'quarter1';
    case quarter2       = 'quarter2';
    case quarter3       = 'quarter3';
    case quarter4       = 'quarter4';

    public function period() : CarbonPeriod {
        return match($this){
            self::yesterday     => CarbonPeriod::since(Carbon::yesterday()->startOfDay())->until(Carbon::yesterday()->startOfDay()),
            self::last7days     => CarbonPeriod::since(now()->subDays(7)->startOfDay())->until(now()->endOfDay()),
            self::last30days    => CarbonPeriod::since(now()->subDays(30)->startOfDay())->until(now()->endOfDay()),
            self::last90days    => CarbonPeriod::since(now()->subDays(90)->startOfDay())->until(now()->endOfDay()),
            self::lastMonth     => CarbonPeriod::since(now()->subMonth()->startOfMonth())->until(now()->subMonth()->endOfMonth()),
            self::lastYear      => CarbonPeriod::since(now()->subYear()->startOfYear())->until(now()->subYear()->endOfYear()),
            self::weekToDate    => CarbonPeriod::since(now()->startOfWeek())->until(now()->endOfDay()),
            self::monthToDate   => CarbonPeriod::since(now()->startOfMonth())->until(now()->endOfDay()),
            self::quarterToDate => CarbonPeriod::since(now()->startOfQuarter())->until(now()->endOfDay()),
            self::yearToDate    => CarbonPeriod::since(now()->startOfYear())->until(now()->endOfDay()),
            self::quarter1      => CarbonPeriod::since(now()->startOfYear())->until(now()->startOfYear()->addQuarter()),
            self::quarter2      => CarbonPeriod::since(now()->startOfYear()->addQuarters(1))->until(now()->startOfYear()->addQuarters(2)),
            self::quarter3      => CarbonPeriod::since(now()->startOfYear()->addQuarters(2))->until(now()->startOfYear()->addQuarters(3)),
            self::quarter4      => CarbonPeriod::since(now()->startOfYear()->addQuarters(3))->until(now()->startOfYear()->addQuarters(4)),
            default             => CarbonPeriod::since(now()->startOfDay())->until(now()->endOfDay()),
        };
    }
}
