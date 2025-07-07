@php
    $isDefaultLanguage = ! defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')
        || !request()->input('ref_lang')
        || request()->input('ref_lang') == Language::getDefaultLocaleCode();
@endphp

@push('footer')
    <script>
        window.jobCustomFields = {{ Js::from([
            'ajax' => $ajax,
            'customFields' => $model ? $model->custom_fields_array : [],
        ]) }}
    </script>
@endpush

<div class="job-custom-fields-wrap">
    <div id="custom-field-list"></div>

    @if ($isDefaultLanguage)
        <a
            href="javascript:void(0);"
            data-bs-toggle="modal"
            data-bs-target="#custom-fields-modal"
            role="button"
        >
            {{ trans('plugins/job-board::custom-fields.add_a_new_custom_field') }}
        </a>
    @endif
</div>

@push('footer')
    <x-core::modal
        id="custom-fields-modal"
        :title="trans('plugins/job-board::custom-fields.modal.heading')"
        :button-label="trans('plugins/job-board::custom-fields.modal.button')"
        button-id="add-new"
    >
        <x-core::form.select
            :label="trans('plugins/job-board::custom-fields.modal.select_field')"
            name="custom-field-id"
        >
            <option value="">{{ trans('plugins/job-board::custom-fields.add_a_new_custom_field') }}</option>
            @foreach ($customFields as $customField)
                <option value="{{ $customField->id }}">{{ $customField->name }}
                    ({{ is_string($customField->type) ? $customField->type : $customField->type->label() }})
                </option>
            @endforeach
        </x-core::form.select>
    </x-core::modal>

    <x-core::custom-template id="custom-field-dropdown-template">
        <x-core::form.fieldset class="position-relative" data-index="__index__">
            <div class="row">
                <input type="hidden" name="custom_fields[__index__][id]" value="__id__">
                <input type="hidden" name="custom_fields[__index__][custom_field_id]" value="__custom_field_id__">
                <div class="col">
                    <x-core::form.label>{{ trans('core/base::forms.name') }}</x-core::form.label>
                    <input type="text" name="custom_fields[__index__][name]" class="form-control custom-field-name __custom_field_input_class__" value="__name__" placeholder="{{ trans('core/base::forms.name') }}">
                </div>
                <div class="col">
                    <x-core::form.label>{{ trans('core/base::forms.value') }}</x-core::form.label>
                    <select name="custom_fields[__index__][value]" class="form-select custom-field-value" id="custom-field-options">__selectOptions__</select>
                </div>
                @if ($isDefaultLanguage)
                    <div>
                        <x-core::button
                            data-index="__index__"
                            class="position-absolute remove-custom-field"
                            icon="ti ti-trash"
                            :icon-only="true"
                            size="sm"
                            style="top: 0.5rem; inset-inline-end: 0.5rem"
                        />
                    </div>
                @endif
            </div>
        </x-core::form.fieldset>
    </x-core::custom-template>

    <x-core::custom-template id="custom-field-template">
        <x-core::form.fieldset class="position-relative" data-index="__index__">
            <div class="row">
                <input type="hidden" name="custom_fields[__index__][id]" value="__id__">
                <input type="hidden" name="custom_fields[__index__][custom_field_id]" value="__custom_field_id__">
                <div class="col">
                    <x-core::form.label>{{ trans('core/base::forms.name') }}</x-core::form.label>
                    <input type="text" name="custom_fields[__index__][name]" class="form-control custom-field-name __custom_field_input_class__" value="__name__" placeholder="{{ trans('core/base::forms.name') }}">
                </div>
                <div class="col">
                    <x-core::form.label>{{ trans('core/base::forms.value') }}</x-core::form.label>
                    <input type="text" name="custom_fields[__index__][value]" class="form-control custom-field-value" value="__value__" placeholder="{{ trans('core/base::forms.value') }}">
                </div>
                @if ($isDefaultLanguage)
                    <div>
                        <x-core::button
                            data-index="__index__"
                            class="position-absolute remove-custom-field"
                            icon="ti ti-trash"
                            :icon-only="true"
                            size="sm"
                            style="top: 0.5rem; inset-inline-end: 0.5rem"
                        />
                    </div>
                @endif
            </div>
        </x-core::form.fieldset>
    </x-core::custom-template>
@endpush
