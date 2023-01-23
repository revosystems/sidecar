<{{ $tag ?? 'button'}} @if(isset($id)) id="{{$id}}" @endif class="h-8 min-w-[96px] py-1.5 px-2 mr-1 bg-brand text-xs text-white border border-brand rounded align-middle cursor-pointer shadow-sm sidecar-button {{ $classes ?? ''}}" {{ $action ?? '' }}>
    @if(isset($icon))
        <i class="fa fa-{{ $icon }}" aria-hidden="true"></i>
    @endif
    {{ $label ?? '' }}
</{{ $tag ?? 'button'}}>