@if ($report->availableGroupings()->count() > 0)
    <div class="sidecar-group-by">
        <select id="sidecar-groupby" name="groupBy[]" multiple class="p-1" style="width: 150px">
            @foreach($report->availableGroupings() as $filter)
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
            @endforeach
        </select>
    </div>

    @push(config('sidecar.scripts-stack'))
        <script>
            window.addEventListener('load', () => {
                SidecarSelector.selector(document.getElementById('sidecar-groupby'), "{{__(config('sidecar.translationsPrefix').'groupBy') }}...")
                
                document.getElementById('sidecar-groupby').addEventListener('change', function(){
                    document.getElementById('sidecar-apply-button').style.display = 'block'
                    document.getElementById('sidecar-apply-button').classList.remove('hidden')
                })
            })
        </script>
    @endpush
@endif
