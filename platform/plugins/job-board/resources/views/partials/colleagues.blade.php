<div class="row">
    <div class="col-md-6">
        <div id="app-job-board">
            <employer-colleagues-component
                :data="{{ json_encode($model ? $model->employer_colleagues : []) }}"
                v-slot="{ items, addRow, deleteRow }"
            >
                <div class="mb-3">
                    <div class="mb-2" v-for="(item, index) in items">
                        <div class="row g-2">
                            <div class="col">
                                <input
                                    type="email"
                                    name="employer_colleagues[]"
                                    v-model="item.value"
                                    class="form-control"
                                    placeholder="{{ __('Email') }}"
                                />
                            </div>

                            <div class="col-auto">
                                <x-core::button
                                    @click="deleteRow(index)"
                                    icon="ti ti-trash"
                                    :icon-only="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <a href="javascript:void(0)" role="button" @click="addRow">
                    {{ __('Add new') }}
                </a>
            </employer-colleagues-component>
        </div>
    </div>
</div>

@include('plugins/job-board::partials.add-company')
