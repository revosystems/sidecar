@extends(config('sidecar.indexLayout'))

@section('content')
   @include('sidecar::filters')

   @if($withWidgets)
   <div id="sidecar-widgets"></div>
   @push('edit-scripts')
   <script>
      $('#sidecar-widgets').load('{{route('sidecar.report.widgets', 'orders')}}');
   </script>
   @endpush
   @endif
    {!! $exporter->export() !!}
@stop


