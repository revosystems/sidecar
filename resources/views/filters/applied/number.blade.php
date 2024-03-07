<x-ui::chip :icon="$field->icon">
    <span class="text-gray-400">{{ $field->getTitle() }}</span>
    {{ $report->filters->filtersFor($field->getFilterField())['operand'] }}
    {{ $report->filters->filtersFor($field->getFilterField())['value'] }}
    <span class="border-l text-gray-400 ml-2 pl-2 transition-all hover:text-black cursor-pointer"
          onclick="
                            document.getElementById('{{$field->getFilterField()}}').value = '';
                            document.getElementById('sidecar-apply-button').style.display = 'block';
                            this.parentElement.parentElement.style.display = 'none';
                          "
    >
                        @icon(xmark)
                    </span>
</x-ui::chip>