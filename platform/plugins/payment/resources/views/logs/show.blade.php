@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('plugins/payment::payment.payment_log.view', ['id' => $paymentLog->getKey()]) }}
            </x-core::card.title>
        </x-core::card.header>
        <x-core::card.body>
            <x-core::datagrid>
                <x-core::datagrid.item>
                    <x-slot:title>{{ trans('plugins/payment::payment.method_name') }}</x-slot:title>
                    {{ $paymentLog->payment_method->label() }}
                </x-core::datagrid.item>
                <x-core::datagrid.item>
                    <x-slot:title>{{ trans('plugins/payment::payment.payment_log.ip_address') }}</x-slot:title>
                    {{ $paymentLog->ip_address }}
                </x-core::datagrid.item>
                <x-core::datagrid.item>
                    <x-slot:title>{{ trans('plugins/payment::payment.created_at') }}</x-slot:title>
                    {{ $paymentLog->created_at }}
                </x-core::datagrid.item>
            </x-core::datagrid>

            <x-core::tab class="mt-3">
                <x-core::tab.item
                    :is-active="true"
                    id="request"
                    label="{{ trans('plugins/payment::payment.payment_log.request') }}"
                />
                <x-core::tab.item
                    id="response"
                    label="{{ trans('plugins/payment::payment.payment_log.response') }}"
                />
            </x-core::tab>

            <x-core::tab.content>
                <x-core::tab.pane
                    id="request"
                    :is-active="true"
                >
                    <pre>{{ json_encode($paymentLog->request, JSON_PRETTY_PRINT) }}</pre>
                </x-core::tab.pane>
                <x-core::tab.pane id="response">
                    <pre>{{ json_encode($paymentLog->response, JSON_PRETTY_PRINT) }}</pre>
                </x-core::tab.pane>
            </x-core::tab.content>
        </x-core::card.body>
    </x-core::card>
@stop
