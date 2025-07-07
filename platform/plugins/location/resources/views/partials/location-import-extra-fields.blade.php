<x-core::form-group>
    <x-core::form.checkbox
        name="skip_existing_records"
        :label="trans('plugins/location::bulk-import.skip_existing_records')"
        :helper-text="trans('plugins/location::bulk-import.skip_existing_records_description')"
        value="1"
    />
</x-core::form-group>
