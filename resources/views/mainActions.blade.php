<div class="flex items-center space-x-2">
    <?php $mainActions = $report->mainActions(); ?>
    @foreach($mainActions as $mainAction)
        {!! $mainAction->display($report) !!}
    @endforeach
</div>
