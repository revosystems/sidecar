<div class="m-4">
    <form action="">

        @foreach($availableFilters as $filter)
            {{ $filter->getTitle() }}
            @if ($filter instanceof Revo\Sidecar\ExportFields\Date)
                @include('sidecar::filters.date')
            @else
                @include('sidecar::filters.select')
            @endif
        @endforeach

        <select name="groupBy">
            <option value="">--</option>
            @foreach($availableGroupings as $filter)
                @foreach($filter->groupings() as $grouping)
                    <option value="{{$filter->getSelectField()}}:{{$grouping}}" @if(request('groupBy') == $filter->getSelectField()) selected @endif> {{ $filter->getTitle() }} ({{$grouping}})</option>
                @endforeach
            @endforeach
        </select>

        <button>{{ __(config('sidecar.translationsPrefix').'filter') }}</button>
    </form>
</div>