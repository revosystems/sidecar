<div class="sidecar-widgets flex justify-between space-x-2 mx-4">
    @foreach($widgets as $widget)
        {!! $widget->render($widgetsResult) !!}
    @endforeach
</div>