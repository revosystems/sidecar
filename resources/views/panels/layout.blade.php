<div class="sidecar-panel bg-white m-4 p-4 rounded shadow">
    <div class="flex justify-between font-bold">
        @if($tooltip = $panel->getTooltip())
            <div class='has-tooltip cursor'>
                <span class='tooltip rounded shadow-lg p-2 text-xs bg-black text-white mt-4'> {{ $tooltip }}</span>
                <div class="" style="text-decoration:underline dotted">{!! __(config('sidecar.translationsPrefix').$panel->getTitle()) !!}</div>
            </div>
        @else
            <div class="">{!! __(config('sidecar.translationsPrefix').$panel->getTitle()) !!}</div>
        @endif
        @yield('top-right')
    </div>

    @yield('content')
    @if($link = $panel->getFullReportLink())
    <div class="mt-2">
        <a href="{{$link}}">{{ __(config('sidecar.translationsPrefix').'viewReport')}}</a>
    </div>
    @endif
</div>

    @yield('scripts')
