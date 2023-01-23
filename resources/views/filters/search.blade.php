@include('sidecar::components.input', [
       'id' => $field->getFilterField(),
       'type' => 'text',
       'name' => "filters[{$field->getFilterField()}][]",
       'value' => $report->filters->filtersFor($field->getFilterField())->implode(" "),
])