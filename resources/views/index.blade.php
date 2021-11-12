@extends(config('sidecar.indexLayout'))

@section('content')
   @include('sidecar::filters')

    {!! $exporter->export() !!}
@stop
