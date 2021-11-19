<?php

namespace Revo\Sidecar\Exporters;
use \Illuminate\Support\Facades\Response;

class CSVExporter extends BaseExporter
{

    public function export() : string {
        return view('sidecar::widgets.csvTable',[
            "fields" => $this->getFields(),
            "rows" => $this->data,
        ])->render();
    }

    protected function getType()
    {
        return "csv";
    }

    public function download()
    {
        return $this->makeResponse($this->report->getTitle());
    }

    private function makeResponse($title)
    {
        return Response::make(rtrim($this->export(), "\n"), 200, $this->getHeaders($title));
    }

    private function getHeaders($title)
    {
        return [
            'Content-Type'        => 'application/csv; charset=UTF-8',
            'Content-Encoding'    => 'UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $title . '.csv"',  // Safari filename must be between commas
        ];
    }
}