@if ((new \Revo\Sidecar\Filters\Graph($report))->doesApply($report))
    <div id="sidecar-graph">
        <div class="m-4 p-4 flex justify-center text-gray-400">
            <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
        </div>
    </div>
    @push('edit-scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            $('#sidecar-graph').load('{{route('sidecar.report.graph', 'orders')}}?{!! request()->getQueryString() !!}');
        </script>
    @endpush
@endif