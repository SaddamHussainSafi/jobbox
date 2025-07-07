<x-core::form :url="route('accounts.credits.add', $account->id)" method="post">
    <x-core::form.text-input
        :label="trans('plugins/job-board::account.form.number_of_credits')"
        name="credits"
        type="number"
        value="0"
        :placeholder="trans('plugins/job-board::account.form.number_of_credits')"
    />

    <x-core::form.textarea
        :label="trans('plugins/job-board::account.form.description')"
        name="description"
        :placeholder="trans('plugins/job-board::account.form.description')"
        rows="5"
    />
</x-core::form>
