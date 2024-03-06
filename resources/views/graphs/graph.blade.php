<div class="bg-white shadow-sm m-4 p-4 relative" @if (in_array($graph->getType(), ['pie', 'doughnug']))  style="height:60vh" @else style="height:30vh" @endif>
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
                    @if (in_array($graph->getType(), ['pie', 'doughnug']) || count($graph->values) > 1)
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