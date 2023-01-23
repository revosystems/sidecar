<{{ $tag ?? 'a'}} class="h-7 py-1.5 px-2 mr-1 bg-white hover:bg-gray-100 text-xs text-gray-400 border border-gray-300 rounded align-middle cursor-pointer shadow-sm transition-all duration-300 sidecar-button-secondary {{ $classes ?? ''}}" {{ $action ?? '' }}>
    @if(isset($icon))
        <i class="fa fa-{{ $icon }}" aria-hidden="true"></i>
    @endif
    {{ $label ?? '' }}
</{{ $tag ?? 'a'}}>