<div class="relative" x-data="{ isOpen: false }">
    @include('sidecar::components.secondaryAction', [
        'action' => 'x-on:click=isOpen=!isOpen',
        'icon' => 'filter',
        'label' => __(config('sidecar.translationsPrefix').'manageFilters'),
    ])
    <div class="fixed z-40 w-full h-full top-0 left-0" x-on:click="isOpen = false; setTimeout(() => document.elementFromPoint($event.clientX, $event.clientY).click(), 100)" x-cloak x-show="isOpen"></div>
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
                @include('sidecar::components.secondaryAction', [
                    'tag' => 'button',
                    'icon' => 'filter',
                    'label' => __(config('sidecar.translationsPrefix').'filter'),
                ])
            </div>
        </div>
    </div>
</div>