@push('footer')
    <x-core::modal
        id="add-company-modal"
        :title="__('Add new company')"
        :has-form="true"
    >
        <x-core::form.label>{{ trans('core/base::forms.name') }}</x-core::form.label>
        <x-core::form.text-input
            name="name"
            type="text"
            id="company_name"
            :placeholder="trans('core/base::forms.name')"
        >
            <x-slot:append>
                <x-core::button
                    type="submit"
                    color="primary"
                    id="btn-add-company"
                >
                    {{ __('Save') }}
                </x-core::button>
            </x-slot:append>
        </x-core::form.text-input>
        <div class="modal-notice"></div>
    </x-core::modal>
@endpush
