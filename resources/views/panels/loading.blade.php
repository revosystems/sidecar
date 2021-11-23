<div id="{{ $panel->slug() }}">
    <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
</div>

@push(config('sidecar.scripts-stack'))
    <script>
        $("#{{$panel->slug()}}").load("{{ route('sidecar.panel', get_class($panel)) }}");
    </script>
@endpush