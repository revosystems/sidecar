@extends(config('sidecar.indexLayout'))

@section('content')
   @include('sidecar::export')
   <h2 class="text-xl ml-6 mb-8 mt-4 font-bold">
      <div class="inline" style="font-size: 20px;"> {{ $report->getTitle() }} </div>
   </h2>
   @include('sidecar::filters')
   @include('sidecar::graphs.compare-graph')
   @include('sidecar::widgets-ajax')
   @include('sidecar::graphs.graph-ajax')

   {!! $exporter->export() !!}
@stop


