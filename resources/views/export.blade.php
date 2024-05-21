<a href="{{route(config('sidecar.exportRoute'), ["report" => $model, "type" => "cvs"])."?".request()->getQueryString()}}">
    <x-ui::secondary-button :async="true" icon="download" hideTextOnSm>
        {{ __(config('sidecar.translationsPrefix').'export') }}
    </x-ui::secondary-button>
</a>