@if (isset($compare) && $compare->isComparing())
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="bg-white shadow-sm m-4 p-4">
    <canvas id="compare-chart" height="60vh"></canvas>
</div>

<script>
    const compareData = {
        labels: @json($compare->labels),
        datasets: [
            @foreach($compare->results as $dataset)
            {
                label: '{{ $dataset['title'] }}',
                    backgroundColor: '{{$compare->colors[$loop->index] ?? "#E75129"}}',
                    borderColor: '{{$compare->colors[$loop->index] ?? "#E75129"}}',
                data: @json($dataset['values']),
            },
            @endforeach
        ]
    };

    let compareDelayed;
    const compareConfig = {
        type: 'bar',
        data: compareData,
        options: {
            responsive:true,
            // maintainAspectRatio:true,
            animation: {
                onComplete: () => {
                    compareDelayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !compareDelayed) {
                        delay = context.dataIndex * 100 + context.datasetIndex * 33;
                    }
                    return delay;
                },
            }
        }
    };
    const compareChart = new Chart(document.getElementById('compare-chart'), compareConfig);
</script>
@endif