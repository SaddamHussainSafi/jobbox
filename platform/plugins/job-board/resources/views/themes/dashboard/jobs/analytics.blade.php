@extends(JobBoardHelper::viewPath('dashboard.layouts.master'))

@section('content')
    <x-core::stat-widget class="mb-3">
        <x-core::stat-widget.item
            :label="__('Total views')"
            :value="number_format($job->views)"
            icon="ti ti-eye"
            color="primary"
        />

        <x-core::stat-widget.item
            :label="__('Views today')"
            :value="number_format($viewsToday)"
            icon="ti ti-eye"
            color="success"
        />

        <x-core::stat-widget.item
            :label="__('Number of favorites')"
            :value="number_format($numberSaved)"
            icon="ti ti-heart"
            color="danger"
        />

        <x-core::stat-widget.item
            :label="__('Applicants')"
            :value="number_format($applicants)"
            icon="ti ti-users-group"
            color="info"
        />
    </x-core::stat-widget>

    <div class="row row-cards">
        <div class="col-md-6">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ __('Top Referrers') }}
                    </x-core::card.title>
                </x-core::card.header>
                @if($referrers->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <x-core::table.header>
                                <x-core::table.header.cell>
                                    #
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ __('URL') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ __('Views') }}
                                </x-core::table.header.cell>
                            </x-core::table.header>
                            <tbody>
                            @foreach ($referrers as $referrer)
                                <x-core::table.body.row>
                                    <x-core::table.body.cell>
                                        {{ $loop->iteration }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ $referrer->referer }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ $referrer->total }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-core::empty-state
                        :title="trans('No data')"
                        :subtitle="trans('There are no data to display.')"
                    />
                @endif
            </x-core::card>
        </div>

        <div class="col-md-6">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ __('Top Countries') }}
                    </x-core::card.title>
                </x-core::card.header>
                @if($countries->isNotEmpty())
                    <div class="table-responsive">
                        <x-core::table>
                            <x-core::table.header>
                                <x-core::table.header.cell>
                                    #
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ __('Country') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ __('Views') }}
                                </x-core::table.header.cell>
                            </x-core::table.header>
                            <tbody>
                            @foreach ($countries as $country)
                                <x-core::table.body.row>
                                    <x-core::table.body.cell>
                                        {{ $loop->iteration }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ $country->country_full }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ $country->total }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                            @endforeach
                            </tbody>
                        </x-core::table>
                    </div>
                @else
                    <x-core::empty-state
                        :title="trans('No data')"
                        :subtitle="trans('There are no data to display.')"
                    />
                @endif
            </x-core::card>
        </div>
    </div>
@stop
