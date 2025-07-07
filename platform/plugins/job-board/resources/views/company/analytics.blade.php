@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::stat-widget class="mb-3">
        <x-core::stat-widget.item
            :label="__('Views')"
            :value="$company->views"
            icon="ti ti-eye"
            color="primary"
        />

        <x-core::stat-widget.item
            :label="__('Jobs')"
            :value="$company->jobs_count"
            icon="ti ti-briefcase"
            color="success"
        />
    </x-core::stat-widget>

    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ __('Jobs') }}
            </x-core::card.title>
        </x-core::card.header>
        @if ($company->jobs->isNotEmpty())
            <x-core::table>
                <x-core::table.header>
                    <x-core::table.header.cell>
                        #</x-core::table.header.cell>
                    <x-core::table.header.cell>
                        {{ __('Title') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>
                        {{ __('Applied') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>
                        {{ __('Views') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>
                        {{ __('Created At') }}
                    </x-core::table.header.cell>
                </x-core::table.header>
                <x-core::table.body>
                    @foreach ($company->jobs as $item)
                        <x-core::table.body.row>
                            <x-core::table.body.cell>
                                {{ $loop->iteration }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <a href="{{ $item->url }}" target="_blank">{{ $item->name }}</a>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                {{ $item->number_of_applied }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                {{ $item->views }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                {{ BaseHelper::formatDate($item->created_at) }}
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endforeach
                </x-core::table.body>
            </x-core::table>
        @else
            <x-core::empty-state
                :title="trans('No data')"
                :subtitle="trans('There are no data to display.')"
            />
        @endif
    </x-core::card>
@stop
