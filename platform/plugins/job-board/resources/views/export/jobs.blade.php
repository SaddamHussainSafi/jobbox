@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('plugins/job-board::export.jobs.name') }}
            </x-core::card.title>
        </x-core::card.header>

        <x-core::card.body>
            <div class="row justify-content-center text-center py-5">
                <div class="col-md-6">
                    <h3>{{ trans('plugins/job-board::export.jobs.total_jobs') }}</h3>
                    <h2 class="fs-1 text-primary fw-bold">{{ $totalJobs }}</h2>
                </div>
            </div>

            <x-core::button
                tag="a"
                data-bb-toggle="export-data"
                class="w-100"
                color="primary"
                :data-loading-text="trans('plugins/job-board::export.exporting')"
                data-filename="export_jobs.csv"
                :href="route('export-jobs.index.post')"
            >
                {{ trans('plugins/job-board::export.start_export') }}
            </x-core::button>
        </x-core::card.body>
    </x-core::card>
@stop
