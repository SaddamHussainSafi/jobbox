@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::form :url="route('coupons.create')" method="post" class="coupon-form">
        @csrf

        @include('plugins/job-board::coupons.partials.coupon-form')
    </x-core::form>
@stop
