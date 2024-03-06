<div>
    <x-ui::dropdown offset="12">
        <x-slot name="trigger">
            <x-ui::secondary-button icon="clone" hideTextOnSm>
                {{ __(config('sidecar.translationsPrefix').'save') }}
            </x-ui::secondary-button>
        </x-slot>

        <div class="font-semibold">
            {{ __(config('sidecar.translationsPrefix').'saveReportTitle') }}
        </div>
        <div class="mb-4 text-gray-400 text-sm mt-1">
            {{ __(config('sidecar.translationsPrefix').'saveReportDesc') }}
        </div>

        <form action="{{ route('sidecar.report.store') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="url" value="{{ request()->fullUrl() }}">
            <x-ui::forms.text-input icon="signature" name="name" :placeholder="__(config('sidecar.translationsPrefix').'myReport')" required class="w-full"/>
            <br>
            <x-ui::primary-button :async="true" type="submit" class="mt-2">
                {{ __(config('sidecar.translationsPrefix').'save') }}
            </x-ui::primary-button>
        </form>

    </x-ui::dropdown>
</div>

