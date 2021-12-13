@if($rows)
<div class="sidecar links links-top">
    {{ $rows->links() }}
</div>
<table class="{{$tableClasses}}">
        <thead class="sticky">
        <tr>
        @foreach($fields as $field)
            <th class="sidecar-th {{ $field->getTDClasses() }}">
                @if ($field->sortable)
                    @if($report->filters->sort->field == $field->getFilterField())
                        @if (strtolower($report->filters->sort->order) == 'desc')
                            <a href='{{ \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'ASC') }}' class='bg-gray-200 rounded px-2 py-1 text-black'> {{ $field->getTitle() }} ▼ </a>
                        @else
                            <a href='{{ \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'DESC') }}' class='bg-gray-200 rounded px-2 py-1 text-black'> {{ $field->getTitle() }} ▲ </a>
                        @endif
                    @else
                        <a href='{{ \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'DESC') }}' class=''> {{ $field->getTitle() }} </a>
                    @endif


                @else
                    {{ $field->getTitle() }}
                @endif
            </th>
        @endforeach
        </tr></thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
            @foreach($fields as $field)
                <td class="{{ $field->getTDClasses() }}">
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
                //$("#" + field).val(value);
                //When it is ajax, the option does not exist in the select
                document.getElementById(field).append('<option value="'+ value + '" selected="selected">'+value+'</option>');
                document.getElementById("sidecar-form").submit()
            }

            function dateInDepth(field, value, start, end){
                document.getElementById("sidecar-groupby").value = value;
                document.getElementById("date-range-" + field).value = 'custom'
                document.getElementById("start_date").value = start
                document.getElementById("end_date").value = end
                document.getElementById("sidecar-form").submit();
            }
        </script>
    @endpush
@endif
