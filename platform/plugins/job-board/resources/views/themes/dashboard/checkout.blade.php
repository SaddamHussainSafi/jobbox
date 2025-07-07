@extends(JobBoardHelper::viewPath('dashboard.layouts.master'))

@section('content')
    @include('plugins/payment::partials.form', [
        'action' => route('payments.checkout'),
        'currency' => $package->currency->title
            ? strtoupper($package->currency->title)
            : cms_currency()->getDefaultCurrency()->title,
        'amount' => $package->price,
        'name' => $package->name,
        'returnUrl' => route('public.account.package.subscribe', $package->id),
        'callbackUrl' => route('public.account.package.subscribe.callback', $package->id),
    ])
@endsection
