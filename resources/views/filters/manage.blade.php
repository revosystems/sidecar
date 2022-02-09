<div class="relative" x-data="{ isOpen: false }">
    <a class="button secondary" x-on:click="isOpen = !isOpen">
        @icon(filter)
        {{ __(config('sidecar.translationsPrefix').'manageFilters') }}
    </a>
    <div class="p-4 absolute bg-white shadow-xl z-50 mt-2" x-cloak x-show.transition="isOpen">
        <div class="text-gray-400 uppercase mb-2">{{ __(config('sidecar.translationsPrefix').'filters') }}</div>
        <div class="">
            <table class="sidecar-filters-table">
            @foreach($report->availableFilters() as $field)
                @if (!($field instanceof Revo\Sidecar\ExportFields\Date))
                    <tr class="h-10">@include('sidecar::filters.filter')</tr>
                @elseif($field->timeFilterable)
                    <tr class="h-10">@include('sidecar::filters.filter')</tr>
                @endif
            @endforeach
            </table>
            <div class="text-right mt-4 mr-4">
                <button class="button secondary">
                    <i class="fa fa-filter fa-fw"></i>
                    {{ __(config('sidecar.translationsPrefix').'filter') }}
                </button>
            </div>
        </div>
    </div>
</div>