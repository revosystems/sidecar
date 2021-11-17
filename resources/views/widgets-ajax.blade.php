@if(count($report->getWidgets()) > 0 && !$graph->doesApply())
    <div id="sidecar-widgets"  style="height:140px">
        <div class="m-4 p-4 flex justify-center text-gray-400">
            <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
        </div>
    </div>
    @push('edit-scripts')
        <script>
            $('#sidecar-widgets').load('{{route('sidecar.report.widgets', $model)}}?{!! request()->getQueryString() !!}');
        </script>
    @endpush
@endif