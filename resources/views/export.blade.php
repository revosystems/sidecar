<div class="">
    <a href="{{route(config('sidecar.exportRoute'), ["report" => $model, "type" => "cvs"])."?".request()->getQueryString()}}">
        <x-ui::secondary-button :async="true">
            <span class="text-gray-700">@icon(download)</span>
            {{ __(config('sidecar.translationsPrefix').'export') }}
        </x-ui::secondary-button>
    </a>
</div>