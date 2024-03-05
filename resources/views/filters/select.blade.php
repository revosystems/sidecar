<x-ui::forms.select id="{{$field->getFilterField()}}" name="filters[{{$field->getFilterField()}}][]" multiple>
    <option value="">--</option>
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
</x-ui::forms.select>

@push(config('sidecar.scripts-stack'))
<script>
    window.addEventListener('load', () => {
        @if($field->filterSearchable)
            SidecarSelector.fetchSelector(document.getElementById('{{$field->getFilterField()}}'), '', '{!! $field->searchableRoute() !!}')
        @else
            SidecarSelector.selector(document.getElementById('{{$field->getFilterField()}}'), '')
        @endif
    })
</script>
@endpush
