<div class="sidecar-panel bg-white m-4 p-4 rounded shadow">
    <div class="flex justify-between">
        <div>{{ __(config('sidecar.translationsPrefix').$panel->title) }}</div>
        <div class="text-xl"> {{ $last }}</div>
    </div>
    <canvas id="chart-{{ $panel->slug() }}" height="70vh"></canvas>
    <div class="mt-2">
        <a href="">{{ __(config('sidecar.translationsPrefix').'viewReport')}}</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const data = {
        labels: @json($labels),
        datasets: [
            {
                lineTension: 0,
                borderWidth: 1,
                backgroundColor: "#E75129",
                borderColor: "#E75129",
                data: @json($values),
            }
        ]
    };

    let delayed;
    const config = {
        type: 'line',
        data: data,
        options: {
            responsive:true,
             // maintainAspectRatio:true,
            plugins:{
                legend:{ display : false },
                title: { display: false },
            },
            elements: {
                point : {
                    radius:0,
                    borderWidth: 0,
                    // hoverRadius: 0,
                }
            },
            scales: {
                xAxes: {
                    display:false,
                },
                yAxes: {
                    display:true,
                }
            },
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
    const myChart = new Chart(document.getElementById('chart-' + '{{ $panel->slug() }}'), config);
</script>