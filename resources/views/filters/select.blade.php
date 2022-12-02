<select id="{{$field->getFilterField()}}" name="filters[{{$field->getFilterField()}}][]" multiple style="width: 300px">
    <option value="">--</option>
    @foreach($field->filterOptions($report->filters) as $key => $value)
        <option value="{{$key}}" @if($report->filters->isFilteringBy($field->getFilterField(), $key)) selected @endif>{{$value}}</option>
    @endforeach
</select>

@push(config('sidecar.scripts-stack'))
<script>
    @if($field->filterSearchable)
        SidecarSelector.fetchSelector(document.getElementById('{{$field->getFilterField()}}'), '', '{!! $field->searchableRoute() !!}')
    @else
        SidecarSelector.selector(document.getElementById('{{$field->getFilterField()}}'), '')
    @endif
    </script>
@endpush
