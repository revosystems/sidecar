<x-ui::chip :icon="$field->icon">
    <span class="text-gray-500">{{ $field->getTitle() }}</span>:
    {{ $report->filters->dateFiltersFor($field->getFilterField())["start_time"] }}
    -
    {{ $report->filters->dateFiltersFor($field->getFilterField())["end_time"] }}
    <span class="border-l text-gray-400 ml-2 pl-2 transition-all hover:text-black cursor-pointer"
          onclick="
            document.getElementById('{{$field->getFilterField()}}_start').value = '';
            document.getElementById('{{$field->getFilterField()}}_end').value = '';
            document.getElementById('sidecar-apply-button').style.display = 'block';
            this.parentElement.parentElement.style.display = 'none';
          "
    >
        @icon(xmark)
    </span>
</x-ui::chip>