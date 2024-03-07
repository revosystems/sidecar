<div id="sidecar-applied-filters" class="mt-4 flex items-center space-x-1">
    @foreach($report->availableFilters()->sort() as $field)
        @if (\Illuminate\Support\Arr::dimensions($field->filterOptions()) > 1)
            @php ($options = collect($field->filterOptions())->mapWithKeys(fn($item) => $item))
        @else
            @php ($options = $field->filterOptions())
        @endif
        @foreach($options as $key => $value)
            @if($report->filters->isFilteringBy($field->getFilterField(), $key))
                <x-ui::chip :icon="$field->icon">

                @if ($field instanceof \Revo\Sidecar\ExportFields\Number)
                    <span class="text-gray-400">{{ $field->getTitle() }}</span>
                    {{ $value }}
                    {{ $report->filters->filtersFor($field->getFilterField())['value'] }}

                    <span class="border-l text-gray-400 ml-2 pl-2 transition-all hover:text-black cursor-pointer"
                          onclick="
                            document.getElementById('{{$field->field}}').value = '';
                            document.getElementById('sidecar-apply-button').style.display = 'block';
                            this.parentElement.parentElement.style.display = 'none';
                          "
                        >
                        @icon(xmark)
                    </span>

                @else
                    {{ $value }}


                    <span class="border-l text-gray-400 ml-2 pl-2 transition-all hover:text-black cursor-pointer"
                          onclick="
                             var selectobject = document.getElementById('{{$field->getSelectField()}}');
                             for (var i=0; i < selectobject.length; i++) {
                                if (selectobject.options[i].value == '{{$key}}'){
                                    selectobject.remove(i);
                                }
                             }

                            document.getElementById('sidecar-apply-button').style.display = 'block';
                            this.parentElement.parentElement.style.display = 'none';
                          "
                        >
                        @icon(xmark)
                    </span>

                @endif
                </x-ui::chip>
            @endif
        @endforeach
    @endforeach
</div>