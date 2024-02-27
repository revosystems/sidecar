<div class="sidecar-panel bg-white m-4 p-4 rounded shadow">
    <div class="flex justify-between font-bold">
        @if($tooltip = $panel->getTooltip())
            <x-ui::tooltip>
                <x-slot name="trigger">
                    <div class="decoration-dotted" style="text-decoration:underline dotted">{!! __(config('sidecar.translationsPrefix').$panel->getTitle()) !!}</div>
                </x-slot>
                {{ $tooltip }}
            </x-ui::tooltip>
        @else
            <div>{!! __(config('sidecar.translationsPrefix').$panel->getTitle()) !!}</div>
        @endif
        @yield('top-right')
    </div>

    @yield('content')
    @if($link = $panel->getFullReportLink())
        <div class="mt-2">
            <x-ui::learn-more href="{{$link}}">
                {{ __(config('sidecar.translationsPrefix').'viewReport')}}
            </x-ui::learn-more>
        </div>
    @endif
</div>

    @yield('scripts')
