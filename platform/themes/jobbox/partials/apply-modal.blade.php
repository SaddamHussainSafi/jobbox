@php
    $isLoggedIn = auth('account')->check();

    $account = null;
    if ($isLoggedIn) {
       $account = auth('account')->user();
    }
@endphp

@if (!$isLoggedIn || ($account && !$account->isEmployer()))
    <div class="modal fade" id="ModalApplyJobForm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content apply-job-form">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body pl-30 pr-30 pt-50">
                    <div class="text-center">
                        <p class="font-sm text-brand-2">{{ __('Job Application') }}</p>
                        <h2 class="mt-10 mb-5 text-brand-1 text-capitalize">{{ __('Start Your Career Today') }}</h2>
                        <p class="font-sm text-muted mb-30">{{ __('Please fill in your information and send it to the employer.') }}</p>
                    </div>
                    {!! Form::open(['route' => 'public.job.apply', 'method' => 'POST', 'files' => true, 'class' => 'job-apply-form text-start mt-20 pb-30']) !!}
                    <div class="text-center mb-4">
                        <h5 class="modal-job-name text-primary"></h5>
                        <input type="hidden" class="modal-job-id" name="job_id" required>
                        <input type="hidden" class="modal-job-name" name="job_name" required>
                        <input type="hidden" name="job_type" value="internal">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="first-name">{{ __('First Name') }} *</label>
                        <input class="form-control" id="first-name" type="text" value="{{ $isLoggedIn ? $account->first_name : '' }}"
                               required="" name="first_name" placeholder="{{ __('Enter your first name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="last-name">{{ __('Last Name') }} *</label>
                        <input class="form-control" id="last-name" type="text" required="" value="{{ $isLoggedIn ? $account->last_name : '' }}" name="last_name" placeholder="{{ __('Enter your last name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">{{ __('Email') }} *</label>
                        <input class="form-control" id="email" type="email" required="" value="{{ $isLoggedIn ? $account->email : '' }}"
                               name="email" placeholder="{{ __('Enter your email example: stevenjob@gmail.com') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="phone-number">{{ __('Phone Number') }} </label>
                        <input class="form-control" id="phone-number" type="text" name="phone" placeholder="{{ __('(+01) 234 567 89') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="message">{{ __('Message') }}</label>
                        <textarea class="form-control" id="message" name="message" placeholder="{{ __('Enter your message') }}" rows="4"></textarea>
                    </div>

                    <div @if (!$isLoggedIn || empty($account->resume)) class="mb-4" @endif>
                        <label class="form-label" for="resume_apply_now">{{ $isLoggedIn && !empty($account->resume) ? __('Resume Upload (optional)') : __('Resume Upload') }}</label>
                        <input type="file" name="resume" class="form-control" id="resume_apply_now">
                    </div>

                    @if ($isLoggedIn && !empty($account->resume))
                        <div class="mb-4 mt-2">
                            <p class="job-apply-resume-info"><i class="mdi mdi-information"></i> {!! BaseHelper::clean(__('Your current resume :resume. Just upload a new resume if you want to change it.', ['resume' => Html::link(RvMedia::url($account->resume), $account->resume, ['target' => '_blank'])->toHtml()])) !!}</p>
                        </div>
                    @endif

                    <div @if (!$isLoggedIn || empty($account->cover_letter)) class="mb-4" @endif>
                        <label class="form-label" for="cover_letter_apply_now">{{ $isLoggedIn && !empty($account->cover_letter) ? __('Cover Letter (optional)') : __('Cover Letter Upload') }}</label>
                        <input type="file" name="cover_letter" class="form-control" id="cover_letter_apply_now">
                    </div>

                    @if (!$isLoggedIn && !empty($account->cover_letter))
                        <div class="mb-4 mt-2">
                            <p class="job-apply-resume-info"><i class="mdi mdi-information"></i> {!! BaseHelper::clean(__('Your current cover letter :cover_letter. Just upload a new cover letter if you want to change it.', ['cover_letter' => Html::link(RvMedia::url($account->cover_letter), $account->cover_letter, ['target' => '_blank'])->toHtml()])) !!}</p>
                        </div>
                    @endif

                        @if (is_plugin_active('captcha') && setting('enable_captcha') && setting('job_board_enable_recaptcha_in_apply_job', 0))
                            <div class="mb-4">
                                {!! Captcha::display() !!}
                            </div>
                        @endif

                        <div class="mb-3">
                            <button class="btn btn-default hover-up w-100" type="submit">{{ __('Apply Now') }}</button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div><!-- END APPLY MODAL -->

    <!-- START APPLY MODAL -->
    <div class="modal fade" id="ModalApplyExternalJobForm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content apply-job-form">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body pl-30 pr-30 pt-50">
                    <div class="text-center">
                        <p class="font-sm text-brand-2">{{ __('Job Application') }}</p>
                        <h2 class="mt-10 mb-5 text-brand-1 text-capitalize">{{ __('Start Your Career Today') }}</h2>
                        <p class="font-sm text-muted mb-30">{{ __('Please fill in your information and send it to the employer.') }}</p>
                    </div>
                    {!! Form::open(['route' => 'public.job.apply', 'method' => 'POST', 'class' => 'job-apply-form text-start mt-20 pb-30']) !!}
                    <div class="text-center mb-4">
                        <h5 class="modal-job-name text-primary"></h5>
                        <input type="hidden" class="modal-job-id" name="job_id" required>
                        <input type="hidden" class="modal-job-name" name="job_name" required>
                        <input type="hidden" name="job_type" value="external">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="external-first-name">{{ __('First Name') }} *</label>
                        <input class="form-control" id="external-first-name" type="text" value="{{ $isLoggedIn ? $account->first_name : '' }}"
                               required="" name="first_name" placeholder="{{ __('Enter your first name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="external-last-name">{{ __('Last Name') }} *</label>
                        <input class="form-control" id="external-last-name" type="text" required="" value="{{ $isLoggedIn ? $account->last_name : '' }}" name="last_name" placeholder="{{ __('Enter your last name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="external-email">{{ __('Email') }} *</label>
                        <input class="form-control" id="external-email" type="email" required="" value="{{ $isLoggedIn ? $account->email : '' }}"
                               name="email" placeholder="{{ __('Enter your email example: stevenjob@gmail.com') }}">
                    </div>

                    @if (is_plugin_active('captcha') && setting('enable_captcha') && setting('job_board_enable_recaptcha_in_apply_job', 0))
                        <div class="mb-4">
                            {!! Captcha::display() !!}
                        </div>
                    @endif

                    <div class="mb-3">
                        <button class="btn btn-default hover-up w-100" type="submit">{{ __('Go To Job Apply Page') }}</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endif
