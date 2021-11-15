@extends(config('sidecar.indexLayout'))

@section('content')
   <h2 class="text-xl ml-6 mb-8 mt-4 font-bold">
      <div class="inline" style="font-size: 20px;"> {{ $report->getTitle() }} </div>
   </h2>
   @include('sidecar::filters')

   @if(count($report->getWidgets()) > 0)
   <div id="sidecar-widgets">
      <div class="m-4 p-4 flex justify-center text-gray-400">
         <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
      </div>
   </div>
   @push('edit-scripts')
   <script>
      $('#sidecar-widgets').load('{{route('sidecar.report.widgets', 'orders')}}');
   </script>
   @endpush
   @endif
    {!! $exporter->export() !!}
@stop


