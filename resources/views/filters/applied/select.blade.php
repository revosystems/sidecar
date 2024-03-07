<x-ui::chip :icon="$field->icon">
    <div class="flex flex-wrap gap-1 items-center">
        @php($operand = $report->filters->requestFilters[$field->getFilterField().'-operand'])
        @if($operand == "whereNotIn")
            <x-ui::icon class="text-red-500">ban</x-ui::icon>
        @endif
        <span class="text-gray-400">{{ $field->getTitle() }}</span>

        @if (\Illuminate\Support\Arr::dimensions($field->filterOptions()) > 1)
            @php ($options = collect($field->filterOptions())->mapWithKeys(fn($item) => $item))
        @else
            @php ($options = $field->filterOptions())
        @endif


        @foreach($options as $key => $value)
            @if($report->filters->isFilteringBy($field->getFilterField(), $key))
                <div class="ml-2">
                    {{ $value }}
                    <span class="@if(!$loop->last) border-r pr-2 @endif text-gray-400 transition-all hover:text-black cursor-pointer"
                          onclick="
                                             var selectobject = document.getElementById('{{$field->getFilterField()}}');
                                             for (var i=0; i < selectobject.length; i++) {
                                                if (selectobject.options[i].value == '{{$key}}'){
                                                    selectobject.remove(i);
                                                }
                                             }

                                            document.getElementById('sidecar-apply-button').style.display = 'block';
                                            this.parentElement.style.display = 'none';
                                          "
                    >
                                    @icon(xmark)
                                </span>
                </div>
            @endif
        @endforeach
    </div>
</x-ui::chip>