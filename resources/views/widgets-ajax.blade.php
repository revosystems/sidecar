@if(count($report->getWidgets()) > 0 && (!$graph || !$graph->doesApply()) && !$compare->isComparing())
    <div id="sidecar-widgets" style="height:120px">
        <div class="m-4 p-4 flex justify-center items-center text-gray-400" style="height:140px">
            <div>
                <x-ui::spinner />
            </div>
        </div>
    </div>
    @push('edit-scripts')
        <script>
            window.addEventListener('load', () => SidecarHtmlLoader.load("{{route('sidecar.report.widgets', $model)}}?{!! request()->getQueryString() !!}", 'sidecar-widgets'))
        </script>
    @endpush
@endif