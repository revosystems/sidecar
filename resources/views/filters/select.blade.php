<div class="inline">
    @if($field->getIcon())
        <i class="fa fa-{{$field->getIcon()}} fa-fw"></i>
    @else
        {{ $field->getTitle() }}
    @endif

    @if ($field instanceof \Revo\Sidecar\ExportFields\Text)
        <input id="{{$field->getFilterField()}}" type="text" name="filters[{{$field->getFilterField()}}][]"
               style="width: 300px"
               value="{{$report->filters->filtersFor($field->getFilterField())->implode(" ")}}">
    @else
        <select id="{{$field->getFilterField()}}" name="filters[{{$field->getFilterField()}}][]" multiple style="width: 300px">
            <option value="">--</option>
            @foreach($field->filterOptions() as $key => $value)
                <option value="{{$key}}" @if($report->filters->isFilteringBy($field->getFilterField(), $key)) selected @endif>{{$value}}</option>
            @endforeach
        </select>
    @endif
</div>
@if($field->filterSearchable)
        @push(config('sidecar.scripts-stack'))
            <script>
                new RVAjaxSelect2('{{ $field->searchableRoute()  }}').show('#{{$field->getFilterField()}}');
            </script>
    @endpush
@endif