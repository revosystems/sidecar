<?php

namespace Revo\Sidecar\Exporters;
use \Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class CSVExporter extends BaseExporter
{

    public function export() : string {
        return view('sidecar::widgets.csvTable',[
            "fields" => $this->getExportableFields()->filter(fn($field) => in_array('csv', $field->exporters)),
            "rows" => $this->data,
        ])->render();
    }

    protected function getType()
    {
        return "csv";
    }

    public function download()
    {
        return $this->makeResponse($this->title());
    }

    public function title(): string
    {
        $dates = collect($this->report->filters->dates)->first();
        return implode("_", [$this->report->getTitle(), $dates['start'], $dates['end']]);
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
        return Storage::temporaryUrl($filename, now()->addMinutes(30));
    }
}
