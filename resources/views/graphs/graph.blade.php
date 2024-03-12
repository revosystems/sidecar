
<div class="bg-neutral-200 border ml-4 rounded-lg inline-block">
    <div class="flex items-center gap-2">
    <a href="{{request()->fullUrl()}}&graph_type=pie"  @class(["py-2 px-3 rounded-md text-gray-700", "bg-white shadow" => $graph->getType() == 'pie']) >@icon(chart-pie)</a>
    <a href="{{request()->fullUrl()}}&graph_type=bar"  @class(["py-2 px-3 rounded-md text-gray-700", "bg-white shadow" => $graph->getType() == 'bar']) >@icon(chart-simple)</a>
    <a href="{{request()->fullUrl()}}&graph_type=line" @class(["py-2 px-3 rounded-md text-gray-700", "bg-white shadow" => $graph->getType() == 'line']) >@icon(chart-line)</a>
    </div>
</div>

<div class="bg-white shadow-sm m-4 p-4 relative" @if (in_array($graph->getType(), ['pie', 'doughnut']))  style="height:50vh" @else style="height:30vh" @endif>
    <canvas id="chart"></canvas>
</div>

@push(config('sidecar.scripts-stack'))
    <script>
        const data = {
            labels: @json($graph->labels),
            datasets: [
                @foreach($graph->values as $dataset)
                {
                    label: '{{ $dataset['title'] }}',
                    @if (in_array($graph->getType(), ['pie', 'doughnut']) || count($graph->values) > 1)
                        backgroundColor: @json(Revo\Sidecar\Filters\Graph::$colors),
                        borderColor: @json(Revo\Sidecar\Filters\Graph::$colors),
                    @elseif ($graph->getType() == 'line')
                        backgroundColor: "#E75129",
                        borderColor: "#E75129",
                    @else
                        backgroundColor: @json(Revo\Sidecar\Filters\Graph::$colors),
                        borderColor: @json(Revo\Sidecar\Filters\Graph::$colors),
                    @endif
                    data: @json($dataset['values']),
                },
                @endforeach
            ]
        };

        let delayed;
        const config = {
            type: '{{ $graph->getType() }}',
            data: data,
            options: {
                responsive:true,
                maintainAspectRatio:false,
                animation: {
                    onComplete: () => {
                        delayed = true;
                    },
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default' && !delayed) {
                            delay = context.dataIndex * 100 + context.datasetIndex * 33;
                        }
                        return delay;
                    },
                }
            }
        };
        const myChart = new Chart(document.getElementById('chart'), config);
    </script>
@endpush