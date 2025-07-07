@extends(Theme::getThemeNamespace('views.job-board.account.partials.layout-settings'))

@section('content')
    <form action="{{ route('public.account.educations..update', $education->id) }}" method="post">
        <div class="row">
            <div class="col-12">
                @csrf
                <div class="mb-3">
                    <label class="font-sm color-text-mutted mb-10" for="school">{{ __('School') }}</label>
                    <input type="text" class="form-control @error('school') is-invalid @enderror" id="school"
                           name="school" value="{{ old('school', $education) }}" placeholder="{{ __('Enter School') }}"/>
                    @error('school')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="font-sm color-text-mutted mb-10" for="specialized">{{ __('Specialized') }}</label>
                    <input type="text" class="form-control @error('position') is-invalid @enderror" id="position"
                           name="specialized" value="{{ old('specialized', $education) }}" placeholder="{{ __('Enter Specialized') }}"/>
                    @error('specialized')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="font-sm color-text-mutted mb-10" for="started_at">{{ __('Start') }}</label>
                            <input type="date" class="form-control @error('started_at') is-invalid @enderror" id="started_at"
                                   name="started_at" value="{{ $education->started_at->format('Y-m-d') }}" />
                            @error('started_at')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="font-sm color-text-mutted mb-10" for="ended_at">{{ __('End') }}</label>
                            <input type="date" class="form-control @error('ended_at') is-invalid @enderror" id="ended_at"
                                   name="ended_at" value="{{ $education->ended_at ? $education->ended_at->format('Y-m-d') : '' }}" />
                            @error('ended_at')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="bio"
                               class="font-sm color-text-mutted mb-10">{{ __('Description') }}</label>
                        {!! Form::customEditor('description', old('description', $education)) !!}
                        @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="border-bottom pt-10 pb-10 mb-30"></div>
                <div class="box-button mt-15">
                    <button class="btn btn-apply-big font-md font-bold">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </form>
@endsection
