<input id="{{$field->getFilterField()}}" type="text" name="filters[{{$field->getFilterField()}}][]"
       style="width: 300px"
       value="{{$report->filters->filtersFor($field->getFilterField())->implode(" ")}}">