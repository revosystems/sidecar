@php $filterValues = $report->filters->dates[$field->getFilterField()] @endphp
<input id="{{$field->getFilterField()}}_start" type="time" name="dates[{{$field->getFilterField()}}][start_time]"
       style="width: 148px"
       value="{{data_get($filterValues, 'start_time', '')}}">

<input id="{{$field->getFilterField()}}_end" type="time" name="dates[{{$field->getFilterField()}}][end_time]"
       style="width: 148px"
       value="{{data_get($filterValues, 'end_time', '')}}">

