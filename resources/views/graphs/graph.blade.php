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
                backgroundColor: '{{$graph->colors[$loop->index]}}',
                borderColor: '{{$graph->colors[$loop->index]}}',
                data: @json($dataset['values']),
            },
            @endforeach
        ]
    };

    const config = {
        type: '{{ $graph->getType() }}',
        data: data,
        responsive:true,
        maintainAspectRatio:false,
        options: {}
    };
    const myChart = new Chart(document.getElementById('chart'), config);
</script>