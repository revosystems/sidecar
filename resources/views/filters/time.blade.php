<div class="grid grid-cols-2 gap-2">
    @php $filterValues = $report->filters->dates[$field->getFilterField()] @endphp
    <x-ui::forms.text-input
            type="time"
           id="{{$field->getFilterField()}}_start"
           name="dates[{{$field->getFilterField()}}][start_time]"
           :value="data_get($filterValues, 'start_time', '')"
            class="w-full"
    />
    <x-ui::forms.text-input
            type="time"
            id="{{$field->getFilterField()}}_end"
            name="dates[{{$field->getFilterField()}}][end_time]"
            :value="data_get($filterValues, 'end_time', '')"
            class="w-full"
    />
</div>