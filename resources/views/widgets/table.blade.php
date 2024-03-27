@if($rows)
<div class="sidecar links links-top p-4">
    {{ $rows->links() }}
</div>
<x-ui::table.table class="sidecar-table {{$tableClasses}}">
        <x-ui::table.header class="sticky">
            <x-ui::table.row>
            @foreach($fields as $field)
                <x-ui::table.header-cell class="{{ $field->getTDClasses() }}">
                    @include('sidecar::widgets.fieldHeader')
                </x-ui::table.header-cell>
            @endforeach
            </x-ui::table.row>
        </x-ui::table.header>
        <x-ui::table.body>
        @foreach($rows as $row)
            <x-ui::table.row>
            @foreach($fields as $field)
                <x-ui::table.cell class="{{ $field->getTDClasses() }}">
                    {!! $field->toHtml($row) !!}
                </x-ui::table.cell>
            @endforeach
            </x-ui::table.row>
        @endforeach
        </x-ui::table.body>
</x-ui::table.table>

<div class="sidecar links links-bottom p-4">
    {{ $rows->links() }}
</div>

    @push(config('sidecar.scripts-stack'))
        <script>
            function filterOnClick(field, value){
                document.getElementById(field).innerHTML += '<option value="'+ value + '" selected/>';
                document.querySelector("input[name='filters[" + field + "][]']").value = value;
                removeGrouping(field)

                document.getElementById("sidecar-form").submit()
            }

            function removeGrouping(field){
                //document.querySelector("select[name='groupBy[]']").options.length = 0; // Removes all the groupings
                let index = document.querySelector("option[value='" + field + ":default']")?.index
                if (index !== null) {
                    document.querySelector("select[name='groupBy[]']").remove(index)
                }
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
