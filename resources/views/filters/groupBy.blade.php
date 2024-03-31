@if ($report->availableGroupings()->count() > 0)
    <div class="sidecar-group-by w-full flex flex-col md:flex-row items-center gap-2">
        
        <x-ui::forms.searchable-select class="h-14" id="sidecar-groupby-date" name="groupBy[]" placeholder="{{__(config('sidecar.translationsPrefix').'groupBy') }}..." class="w-full md:w-auto" icon="calendar-plus">
            @foreach($report->availableGroupings() as $filter)
                @if(count($filter->groupings()) > 1)
                    <option value="">{{ trans_choice(config('sidecar.translationsPrefix').'selectDateGroup', 1) }}...</option>
                    @foreach($filter->groupings() as $grouping)
                        <option value="{{$filter->getFilterField()}}:{{$grouping}}"
                                @if ($report->filters->groupBy->isGroupingBy($filter->getFilterField(), $grouping)) selected @endif>
                            @if ($grouping == "default")
                                {{ str_replace(" (default)", "", "{$filter->getTitle()}") }}
                            @else
                                {{ str_replace(" (default)", "", $filter->getTitle() . " (" . trans_choice(config('sidecar.translationsPrefix').$grouping, 1) . ")") }}
                            @endif
                        </option>
                    @endforeach
                @endif
            @endforeach
        </x-ui::forms.searchable-select>

        <x-ui::forms.multiple-select id="sidecar-groupby" name="groupBy[]" placeholder="{{__(config('sidecar.translationsPrefix').'groupBy') }}..." class="w-full" icon="layer-group">
            @foreach($report->availableGroupings() as $filter)
                @if(count($filter->groupings()) == 1)
                    @foreach($filter->groupings() as $grouping)
                        <option value="{{$filter->getFilterField()}}:{{$grouping}}"
                            @if ($report->filters->groupBy->isGroupingBy($filter->getFilterField(), $grouping)) selected @endif>
                            @if ($grouping == "default")
                                {{ str_replace(" (default)", "", "{$filter->getTitle()}") }}
                            @else
                                {{ str_replace(" (default)", "", $filter->getTitle() . " (" . trans_choice(config('sidecar.translationsPrefix').$grouping, 1) . ")") }}
                            @endif
                        </option>
                    @endforeach
                @endif
            @endforeach
        </x-ui::forms.multiple-select>
    </div>

    @push(config('sidecar.scripts-stack'))
        <script>
            document.getElementById('sidecar-groupby').addEventListener('change', function(){
                document.getElementById('sidecar-apply-button').style.display = 'block'
                document.getElementById('sidecar-apply-button').classList.remove('hidden')
            })
            document.getElementById('sidecar-groupby-date').addEventListener('change', function(){
                document.getElementById('sidecar-apply-button').style.display = 'block'
                document.getElementById('sidecar-apply-button').classList.remove('hidden')
            })
        </script>
    @endpush
@endif
