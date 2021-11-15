<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="bg-white shadow-sm m-4 p-4">
    <canvas id="chart" height="60vh"></canvas>
</div>

<script>
    const labels = @json($graph->labels);
    const data = {
        labels: labels,
        datasets: [{
            label: '{{ $graph->getTitle() }}',
            backgroundColor: '#E75129',
            borderColor: '#E75129',
            data: @json($graph->values),
        }]
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