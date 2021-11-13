<div class="m-4">
    <form action="">
        @foreach($availableFilters as $filter)
            {{ $filter->getTitle() }}
            <select name="{{$filter->getSelectField()}}[]" multiple>
                <option value="">--</option>
                @foreach($filter->filterOptions() as $key => $value)
                    <option value="{{$key}}" @if(in_array($key, request($filter->getSelectField()) ?? [])) selected @endif>{{$value}}</option>
                @endforeach
            </select>
        @endforeach
        <button>{{ __(config('sidecar.translationsPrefix').'.filter') }}</button>
    </form>
</div>