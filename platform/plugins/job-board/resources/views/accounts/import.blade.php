@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::form class="form-import-data mb-3" :url="route('accounts.import')" method="post">
        <x-core::card>
            <x-core::card.header>
                <x-core::card.title>
                    {{ trans('plugins/job-board::account.import.name') }}
                </x-core::card.title>
            </x-core::card.header>
            <x-core::card.body>
                <x-core::form.text-input
                    :label="trans('plugins/job-board::import.choose_file')"
                    type="file"
                    name="file"
                    :helper-text="trans('plugins/job-board::import.choose_file_description', ['types' => implode(', ', config('plugins.job-board.general.bulk-import.mimes', []))])"
                />

                <div class="mb-3 text-center p-2 border bg-body text-body">
                    <a
                        href="javascript:void(0)"
                        class="download-template"
                        data-url="{{ route('accounts.download-template') }}"
                        data-extension="csv"
                        data-filename="template_accounts.csv"
                        data-downloading="<i class='fas fa-spinner fa-spin'></i> {{ trans('plugins/job-board::import.downloading') }}"
                    >
                        <x-core::icon name="ti ti-file-type-csv" />
                        {{ trans('plugins/job-board::import.download_csv_file') }}
                    </a>
                    &nbsp; | &nbsp;
                    <a
                        href="javascript:void(0)"
                        class="download-template"
                        data-url="{{ route('accounts.download-template') }}"
                        data-extension="xlsx"
                        data-filename="template_accounts.xlsx"
                        data-downloading="<i class='fas fa-spinner fa-spin'></i> {{ trans('plugins/job-board::import.downloading') }}"
                    >
                        <x-core::icon name="ti ti-file-spreadsheet" />
                        {{ trans('plugins/job-board::import.download_excel_file') }}
                    </a>
                </div>

                <div class="d-grid">
                    <x-core::button
                        type="submit"
                        color="primary"
                    >
                        {{ trans('plugins/job-board::account.import.name') }}
                    </x-core::button>
                </div>
            </x-core::card.body>
        </x-core::card>

        <div class="hidden main-form-message mt-3">
            <div id="imported-message"></div>
            <x-core::card class="bg-danger-lt show-errors hidden" id="failures-list">
                <x-core::card.header>
                    <x-core::card.title class="text-warning">
                        {{ trans('plugins/job-board::import.failures') }}
                    </x-core::card.title>
                </x-core::card.header>
                <x-core::table :hover="false">
                    <x-core::table.header>
                        <x-core::table.header.cell>
                            #{{ trans('plugins/job-board::import.row') }}
                        </x-core::table.header.cell>
                        <x-core::table.header.cell>
                            {{ trans('plugins/job-board::import.attribute') }}
                        </x-core::table.header.cell>
                        <x-core::table.header.cell>
                            {{ trans('plugins/job-board::import.errors') }}
                        </x-core::table.header.cell>
                    </x-core::table.header>
                    <x-core::table.body id="imported-listing"></x-core::table.body>
                </x-core::table>
            </x-core::card>
        </div>
    </x-core::form>

    @include('plugins/job-board::import.partials.template', ['headings' => $headings, 'data' => $accounts])

    @include('plugins/job-board::import.partials.rules', compact('rules', 'headings'))
@stop

@push('footer')
    @include('plugins/job-board::import.partials.failure-template')
@endpush
