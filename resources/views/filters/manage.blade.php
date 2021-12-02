<div class="mt-4 ml-4 inline" x-data="{ isOpen: false }">
    <a class="button secondary" @click="isOpen = !isOpen">
        @icon(filter)
        {{ __(config('sidecar.translationsPrefix').'manageFilters') }}
    </a>
    <div class="p-4 mt-2 ml-12 absolute bg-white shadow-xl" @click.outside="isOpen = false" x-cloak x-show.transition="isOpen">
        <div class="text-gray-400 uppercase mb-2">{{ __(config('sidecar.translationsPrefix').'filters') }}</div>
        <div class="">
            @foreach($report->availableFilters() as $field)
                @if (!($field instanceof Revo\Sidecar\ExportFields\Date))
                    <div class="mt-4"> @include('sidecar::filters.filter') </div>
                @endif
            @endforeach
            <div class="text-right mt-4 -mr-4">
                <button class="button secondary">
                    <i class="fa fa-filter fa-fw"></i>
                    {{ __(config('sidecar.translationsPrefix').'filter') }}
                </button>
            </div>
        </div>
    </div>
</div>