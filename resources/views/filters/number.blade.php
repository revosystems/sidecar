@php $filterValues = $report->filters->filtersFor($field->getFilterField()) @endphp
<select id="{{$field->getFilterField()}}-select" name="filters[{{$field->getFilterField()}}][operand]"
        style="width: 55px; padding-top: 6.5px;">
    @foreach($field->filterOptions() as $operand => $name)
        <option value="{{$operand}}" @if($operand == data_get($filterValues, 'operand', '')) selected @endif> {{ $name }}</option>
    @endforeach
</select>
<input id="{{$field->getFilterField()}}" type="number" name="filters[{{$field->getFilterField()}}][value]"
       style="width: 240px"
       value="{{data_get($filterValues, 'value', '')}}">