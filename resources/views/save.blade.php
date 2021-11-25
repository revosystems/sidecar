<div class="float-right mt-4 mr-4">
    <a class="button secondary dropdown">
        <i class="fa fa-clone" aria-hidden="true"></i>
        {{ __(config('sidecar.translationsPrefix').'save') }}
    </a>

    <div class="dropdown-container m-4 p-4">
        <form action="{{ route('sidecar.report.save') }}?{{request()->getQueryString()}}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="url" value="{{ request()->fullUrl() }}">
            <input name="name" placeholder="My Report" required>
            <button class="button primary mt-4">{{ __(config('sidecar.translationsPrefix').'save') }}</button>
        </form>
    </div>
</div>