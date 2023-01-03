@if ($graph && $graph->doesApply())
    @include("sidecar::graphs.graph",[
        "graph" => $graph
   ])
    @if (false)
        <div id="sidecar-graph">
            <div class="m-4 p-4 flex justify-center text-gray-400">
                <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
            </div>
        </div>
        @push(config('sidecar.scripts-stack'))
            <script>
                window.addEventListener('load', () => SidecarHtmlLoader.load("{{route('sidecar.report.graph', 'orders')}}?{!! request()->getQueryString() !!}", 'sidecar-graph'))
            </script>
        @endpush
    @endif
@endif