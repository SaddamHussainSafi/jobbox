<x-core::table>
    <x-core::table.header>
        <x-core::table.header.cell>
            #
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.educations.school') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.educations.specialized') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.started_at') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.ended_at') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell class="text-end">
            {{ trans('plugins/job-board::account.table.action') }}
        </x-core::table.header.cell>
    </x-core::table.header>
    <x-core::table.body>
        @forelse ($educations as $education)
            <x-core::table.body.row>
                <x-core::table.body.cell scope="row">
                    {{ $loop->iteration }}
                </x-core::table.body.cell>
                <x-core::table.body.cell class="text-start">
                    {{ $education->school }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $education->specialized }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $education->started_at->format('Y-m-d') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $education->ended_at ? $education->ended_at->format('Y-m-d') : trans('plugins/job-board::account.now') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell class="text-end">
                    <span data-bs-toggle="tooltip" title="{{ trans('plugins/job-board::account.action_table.edit') }}">
                        <x-core::button
                            tag="a"
                            color="primary"
                            size="sm"
                            data-bs-toggle="modal"
                            data-bs-target="#edit-account-entity-modal"
                            :data-modal-title="trans('plugins/job-board::account.edit_education')"
                            data-table="#educations-table"
                            :href="route('accounts.educations.edit-modal', [$education->id, $education->account_id])"
                            icon="ti ti-edit"
                            :icon-only="true"
                        />
                    </span>
                    <x-core::button
                        tag="a"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-confirm-delete"
                        color="danger"
                        size="sm"
                        :href="route('accounts.educations.destroy', $education->id)"
                        data-table="#educations-table"
                        data-bs-original-title="{{ trans('plugins/job-board::account.action_table.delete') }}"
                        icon="ti ti-trash"
                        :icon-only="true"
                    />
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @empty
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="6" class="text-center text-muted">
                    {{ trans('plugins/job-board::account.no_education') }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endforelse
    </x-core::table.body>
</x-core::table>
