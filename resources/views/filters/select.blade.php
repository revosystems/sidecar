<div class="flex space-x-2 items-center">
    <div class="w-34">

        <x-ui::forms.searchable-select :searchable="false"
               id="{{$field->getFilterField()}}-select"
               name="filters[{{$field->getFilterField()}}-operand]"
               class="text-center">
            <option value="whereIn">
                {{ __(config('sidecar.translationsPrefix').'isAnyOf') }}
            </option>
            <option value="whereNotIn" @if($report->filters->requestFilters[$field->getFilterField() . '-operand'] ?? '' == 'whereNotIn')) selected @endif>
                {{ __(config('sidecar.translationsPrefix').'isNot') }}
            </option>
        </x-ui::forms.searchable-select>
    </div>

    <x-ui::forms.multiple-select
        id="{{$field->getFilterField()}}"
        name="filters[{{$field->getFilterField()}}][]"
        :url="($field->filterSearchable ? $field->searchableRoute() : null)"
    >
        @if (\Illuminate\Support\Arr::dimensions($field->filterOptions($report->filters)) > 1)
            @foreach($field->filterOptions($report->filters) as $category => $categories)
                <optgroup label="{{ $category }}">
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}" @if($report->filters->isFilteringBy($field->getFilterField(), $key)) selected @endif>{{$value}}</option>
                    @endforeach
                </optgroup>
            @endforeach
        @else
            @foreach($field->filterOptions($report->filters) as $key => $value)
                <option value="{{$key}}" @if($report->filters->isFilteringBy($field->getFilterField(), $key)) selected @endif>{{$value}}</option>
            @endforeach
        @endif
    </x-ui::forms.multiple-select>
</div>