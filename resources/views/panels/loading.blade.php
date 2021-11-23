<div id="{{ $panel->slug() }}">
    <div class="bg-white m-4 p-4 rounded shadow w-48 h-48">
        <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
    </div>
</div>

@push(config('sidecar.scripts-stack'))
    <script>
        $("#{{$panel->slug()}}").load("{{ route('sidecar.panel', get_class($panel)) }}");
    </script>
@endpush