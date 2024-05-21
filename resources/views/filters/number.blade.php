<div class="flex flex-col gap-2 w-full">
    @php $filterValues = $report->filters->filtersFor($field->getFilterField()) @endphp
    <div>
        <x-ui::forms.searchable-select
                :searchable="false"
                :id="$field->getFilterField().'-select'"
                :name="'filters['.$field->getFilterField().'][operand]'"
                class="text-center"
        >
            @foreach($field->filterOptions() as $operand => $name)
                <option value="{{$operand}}" @if($operand == data_get($filterValues, 'operand', '')) selected @endif> {{ $name }}</option>
            @endforeach
        </x-ui::forms.searchable-select>
    </div>
    <div class="w-full">
        <x-ui::forms.text-input
                type="number"
                :id="$field->getFilterField()"
                name="filters[{{$field->getFilterField()}}][value]"
                :value="data_get($filterValues, 'value', '')"
                step="any"
                class="w-full"
        />
    </div>
</div>