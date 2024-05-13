@extends('sidecar::panels.layout')

@section('top-right')
    <x-ui::tooltip>
        <x-slot name="trigger">
            <div class="text-xl"> {{ $last }} </div>
        </x-slot>
        {{ $lastLabel }}
    </x-ui::tooltip>
@stop

@section('content')
    <canvas id="chart-{{ $panel->slug() }}" height="70vh"></canvas>
@stop

@section('scripts')
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
            plugins:{
                legend:{ display : false },
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
@stop
