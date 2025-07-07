<x-core::table>
    <x-core::table.header>
        <x-core::table.header.cell>
            #
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.experiences.company') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/job-board::account.table.experiences.position') }}
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
        @forelse ($experiences as $experience)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ $loop->iteration }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $experience->company }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $experience->position }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $experience->started_at->format('Y-m-d') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $experience->ended_at ? $experience->ended_at->format('Y-m-d') : trans('plugins/job-board::account.now') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell class="text-end">
                    <span data-bs-toggle="tooltip" title="{{ trans('plugins/job-board::account.action_table.edit') }}">
                        <x-core::button
                            tag="a"
                            color="primary"
                            size="sm"
                            data-bs-toggle="modal"
                            data-bs-target="#edit-account-entity-modal"
                            :data-modal-title="trans('plugins/job-board::account.edit_experience')"
                            data-table="#experiences-table"
                            :href="route('accounts.experiences.edit-modal', [$experience->id, $experience->account_id])"
                            icon="ti ti-edit"
                            :icon-only="true"
                        />
                    </span>
                    <span data-bs-toggle="tooltip" title="{{ trans('plugins/job-board::account.action_table.delete') }}">
                        <x-core::button
                            tag="a"
                            color="danger"
                            size="sm"
                            :href="route('accounts.experiences.destroy', $experience->id)"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-confirm-delete"
                            data-table="#experiences-table"
                            icon="ti ti-trash"
                            :icon-only="true"
                        />
                    </span>
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @empty
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="6" class="text-center text-muted">
                    {{ trans('plugins/job-board::account.no_experience') }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endforelse
    </x-core::table.body>
</x-core::table>
