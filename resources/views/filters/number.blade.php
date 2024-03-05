<div class="flex items-center space-x-2">
    @php $filterValues = $report->filters->filtersFor($field->getFilterField()) @endphp
    <x-sidecar::select :id="$field->getFilterField().'-select'" :name="'filters['.$field->getFilterField().'][operand]'" class="w-20">
        @foreach($field->filterOptions() as $operand => $name)
            <option value="{{$operand}}" @if($operand == data_get($filterValues, 'operand', '')) selected @endif> {{ $name }}</option>
        @endforeach
    </x-sidecar::select>

    @include('sidecar::components.input', [
        'id' => $field->getFilterField(),
        'type' => 'number',
        'name' => "filters[{$field->getFilterField()}][value]",
        'width' => 'w-f',
        'value' => data_get($filterValues, 'value', ''),
        'classes' => 'ml-1'
    ])

</div>