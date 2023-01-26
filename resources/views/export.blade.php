<div class="float-right mt-4 {{$report->exportable ? 'mr-2' : 'mr-4'}}">
    @include('sidecar::components.secondaryAction', [
        'action' => 'href=' . route(config('sidecar.exportRoute'), ["report" => $model, "type" => "cvs"])."?".request()->getQueryString(),
        'icon' => 'download',
        'label' => __(config('sidecar.translationsPrefix').'export'),
    ])
</div>