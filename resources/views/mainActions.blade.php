<div class="float-right mt-4 mr-4">
    <?php $mainActions = $report->mainActions(); ?>
    @foreach($mainActions as $mainAction)
        {!! $mainAction->display($report) !!}
    @endforeach
</div>