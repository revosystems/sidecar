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
                $("#" + field).append('<option value="'+ value + '" selected="selected">'+value+'</option>');
                $("#sidecar-form").submit();
            }

            function dateInDepth(field, value, start, end){
                $("#sidecar-groupby").val(value);
                 $("#date-range-" + field).val('custom')
                 $("#start_date").val(start)
                 $("#end_date").val(end)
                 $("#sidecar-form").submit();
            }
        </script>
    @endpush
@endif
