@if ($graph && $graph->doesApply())
    @include("sidecar::graphs.graph",[
        "graph" => $graph
   ])
    @if (false)
        <x-ui::lazy id="sidecar-graph" :url="route('sidecar.report.graph', 'orders') .'?'. request()->getQueryString() ">
            <div class="m-4 p-4 flex justify-center text-gray-400">
                <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
            </div>
        </x-ui::lazy>
    @endif
@endif