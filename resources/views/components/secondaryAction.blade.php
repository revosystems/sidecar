<{{ $tag ?? 'a'}} class="h-7 py-1.5 px-2 mr-1 bg-white hover:bg-gray-100 text-xs text-gray-400 border border-gray-300 rounded align-middle cursor-pointer shadow-sm transition-all duration-300 sidecar-button-secondary {{ $classes ?? ''}}" {{ $action ?? '' }}>
    @isset($icon)
        <i class="fa fa-{{ $icon }}" aria-hidden="true"></i>
    @endisset
    @isset($label)
    <span class="hidden lg:inline-block">
        {{ $label ?? '' }}
    </span>
    @endisset
</{{ $tag ?? 'a'}}>
