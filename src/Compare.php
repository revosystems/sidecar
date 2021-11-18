<?php

namespace Revo\Sidecar;

class Compare
{
    protected Report $period1;
    protected Report $period2;
    protected $period1Results;
    protected $period2Results;

    protected $groupBy = 'tenantUser_id';
    protected $metric = 'total';

    public function __construct($report)
    {
        $this->period1 = $report;
        $this->period2 = clone $report;
        $this->setPeriod2Dates();
    }

    public function setPeriod2Dates()
    {
        $this->period2->filters->dates['opened'] = [
            'start' => '2020-01-01',
            'end'   => '2022-01-01'
        ];
    }

    public function calculate()
    {
        $this->period1Results = $this->period1->paginate();
        $this->period2Results = $this->period2->paginate();

//        dd($this->period1Results);
    }

}