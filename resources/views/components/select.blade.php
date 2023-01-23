<select id="{{ $id }}" name="{{ $name }}" style="width: {{ $width ?? '300px'}};"
    class="h-8 px-1 border border-gray-400 rounded text-sm text-gray-700 sidecar-select {{ $classes ?? '' }}"
>
    {{ $slot }}
</select>