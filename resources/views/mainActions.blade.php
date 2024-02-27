<div class="">
    <?php $mainActions = $report->mainActions(); ?>
    @foreach($mainActions as $mainAction)
        {!! $mainAction->display($report) !!}
    @endforeach
</div>
