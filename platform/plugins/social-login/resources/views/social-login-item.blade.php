<li>
    <a
        class="{{ $social }}"
        data-bs-toggle="tooltip"
        data-bs-title="{{ $label = trans('plugins/social-login::social-login.sign_in_with', ['provider' => trans('plugins/social-login::social-login.socials.' . $social)]) }}"
        title="{{ $label }}"
        href="{{ $url }}"
    >
        @php
            $social = $social === 'linkedin-openid' ? 'linkedin' : $social;
        @endphp

        <x-core::icon name="ti ti-brand-{{ $social }}" />
        <span>{{ $label }}</span>
    </a>
</li>
