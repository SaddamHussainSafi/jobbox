<x-core::table>
    <x-core::table.header>
        <x-core::table.header.cell>
            #
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.languages.name') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.languages.code') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.languages.level') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.languages.is_native') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell class="text-end">
            {{ trans('plugins/job-board::account.table.action') }}
        </x-core::table.header.cell>
    </x-core::table.header>
    <x-core::table.body>
        @forelse ($languages as $language)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ $language->getKey() }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $language->language_name }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $language->language }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $language->languageLevel->name }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $language->is_native ? trans('core/base::base.yes') : trans('core/base::base.no') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell class="text-end">
                    <span data-bs-toggle="tooltip" title="{{ trans('plugins/job-board::account.action_table.edit') }}">
                        <x-core::button
                            tag="a"
                            color="primary"
                            size="sm"
                            data-bs-toggle="modal"
                            data-bs-target="#edit-account-entity-modal"
                            :data-modal-title="trans('plugins/job-board::account.edit_language')"
                            data-table="#languages-table"
                            :href="route('accounts.languages.edit-modal', [$language->id, $language->account_id])"
                            icon="ti ti-edit"
                            :icon-only="true"
                        />
                    </span>
                    <span data-bs-toggle="tooltip" title="{{ trans('plugins/job-board::account.action_table.delete') }}">
                        <x-core::button
                            tag="a"
                            color="danger"
                            size="sm"
                            :href="route('accounts.languages.destroy', $language->id)"
                            data-bs-toggle="modal"
                            data-table="#languages-table"
                            data-bs-target="#modal-confirm-delete"
                            icon="ti ti-trash"
                            :icon-only="true"
                        />
                    </span>
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @empty
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="6" class="text-center text-muted">
                    {{ trans('plugins/job-board::account.no_languages') }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endforelse
    </x-core::table.body>
</x-core::table>
