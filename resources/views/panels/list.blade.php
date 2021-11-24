@extends('sidecar::panels.layout')
@section('content')
    <table class="table striped mt-4">
        @foreach($labels as $label)
            <tr>
                <td class="max-w-sm p-2">{{ $label }}</td>
                <td class="text-right">{{ $values[$loop->index] }}</td>
            </tr>
        @endforeach
    </table>
@stop