@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('plugins/job-board::account.export.name') }}
            </x-core::card.title>
        </x-core::card.header>

        <x-core::card.body>
            <div class="row justify-content-center text-center py-5">
                <div class="col-md-6">
                    <h3>{{ trans('plugins/job-board::account.export.total') }}</h3>
                    <h2 class="fs-1 text-primary fw-bold">{{ $total }}</h2>
                </div>
            </div>

            <x-core::button
                tag="a"
                data-bb-toggle="export-data"
                class="w-100"
                color="primary"
                :data-loading-text="trans('plugins/job-board::export.exporting')"
                data-filename="export_accounts.csv"
                :href="route('accounts.export')"
            >
                {{ trans('plugins/job-board::export.start_export') }}
            </x-core::button>
        </x-core::card.body>
    </x-core::card>
@endsection
