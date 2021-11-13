<div class="m-4">
    <form action="">

        @foreach($availableFilters as $filter)
            {{ $filter->getTitle() }}
            @if ($filter instanceof Revo\Sidecar\ExportFields\Date)
                <input type="date" name="{{$filter->getSelectField()}}[start]" value="{{request($filter->getSelectField())['start']}}">
                <input type="date" name="{{$filter->getSelectField()}}[end]"   value="{{request($filter->getSelectField())['end']}}">
            @else
            <select name="{{$filter->getSelectField()}}[]" multiple>
                <option value="">--</option>
                @foreach($filter->filterOptions() as $key => $value)
                    <option value="{{$key}}" @if(in_array($key, request($filter->getSelectField()) ?? [])) selected @endif>{{$value}}</option>
                @endforeach
            </select>
            @endif
        @endforeach
        <button>{{ __(config('sidecar.translationsPrefix').'.filter') }}</button>
    </form>
</div>