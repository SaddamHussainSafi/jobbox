@if($transactions->isNotEmpty())
    <x-core::step :vertical="true">
        @foreach($transactions as $transaction)
            <x-core::step.item @class(['user-action' => $transaction->account_id])>
                <div @class(['h4 m-0', 'cursor-pointer' => $transaction->description])>
                    {!! BaseHelper::clean($transaction->getDescription()) !!}
                </div>
                <div class="text-secondary">{{ $transaction->created_at }}</div>

                @if($transaction->description)
                    <x-core::form.fieldset class="mt-2 py-2" style="display: none;">
                        {{ $transaction->description }}
                    </x-core::form.fieldset>
                @endif
            </x-core::step.item>
        @endforeach
    </x-core::step>
@else
    <p class="mb-0 text-muted text-center">{{ trans('plugins/job-board::account.no_transactions') }}</p>
@endif
