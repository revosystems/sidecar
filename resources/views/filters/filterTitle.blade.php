<div class="flex gap-2 items-center">
    @if($field->getIcon())
        <x-ui::icon class="text-gray-500">{{$field->getIcon()}}</x-ui::icon>
    @endif
    <div>
        {{ $field->getTitle() }}
    </div>
</div>