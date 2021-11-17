@if($filter->getIcon())
    <i class="fa fa-{{$filter->getIcon()}} fa-fw"></i>
@else
    {{ $filter->getTitle() }}
@endif

<select id="{{$filter->getFilterField()}}" name="filters[{{$filter->getFilterField()}}][]" multiple>
    <option value="">--</option>
    @foreach($filter->filterOptions() as $key => $value)
        <option value="{{$key}}" @if($report->filters->isFilteringBy($filter->getFilterField(), $key)) selected @endif>{{$value}}</option>
    @endforeach
</select>

@if($filter->filterSearchable)
        @push(config('sidecar.scripts-stack'))
            <script>
                new RVAjaxSelect2('{{ $filter->searchableRoute()  }}').show('#{{$filter->getFilterField()}}');
            </script>
    @endpush
@endif