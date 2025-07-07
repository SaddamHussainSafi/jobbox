@extends(Theme::getThemeNamespace('views.job-board.account.partials.layout-settings'))

@section('content')
    <div class="col-lg-12">
        <div class="card profile-content-page mt-4 mt-lg-0">
            <ul class="profile-content-nav nav nav-pills border-bottom mb-4" id="pills-tab"
                role="tablist">
                <li class="nav-item" role="presentation">
                    <h3 class="mt-0 mb-15 mt-15 color-brand-1">{{ __('Security') }}</h3>
                </li>
            </ul>
            <div class="card-body p-4">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                        {!! Form::open(['route' => 'public.account.post.security', 'method' => 'PUT']) !!}
                        <div class="mt-4">
                            <h5 class="fs-17 fw-semibold mb-3 mb-3">
                                {{ __('Change Password') }}
                            </h5>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="current-password-input" class="form-label">{{ __('Current password') }}</label>
                                        <input type="password" @class(['form-control', 'is-invalid' => $errors->has('old_password')])
                                        placeholder="{{ __('Enter current password') }}" name="old_password" id="current-password-input" autocomplete="password" />
                                        @error('old_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="new-password-input" class="form-label">{{ __('New password') }}</label>
                                        <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password')])
                                        placeholder="{{ __('Enter new password') }}" name="password" id="new-password-input" autocomplete="new-password" />
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="confirm-password-input" class="form-label">{{ __('Password confirmation') }}</label>
                                        <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password_confirmation')])
                                        placeholder="{{ __('Enter password confirmation') }}" name="password_confirmation" id="confirm-password-input" />
                                        @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

