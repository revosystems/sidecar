<x-ui::lazy class="flex-1 min-w-sm"
            :url="route('sidecar.panel', get_class($panel))" id="{{ $panel->slug() }}" >
    <div class="flex justify-center bg-white m-4 p-4 rounded shadow h-44">
        <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
    </div>
</x-ui::lazy>