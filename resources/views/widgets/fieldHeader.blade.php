<div class='has-tooltip cursor max-w-sm'>
    @if ($tooltip = $field->getTooltip())
     <span class='tooltip rounded shadow-lg p-2 text-xs bg-black text-white mt-7 normal-case'> {{ $tooltip  }}</span>
    @endif
    <div @if($tooltip) style="text-decoration:underline dotted" @endif>
        @if ($field->sortable)
            @if($report->filters->sort->field == $field->getFilterField())
                @if (strtolower($report->filters->sort->order) == 'desc')
                    <a href='{{ \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'ASC') }}' class='bg-gray-200 rounded px-2 py-1 text-black'> {{ $field->getTitle() }} ▼ </a>
                @else
                    <a href='{{ \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'DESC') }}' class='bg-gray-200 rounded px-2 py-1 text-black'> {{ $field->getTitle() }} ▲ </a>
                @endif
            @else
                <a href='{{ \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'DESC') }}' class=''> {{ $field->getTitle() }} </a>
            @endif
        @else
            {{ $field->getTitle() }}
        @endif
    </div>
</div>