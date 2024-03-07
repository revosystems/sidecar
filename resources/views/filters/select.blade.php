
{{ $field->filterSearchable ? $field->searchableRoute() : '--' }}
<x-ui::forms.multiple-select
    id="{{$field->getFilterField()}}"
    name="filters[{{$field->getFilterField()}}][]"
    :url="($field->filterSearchable ? $field->searchableRoute() : null)"
>
    @if (\Illuminate\Support\Arr::dimensions($field->filterOptions($report->filters)) > 1)
        @foreach($field->filterOptions($report->filters) as $category => $categories)
            <optgroup label="{{ $category }}">
                @foreach($categories as $key => $value)
                    <option value="{{ $key }}" @if($report->filters->isFilteringBy($field->getFilterField(), $key)) selected @endif>{{$value}}</option>
                @endforeach
            </optgroup>
        @endforeach
    @else
        @foreach($field->filterOptions($report->filters) as $key => $value)
            <option value="{{$key}}" @if($report->filters->isFilteringBy($field->getFilterField(), $key)) selected @endif>{{$value}}</option>
        @endforeach
    @endif
</x-ui::forms.multiple-select>
