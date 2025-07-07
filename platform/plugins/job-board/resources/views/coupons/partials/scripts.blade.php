@push('header')
    <script>
        'use strict';

        window.trans = window.trans || {};
        window.coupon = window.coupon || {};

        window.trans.coupon = {{ Js::from(trans('plugins/job-board::coupon')) }}
        window.coupon.currency = '{{ get_application_currency()->symbol }}';
    </script>
@endpush

@push('footer')
    {!! $jsValidator !!}
@endpush
