@extends(JobBoardHelper::viewPath('dashboard.layouts.master'))

@section('content')
    @php
        $invoice = $invoice->load('payment');
        $payment = $invoice->payment;
        $currency = $payment ? \Botble\JobBoard\Models\Currency::query()->where('title', strtoupper($payment->currency))->first() : null;
    @endphp

    <x-core::card size="lg">
        <x-core::card.body>
            <div class="row">
                <div class="col-md-6">
                    <img
                        src="{{ RvMedia::getImageUrl($invoice->company_logo) }}"
                        alt="{{ $invoice->company_name }}"
                        style="max-height: 150px;"
                    >
                </div>
                <div class="col-md-6 text-end">
                    <p class="h3">{{ trans('plugins/job-board::invoice.heading') }}</p>
                    <p class="mb-1">{{ $invoice->customer_name }}</p>
                    <p class="mb-1">{{ $invoice->customer_email }}</p>
                    <p class="mb-1">{{ $invoice->customer_phone }}</p>
                    <p class="mb-1">{{ $invoice->customer_address }}</p>
                    @if ($invoice->tax_id)
                        <p class="mb-1">
                            <strong>{{ trans('plugins/job-board::invoice.detail.tax_id') }}:</strong>
                            {{ $invoice->tax_id }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="my-5">
                <div class="row">
                    <div class="col-lg-4">
                        <strong class="text-brand">{{ trans('plugins/job-board::invoice.detail.code') }}:</strong>
                        #{{ $invoice->code }}
                    </div>
                    <div class="col-lg-4">
                        <strong class="text-brand">{{ trans('plugins/job-board::invoice.detail.issue_at') }}:</strong>
                        {{ $invoice->created_at->translatedFormat('j F, Y') }}
                    </div>
                    <div class="col-lg-4">
                        <strong class="text-brand">{{ trans('plugins/job-board::invoice.payment_method') }}:</strong>
                        {{ $invoice->payment->payment_channel->label() }}
                    </div>
                </div>
            </div>

            <x-core::table class="table-transparent" :striped="false" :hover="false">
                <x-core::table.header>
                    <x-core::table.header.cell>
                        {{ trans('plugins/job-board::invoice.detail.description') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>
                        {{ trans('plugins/job-board::invoice.detail.qty') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell class="text-center">
                        {{ trans('plugins/job-board::invoice.total_amount') }}
                    </x-core::table.header.cell>
                </x-core::table.header>
                <x-core::table.body>
                    @foreach ($invoice->items as $item)
                        <x-core::table.body.row>
                            <x-core::table.body.cell style="width: 70%">
                                <p class="mb-0">{{ $item->name }}</p>
                                @if ($item->description)
                                    <small>{{ $item->description }}</small>
                                @endif
                            </x-core::table.body.cell>
                            <x-core::table.body.cell style="width: 5%">{{ $item->qty }}</x-core::table.body.cell>
                            <x-core::table.body.cell class="text-center" style="width: 25%">
                                {{ format_price($item->sub_total, $currency) }}
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endforeach

                    <x-core::table.body.row>
                        <x-core::table.body.cell class="text-end" colspan="2">
                            {{ trans('plugins/job-board::invoice.detail.sub_total') }}:
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-center">
                            <strong>{{ format_price($invoice->sub_total, $currency) }}</strong>
                        </x-core::table.body.cell>
                    </x-core::table.body.row>
                    @if ($invoice->tax_amount > 0)
                        <x-core::table.body.row>
                            <x-core::table.body.cell class="text-end" colspan="2">
                                {{ trans('plugins/job-board::invoice.detail.tax') }}:
                            </x-core::table.body.cell>
                            <x-core::table.body.cell class="text-center">
                                <strong>{{ format_price($invoice->tax_amount, $currency) }}</strong>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endif
                    @if ($invoice->shipping_amount > 0)
                        <x-core::table.body.row>
                            <x-core::table.body.cell class="text-end" colspan="2">
                                {{ trans('plugins/job-board::invoice.detail.shipping_fee') }}:
                            </x-core::table.body.cell>
                            <x-core::table.body.cell class="text-center">
                                <strong>{{ format_price($invoice->shipping_amount, $currency) }}</strong>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endif
                    @if ($invoice->discount_amount > 0)
                        <x-core::table.body.row>
                            <x-core::table.body.cell class="text-end" colspan="2">
                                {{ trans('plugins/job-board::invoice.detail.discount') }}:
                            </x-core::table.body.cell>
                            <x-core::table.body.cell class="text-center">
                                <strong>{{ format_price($invoice->discount_amount, $currency) }}</strong>
                                <p>({{ $invoice->coupon_code }})</p>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endif
                    <x-core::table.body.row>
                        <x-core::table.body.cell class="text-end" colspan="2">
                            {{ trans('plugins/job-board::invoice.detail.grand_total') }}:
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-center">
                            <strong>{{ format_price($invoice->amount, $currency) }}</strong>
                        </x-core::table.body.cell>
                    </x-core::table.body.row>

                    <x-core::table.body.row>
                        <x-core::table.body.cell class="text-end text-danger fw-bold" colspan="2">
                            {{ trans('plugins/job-board::invoice.total_amount') }}:
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-center">
                            <strong>{{ format_price($invoice->amount, $currency) }}</strong>
                        </x-core::table.body.cell>
                    </x-core::table.body.row>
                </x-core::table.body>
            </x-core::table>

            <div class="mt-5">
                <div class="d-flex gap-1">
                    <dt>{{ trans('plugins/job-board::invoice.detail.issue_at') }}:</dt>
                    <dd>{{ $invoice->created_at->format('j F, Y') }}</dd>
                </div>
                <div class="d-flex gap-1">
                    <dt>{{ trans('plugins/job-board::invoice.detail.invoice_to') }}:</dt>
                    <dd>{{ $invoice->company_name }}</dd>
                </div>
                @if ($invoice->tax_id)
                    <div class="d-flex gap-1">
                        <dt>{{ trans('plugins/job-board::invoice.detail.tax_id') }}:</dt>
                        <dd>{{ $invoice->tax_id }}</dd>
                    </div>
                @endif
            </div>
        </x-core::card.body>

        <x-core::card.footer>
            <div class="btn-list justify-content-end">
                <x-core::button
                    tag="a"
                    :href="route('public.account.invoices.generate_invoice', ['invoice' => $invoice->id, 'type' => 'print'])"
                    target="_blank"
                    icon="ti ti-printer"
                >
                    {{ trans('plugins/job-board::invoice.print') }}
                </x-core::button>
                <x-core::button
                    tag="a"
                    :href="route('public.account.invoices.generate_invoice', ['invoice' => $invoice->id, 'type' => 'download'])"
                    target="_blank"
                    icon="ti ti-download"
                >
                    {{ trans('plugins/job-board::invoice.download') }}
                </x-core::button>
            </div>
        </x-core::card.footer>
    </x-core::card>
@endsection
