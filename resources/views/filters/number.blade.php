@php $filterValues = $report->filters->filtersFor($field->getFilterField()) @endphp
<x-sidecar::select :id="$field->getFilterField().'-select'" :name="'filters['.$field->getFilterField().'][operand]'" :width="'55px'">
    @foreach($field->filterOptions() as $operand => $name)
        <option value="{{$operand}}" @if($operand == data_get($filterValues, 'operand', '')) selected @endif> {{ $name }}</option>
    @endforeach
</x-sidecar::select>
@include('sidecar::components.input', [
    'id' => $field->getFilterField(),
    'type' => 'number',
    'name' => "filters[{$field->getFilterField()}][value]",
    'width' => '240px',
    'value' => data_get($filterValues, 'value', ''),
])