@if($report->exportable)
    <div class="float-right mt-4 mr-4">
        <a class="button secondary" href="{{ route('sidecar.report.export', ["report" => $model, "type" => "cvs"])."?".request()->getQueryString() }}">
            <i class="fa fa-download" aria-hidden="true"></i>
            {{ __(config('sidecar.translationsPrefix').'export') }}
        </a>
    </div>
@endif