<x-core::card>
    <x-core::card.header>
        <x-core::card.title>
            {{ trans('plugins/job-board::import.rules') }}
        </x-core::card.title>
    </x-core::card.header>
    <x-core::table class="card-table">
        <x-core::table.header>
            <x-core::table.header.cell>
                {{ trans('plugins/job-board::import.column') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/job-board::import.rules') }}
            </x-core::table.header.cell>
        </x-core::table.header>
        <x-core::table.body>
            @foreach ($rules as $key => $value)
                <x-core::table.body.row>
                    <x-core::table.body.cell>
                        {{ Arr::get($headings, $key) }}
                    </x-core::table.body.cell>
                    <x-core::table.body.cell>
                        {{ $value }}
                    </x-core::table.body.cell>
                </x-core::table.body.row>
            @endforeach
        </x-core::table.body>
    </x-core::table>
</x-core::card>
