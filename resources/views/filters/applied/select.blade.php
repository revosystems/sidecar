<x-ui::chip :icon="$field->icon">
    <div class="flex flex-wrap gap-1 items-center">
        @php($operand = $report->filters->getOperandFor($field->getFilterField()))

        <span class="text-gray-500">{{ $field->getTitle() }}</span>

        <div class="text-gray-300 border-l border-r px-1">
        @if($operand == "whereNotIn")
            <x-ui::icon>ban</x-ui::icon>
                {{ __(config('sidecar.translationsPrefix').'isNot') }}
        @else
                {{ __(config('sidecar.translationsPrefix').'isAnyOf') }}
        @endif
        </div>

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