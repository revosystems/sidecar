@extends(config('sidecar.indexLayout'))

@section('content')
    {!! $exporter->export() !!}
@stop
