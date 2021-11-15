<div class="flex justify-between ml-3">
    @foreach($widgets as $widget)
    {!! $widget->render($widgetsResult) !!}
    @endforeach
</div>