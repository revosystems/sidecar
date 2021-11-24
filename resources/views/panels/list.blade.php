<div class="sidecar-panel bg-white m-4 p-4 rounded shadow">
    <div class="flex justify-between font-bold">
        <div>{{ __(config('sidecar.translationsPrefix').$panel->getTitle()) }}</div>
    </div>

    <table class="table striped mt-4">
        @foreach($labels as $label)
            <tr>
                <td class="max-w-sm p-2">{{ $label }}</td>
                <td class="text-right">{{ $values[$loop->index] }}</td>
            </tr>
        @endforeach
    </table>

    <div class="mt-2">
        <a href="">{{ __(config('sidecar.translationsPrefix').'viewReport')}}</a>
    </div>
</div>