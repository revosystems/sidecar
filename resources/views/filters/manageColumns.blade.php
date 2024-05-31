<div class="hidden sm:block">
    <x-ui::dropdown>
        <x-slot name="trigger">
            <x-ui::secondary-button icon="columns">
                {{ __(config('sidecar.translationsPrefix').'columns') }}
            </x-ui::secondary-button>
        </x-slot>

        <div class="min-w-56">
            <div class="font-bold pb-2 border-b">
                {{ __(config('sidecar.translationsPrefix').'columns') }}
            </div>

            <div class="mt-2 flex flex-col gap-1">
                @foreach($fields as $field)
                    <div class="flex gap-2 items-center">
                        <x-ui::forms.check name="columns[]" :value="$field->field" :on="$report->columns->isEmpty() || $report->columns->contains($field->field)"/>
                        <div>{{ $field->getTitle() }}</div>
                    </div>
                @endforeach
            </div>
            <div class="mt-2">
                <x-ui::primary-button type="submit" :async="true">
                    {{ __(config('sidecar.translationsPrefix').'apply') }}
                </x-ui::primary-button>
            </div>
        </div>
    </x-ui::dropdown>
</div>