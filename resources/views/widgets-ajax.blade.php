@if(count($report->getWidgets()) > 0 && (!$graph || !$graph->doesApply()) && !$compare->isComparing())
    <x-ui::lazy id="sidecar-widgets" style="height:120px" :url="route('sidecar.report.widgets', $model) . '?' . request()->getQueryString() ">
        <div class="m-4 p-4 flex justify-center items-center text-gray-400" style="height:140px">
            <div>
                <x-ui::spinner />
            </div>
        </div>
    </x-ui::lazy>
@endif