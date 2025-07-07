@extends(Theme::getThemeNamespace('views.job-board.account.partials.layout-settings'))

@section('content')
    <div class="col-lg-12">
        <div class="card profile-content-page mt-4 mt-lg-0">
            <ul class="profile-content-nav nav nav-pills border-bottom mb-50" id="pills-tab"
                role="tablist">
                <li class="nav-item" role="presentation">
                    <h3 class="mt-0 mb-15 mt-15 color-brand-1">{{ __('Overview') }}</h3>
                </li>
            </ul>
            <div class="card-body p-4">
                <div class="tab-content" id="pills-tabContent">
                    <h5 class="fs-18 fw-bold">{{ __('About') }}</h5>
                    <p class="text-muted mt-4">{!! BaseHelper::clean($account->description) !!}</p>
                    <div>
                        @if($countEducation = $educations->count())
                            <div class="candidate-education-details mt-4 pt-3">
                                <h4 class="fs-17 fw-bold mb-0">{{ __('Education') }}</h4>
                                @foreach($educations as $education)
                                    <div class="candidate-education-content mt-4 d-flex">
                                        <div class="circle flex-shrink-0 bg-soft-primary">{{ $education->specialized ? strtoupper(substr($education->specialized, 0, 1)) : 'E' }}</div>
                                        <div class="ms-4">
                                            @if ($education->specialized)
                                                <h6 class="fs-16 mb-1">{{ $education->specialized }}</h6>
                                            @endif
                                            <p class="mb-2 text-muted">{{ $education->school }} -
                                                ({{  $education->started_at->format('Y') }} -
                                                {{ $education->ended_at ? $education->ended_at->format('Y'): __('Now') }})
                                            </p>
                                            <p class="text-muted">{!! BaseHelper::clean($education->description) !!}</p>
                                        </div>
                                        @if ($countEducation >= 1 && ! $loop->last)
                                            <span class="line"></span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($countExperience = $experiences->count())
                            <div class="candidate-education-details mt-4 pt-3">
                                <h4 class="fs-17 fw-bold mb-0">{{ __('Experience') }}</h4>
                                @foreach( $experiences as $experience)
                                    <div class="candidate-education-content mt-4 d-flex">
                                        <div class="circle flex-shrink-0 bg-soft-primary"> {{ $experience->position ? strtoupper(substr($experience->position, 0, 1)) : '' }} </div>
                                        <div class="ms-4">
                                            @if ($experience->position)
                                                <h6 class="fs-16 mb-1">{{ $experience->position }}</h6>
                                            @endif
                                            <p class="mb-2 text-muted">{{ $experience->company }} -
                                                ({{ $experience->started_at->format('Y') }} -
                                                {{ $experience->ended_at ? $experience->ended_at->format('Y') : __('Now') }})
                                            </p>
                                            <p class="text-muted">{!! BaseHelper::clean($experience->description) !!}</p>
                                        </div>
                                        @if ($countExperience >= 1 && ! $loop->last)
                                            <span class="line"></span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

