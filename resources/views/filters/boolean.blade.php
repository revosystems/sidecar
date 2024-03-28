@php(
    $value = $report->filters->filtersFor($field->getFilterField())[0] ?? null
)
<x-ui::forms.select
        id="{{$field->getFilterField()}}"
        name="filters[{{$field->getFilterField()}}]"
        class="w-full"
>
    <option value=""  @if($value === null) selected @endif >--</option>
    <option value="0" @if($value === "0") selected @endif>{{ __(config('sidecar.translationsPrefix').'no') }}</option>
    <option value="1" @if($value === "1") selected @endif>{{ __(config('sidecar.translationsPrefix').'yes') }}</option>
</x-ui::forms.select>