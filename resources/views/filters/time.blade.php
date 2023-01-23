@php $filterValues = $report->filters->dates[$field->getFilterField()] @endphp
@include('sidecar::components.input', [
       'id' => "{$field->getFilterField()}_start",
       'type' => 'time',
       'name' => "dates[{$field->getFilterField()}][start_time]",
       'width' => '148px',
       'value' => data_get($filterValues, 'start_time', ''),
       'classes' => 'mr-1',
])
@include('sidecar::components.input', [
       'id' => "{$field->getFilterField()}_start",
       'type' => 'time',
       'name' => "dates[{$field->getFilterField()}][end_time]",
       'width' => '148px',
       'value' => data_get($filterValues, 'end_time', ''),
])

