<x-ui::forms.text-input class="w-full"
    :id="$field->getFilterField()"
    :name="'filters[{$field->getFilterField()}][]'"
    :value="$report->filters->filtersFor($field->getFilterField())->implode(' ')"
/>
