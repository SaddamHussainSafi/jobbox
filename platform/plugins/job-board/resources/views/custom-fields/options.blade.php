<div id="custom-field-options">
    <x-core::table class="card-table">
        <x-core::table.header>
            <x-core::table.header.cell>
                #
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/job-board::custom-fields.option.label') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell colspan="2">
                {{ trans('plugins/job-board::custom-fields.option.value') }}
            </x-core::table.header.cell>
        </x-core::table.header>
        <x-core::table.body class="option-sortable">
            <input name="is_global" type="hidden" value="1" />

            @if ($options->count())
                @foreach ($options as $key => $value)
                    <x-core::table.body.row
                        class="option-row ui-state-default"
                        data-index="{{ $value->id }}"
                    >
                        <input
                            name="options[{{ $key }}][id]"
                            type="hidden"
                            value="{{ $value->id }}"
                        >
                        <input
                            name="options[{{ $key }}][order]"
                            type="hidden"
                            value="{{ $value->order !== 999 ? $value->order : $key }}"
                        >
                        <x-core::table.body.cell class="text-center">
                            <x-core::icon name="ti ti-arrows-sort" />
                        </x-core::table.body.cell>
                        <x-core::table.body.cell>
                            <input
                                class="form-control option-label"
                                name="options[{{ $key }}][label]"
                                type="text"
                                value="{{ $value->label }}"
                                placeholder="{{ trans('plugins/job-board::custom-fields.option.label') }}"
                            />
                        </x-core::table.body.cell>
                        <x-core::table.body.cell>
                            <input
                                class="form-control option-value"
                                name="options[{{ $key }}][value]"
                                type="text"
                                value="{{ $value->value }}"
                                placeholder="{{ trans('plugins/job-board::custom-fields.option.value') }}"
                            />
                        </x-core::table.body.cell>
                        <x-core::table.body.cell style="width: 50px">
                            <x-core::button
                                class="remove-row"
                                data-index="0"
                                icon="ti ti-trash"
                                :icon-only="true"
                            />
                        </x-core::table.body.cell>
                    </x-core::table.body.row>
                @endforeach
            @else
                <x-core::table.body.row class="option-row" data-index="0">
                    <x-core::table.body.cell class="text-center">
                        <i class="fa fa-sort"></i>
                    </x-core::table.body.cell>
                    <x-core::table.body.cell>
                        <input
                            class="form-control option-label"
                            name="options[0][label]"
                            type="text"
                            placeholder="{{ trans('plugins/job-board::custom-fields.option.label') }}"
                        />
                    </x-core::table.body.cell>
                    <x-core::table.body.cell>
                        <input
                            class="form-control option-value"
                            name="options[0][value]"
                            type="text"
                            placeholder="{{ trans('plugins/job-board::custom-fields.option.value') }}"
                        />
                    </x-core::table.body.cell>
                    <x-core::table.body.cell style="width: 50px">
                        <x-core::button
                            class="remove-row"
                            data-index="0"
                            icon="ti ti-trash"
                            :icon-only="true"
                        />
                    </x-core::table.body.cell>
                </x-core::table.body.row>
            @endif
        </x-core::table.body>
    </x-core::table>
</div>
