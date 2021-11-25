<div class="float-right mt-4 mr-2">
    <a class="button secondary dropdown">
        <i class="fa fa-clone" aria-hidden="true"></i>
        {{ __(config('sidecar.translationsPrefix').'save') }}
    </a>

    <div class="dropdown-container m-4 p-4" style="right:0px">
        <div class="mb-4">
        {{ __(config('sidecar.translationsPrefix').'saveReportTitle') }}
        </div>
        <form action="{{ route('sidecar.report.store') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="url" value="{{ request()->fullUrl() }}">
            <input name="name" placeholder="My Report" required>
            <br>
            <div class="mb-2 text-gray-400 text-sm mt-1">
                {{ __(config('sidecar.translationsPrefix').'saveReportDesc') }}
            </div>
            <button class="button primary mt-2">{{ __(config('sidecar.translationsPrefix').'save') }}</button>
        </form>
    </div>
</div>