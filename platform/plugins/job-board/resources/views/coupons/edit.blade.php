@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::form :url="route('coupons.edit', $coupon)" method="post" class="coupon-form">
        @csrf

        @include('plugins/job-board::coupons.partials.coupon-form')
    </x-core::form>
@stop
