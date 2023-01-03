<div id="{{ $panel->slug() }}" class="flex-1 min-w-sm">
    <div class="flex justify-center bg-white m-4 p-4 rounded shadow">
        <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
    </div>
</div>

@push(config('sidecar.scripts-stack'))
    <script>
        window.addEventListener('load', () => SidecarHtmlLoader.load("{{ route('sidecar.panel', get_class($panel)) }}", '{{$panel->slug()}}'))
    </script>
@endpush