<div class="flex space-x-2 items-center">
    @php $filterValues = $report->filters->dates[$field->getFilterField()] @endphp
    @include('sidecar::components.input', [
           'id' => "{$field->getFilterField()}_start",
           'type' => 'time',
           'name' => "dates[{$field->getFilterField()}][start_time]",
           'width' => '147px',
           'value' => data_get($filterValues, 'start_time', ''),
           'classes' => 'w-full',
    ])
    @include('sidecar::components.input', [
           'id' => "{$field->getFilterField()}_start",
           'type' => 'time',
           'name' => "dates[{$field->getFilterField()}][end_time]",
           'width' => '146px',
           'value' => data_get($filterValues, 'end_time', ''),
           'classes' => 'w-full',
    ])

</div>