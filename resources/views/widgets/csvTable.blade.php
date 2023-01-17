@if($rows)
{{ $fields->map->exportTitle()->implode(";") }}
@foreach($rows as $row)
    {!! $fields->map(function($field) use($row) {
        return $field->toCsv($row);
    })->implode(";") !!}
@endforeach
@endif
