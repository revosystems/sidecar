@if($rows)
<div class="sidecar links links-top">
    {{ $rows->links() }}
</div>
<table class="{{$tableClasses}}">
        <thead class="sticky">
        <tr>
        @foreach($fields as $field)
            <th class="{{ $field->getTDClasses() }}">
                @if ($field->sortable)
                    <div class='sortableHeader'>{{ $field->getTitle() }}
                        <div class='sortArrows'>
                            <a href='{{ \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'ASC') }}' class='sortUp'>▲</a>
                            <a href='{{ \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'DESC') }}' class='sortDown'>▼</a>
                        </div>
                    </div>
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
                $("#" + field).val(value);
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
