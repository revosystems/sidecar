<div>
    <x-ui::dropdown offset="12">
        <x-slot name="trigger">
            <x-ui::secondary-button>
                <span class="text-gray-700">@icon(clone)</span>
                {{ __(config('sidecar.translationsPrefix').'save') }}
            </x-ui::secondary-button>
        </x-slot>

        <div class="mb-4 font-semibold">
            {{ __(config('sidecar.translationsPrefix').'saveReportTitle') }}
        </div>
        <form action="{{ route('sidecar.report.store') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="url" value="{{ request()->fullUrl() }}">
            <x-ui::forms.text-input name="name" :placeholder="__(config('sidecar.translationsPrefix').'myReport')" required class="w-full"/>
            <br>
            <div class="mb-2 text-gray-400 text-sm mt-1">
                {{ __(config('sidecar.translationsPrefix').'saveReportDesc') }}
            </div>
            <x-ui::primary-button :async="true" type="submit" class="mt-2">
                {{ __(config('sidecar.translationsPrefix').'save') }}
            </x-ui::primary-button>
        </form>

    </x-ui::dropdown>
</div>

