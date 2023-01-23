@if($rows)
<div class="sidecar links links-top">
    {{ $rows->links() }}
</div>
<table class="sidecar-table {{$tableClasses}}">
        <thead class="sticky">
            <tr>
            @foreach($fields as $field)
                <th class="h-9 py-2 @if($loop->first) pl-6 pr-5 @elseif($loop->last) pr-6 @else pr-5 @endif bg-gray-50 text-sm text-gray-500 border-b border-gray-100 font-light sidecar-th {{ $field->getTDClasses() }}">
                    @include('sidecar::widgets.fieldHeader')
                </th>
            @endforeach
            </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
            @foreach($fields as $field)
                <td class="h-3 py-2 @if($loop->first) pl-6 pr-5 @elseif($loop->last) pr-6 @else pr-5 @endif text-sm text-gray-700 sidecar-td{{ $field->getTDClasses() }}">
                    {!! $field->toHtml($row) !!}
                </td>
            @endforeach
            </tr>
        @endforeach
        </tbody>
</table>
<div class="sidecar links links-bottom">
    {{ $rows->links() }}
</div>

    @push(config('sidecar.scripts-stack'))
        <script>
            function filterOnClick(field, value){
                document.getElementById(field).innerHTML += '<option value="'+ value + '" selected="selected">'+value+'</option>';
                document.getElementById("sidecar-form").submit()
            }

            function dateInDepth(field, value, start, end){
                document.getElementById("sidecar-groupby").value = value;
                document.getElementById("date-range-" + field).value = 'custom'
                document.getElementById("start_date").value = start
                document.getElementById("end_date").value = end
                document.getElementById("sidecar-form").submit()
            }
        </script>
    @endpush
@endif
