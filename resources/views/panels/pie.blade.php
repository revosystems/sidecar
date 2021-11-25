@extends('sidecar::panels.layout')

@section('content')
    <div class="mt-4">
        <canvas id="chart-{{ $panel->slug() }}" height="70vh"></canvas>
    </div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const data = {
        labels: @json($labels),
        datasets: [
            {
                lineTension: 0,
                borderWidth: 1,
                pointStyle: 'rectRot',
                backgroundColor: @json(Revo\Sidecar\Filters\Graph::$colors),
                borderColor: @json(Revo\Sidecar\Filters\Graph::$colors),
                data: @json($values),
            }
        ]
    };

    let delayed;
    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive:true,
             // maintainAspectRatio:true,
            plugins:{
                legend:{
                    display : true,
                    position: 'bottom',
                    align : 'start',
                    labels: {
                        usePointStyle: true,
                    }
                },
                title: { display: false },
            },
            elements: {
                point : {
                    radius:1,
                    borderWidth: 0,
                    hoverRadius: 8,
                }
            },
            scales: {
                xAxes: {
                    display:false,
                },
                yAxes: {
                    display:false,
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
@stop