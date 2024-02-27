@extends(config('sidecar.indexLayout'))

@section('content')

    <div class="flex justify-between pl-4 pr-2 py-4">
            @if($tooltip = $report->getTooltip())
                <x-ui::tooltip>
                    <x-slot name="trigger">
                        <h2 class="text-xl font-bold">
                            <div class="" style="text-decoration:underline dotted">{!! $report->getTitle() !!}</div>
                        </h2>
                    </x-slot>
                    {!! $tooltip !!}
                </x-ui::tooltip>
            @else
                <h2 class="text-xl font-bold">
                    <div class="">{!! $report->getTitle() !!}</div>
                </h2>
            @endif

        <div class="flex space-x-2">
            @include('sidecar::mainActions')
            @includeWhen($report->exportable, 'sidecar::export')
            @includeWhen($report->canBeSaved, 'sidecar::save')
        </div>
    </div>

    @include('sidecar::filters')
    @include('sidecar::graphs.compare-graph')
    @include('sidecar::widgets-ajax')
    @include('sidecar::graphs.graph-ajax')

    {!! $exporter->export() !!}
@stop


