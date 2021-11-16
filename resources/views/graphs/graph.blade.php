<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="bg-white shadow-sm m-4 p-4">
    <canvas id="chart" height="60vh"></canvas>
</div>

<script>
    const labels = @json($graph->labels);
    const data = {
        labels: labels,
        datasets: [
            @foreach($graph->values as $dataset)
            {
                label: '{{ $dataset['title'] }}',
                backgroundColor: '{{$graph->colors[$loop->index] ?? "#E75129"}}',
                borderColor: '{{$graph->colors[$loop->index] ?? "#E75129"}}',
                data: @json($dataset['values']),
            },
            @endforeach
        ]
    };

    let delayed;
    const config = {
        type: '{{ $graph->getType() }}',
        data: data,
        responsive:true,
        maintainAspectRatio:false,
        options: {
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