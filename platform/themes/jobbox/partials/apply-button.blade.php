@if ($job->canShowApplyJob())
    @php($classButtonApply = $class ?? 'btn btn-apply-now')
    <div class="{{ $wrapClass ?? '' }}">
        @if ($job->is_applied)
            <button class="{{ $classButtonApply }} disabled" disabled>{{ __('Applied') }}</button>
        @elseif (! $job->isJobOpen())
            <button disabled
                style="background-color: #f8d7da;  border: 0; background-image: unset"
                class="{{ $classButtonApply }} text-danger"
            >
                {{ __('Closed') }}
            </button>
        @elseif ($job->apply_url)
            @if ($job->getMetaData('is_direct_redirect', true))
                <a href="{{ $job->apply_url }}" target="_blank">
                    <div class="{{ $classButtonApply }}">{{ __('Apply Now') }}</div>
                </a>
            @else
                <button class="{{ $classButtonApply }}"
                    data-bs-target="#ModalApplyExternalJobForm"
                    data-bs-toggle="modal"
                    data-job-name="{{ $job->name }}"
                    data-job-id="{{ $job->id }}"
                >
                    {{ __('Apply Now') }}
                </button>
            @endif
        @elseif (!auth('account')->check() && !JobBoardHelper::isGuestApplyEnabled())
            <a href="{{ route('public.account.login') }}">
                <div class="{{ $classButtonApply }}">{{ __('Apply Now') }}</div>
            </a>
        @else
            <button class="{{ $classButtonApply }}"
                    data-job-name="{{ $job->name }}"
                    data-job-id="{{ $job->id }}"
                    data-bs-toggle="modal"
                    data-bs-target="#ModalApplyJobForm"
            >
                {{ __('Apply Now') }}
            </button>
        @endif
    </div>
@endif
