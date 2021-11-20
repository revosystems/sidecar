<?php

namespace Revo\Sidecar\Exporters;
use \Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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
        $dates = collect($this->report->filters->dates)->first();
        return $this->makeResponse(implode("_", [$this->report->getTitle(), $dates['start'],  $dates['end']]));
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

    public function save($filename) : string
    {
        Storage::put($filename, $this->export());
        return Storage::url($filename);
    }
}