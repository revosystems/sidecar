<x-ui::dropdown class="w-[95%] sm:w-auto min-w-64">
    <x-slot name="trigger">
        <x-ui::secondary-button icon="filter">
            {{ __(config('sidecar.translationsPrefix').'manageFilters') }}
        </x-ui::secondary-button>
    </x-slot>

    <div class="text-gray-400 uppercase mb-2">
        {{ __(config('sidecar.translationsPrefix').'filters') }}
    </div>
    <div class="" x-data="{
            selectedFilter:null
        }"
        @keyup.escape="
            console.log('esc')
            selectedFilter = null;
        "
    >
        <div class="flex flex-col">
            @foreach($report->availableFilters() as $field)
                @include('sidecar::filters.filter')
            @endforeach
        </div>

        <x-ui::primary-button type="submit" :async="true" class="mt-4">
            @icon(filter)
            {{__(config('sidecar.translationsPrefix').'filter')}}
        </x-ui::primary-button>
    </div>

</x-ui::dropdown>
