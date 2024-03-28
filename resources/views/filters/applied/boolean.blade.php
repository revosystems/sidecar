<x-ui::chip :icon="$field->icon">
    <div class="flex flex-wrap gap-1 items-center">
        <span class="text-gray-500">{{ $field->getTitle() }}</span>
        <div class="text-gray-300 border-l border-r px-1">
            {{ __(config('sidecar.translationsPrefix').'is') }}
        </div>
        @if($report->filters->filtersFor($field->getFilterField())[0])
            {{ __(config('sidecar.translationsPrefix').'yes') }}
        @else
            {{ __(config('sidecar.translationsPrefix').'no') }}
        @endif
            <span class="border-l text-gray-400 ml-2 pl-2 transition-all hover:text-black cursor-pointer"
                  onclick="
                document.getElementById('{{$field->getFilterField()}}').value = '0';
                document.getElementById('sidecar-apply-button').style.display = 'block';
                this.parentElement.parentElement.parentElement.style.display = 'none';
              "
            >
            @icon(xmark)
        </span>
    </div>
</x-ui::chip>