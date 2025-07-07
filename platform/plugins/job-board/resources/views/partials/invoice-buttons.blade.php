<div class="btn-list mt-5">
    <x-core::button
        tag="a"
        :href="route('invoice.generate-invoice', ['id' => $invoice->id, 'type' => 'print'])"
        target="_blank"
    >
        {{ trans('plugins/job-board::invoice.print') }}
    </x-core::button>

    <x-core::button
        tag="a"
        :href="route('invoice.generate-invoice', ['id' => $invoice->id, 'type' => 'download'])"
        target="_blank"
    >
        {{ trans('plugins/job-board::invoice.download') }}
    </x-core::button>
</div>
