<x-ui::dropdown offset="12">
    <x-slot name="trigger">
        <x-ui::secondary-button>
            <div class="flex space-x-2 items-center">
                <div>@icon(filter)</div>
                <div class="hidden sm:show">{{ __(config('sidecar.translationsPrefix').'manageFilters') }}</div>
            </div>
        </x-ui::secondary-button>
    </x-slot>

    <div class="text-gray-400 uppercase mb-2">
        {{ __(config('sidecar.translationsPrefix').'filters') }}
    </div>
    <div class="">
        <table>
            @foreach($report->availableFilters() as $field)
                @if (!($field instanceof Revo\Sidecar\ExportFields\Date))
                    <tr class="h-10">@include('sidecar::filters.filter')</tr>
                @elseif($field->timeFilterable)
                    <tr class="h-10">@include('sidecar::filters.filter')</tr>
                @endif
            @endforeach
        </table>

        <x-ui::primary-button type="submit" :async="true" class="mt-4">
            @icon(filter)
            {{__(config('sidecar.translationsPrefix').'filter')}}
        </x-ui::primary-button>

    </div>

</x-ui::dropdown>
