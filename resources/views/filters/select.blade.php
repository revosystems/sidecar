<div class="inline">
    @if($field->getIcon())
        <i class="fa fa-{{$field->getIcon()}} fa-fw"></i>
    @else
        {{ $field->getTitle() }}
    @endif

    <select id="{{$field->getFilterField()}}" name="filters[{{$field->getFilterField()}}][]" multiple>
        <option value="">--</option>
        @foreach($field->filterOptions() as $key => $value)
            <option value="{{$key}}" @if($report->filters->isFilteringBy($field->getFilterField(), $key)) selected @endif>{{$value}}</option>
        @endforeach
    </select>
</div>
@if($field->filterSearchable)
        @push(config('sidecar.scripts-stack'))
            <script>
                new RVAjaxSelect2('{{ $field->searchableRoute()  }}').show('#{{$field->getFilterField()}}');
            </script>
    @endpush
@endif