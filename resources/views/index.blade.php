@extends(config('sidecar.indexLayout'))

@section('content')
   @include('sidecar::export')
   @include('sidecar::save')

   <h2 class="text-xl ml-6 mb-8 mt-4 font-bold">
      @if($tooltip = $report->getTooltip())
         <div class='has-tooltip cursor max-w-sm'>
            <span class='tooltip rounded shadow-lg p-2 text-xs bg-black text-white mt-7'> {{ $tooltip }}</span>
            <div class="" style="text-decoration:underline dotted">{!! __(config('sidecar.translationsPrefix').$report->getTitle()) !!}</div>
         </div>
      @else
         <div class="">{!! __(config('sidecar.translationsPrefix').$report->getTitle()) !!}</div>
      @endif
   </h2>
   @include('sidecar::filters')
   @include('sidecar::graphs.compare-graph')
   @include('sidecar::widgets-ajax')
   @include('sidecar::graphs.graph-ajax')

   {!! $exporter->export() !!}

   @push(config('sidecar.scripts-stack'))
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   @endpush
@stop


