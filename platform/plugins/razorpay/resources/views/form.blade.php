<form action="{{ $action }}" method="post">
    @foreach($data as $key => $value)
        @if (is_array($value))
            @foreach($value as $valueKey => $item)
                <input type="hidden" name="{{ $key }}[{{ $valueKey }}]" value="{{ $item }}"/>
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}"/>
        @endif
    @endforeach
    <button type="submit" style="display: none">{{ __('Submit') }}</button>
</form>

<p>{{ __('Redirecting to Razorpay...') }}</p>

<script>
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('form').submit();
    });
</script>
