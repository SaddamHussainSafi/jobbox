@extends(Theme::getThemeNamespace('views.job-board.account.partials.layout-settings'))

@section('content')
    <div class="col-lg-12">
        <div class="mb-3 mt-10">
            <a href="{{ route('public.account.educations.create') }}" class="btn btn-default btn-brand icon-tick">{{ __('Add Education') }}</a>
        </div>
    </div>
    <div class="box-timeline mt-50">
        @forelse($educations as $education)
            <div class="item-timeline">
                <div class="timeline-year">
                    <span>{{ $education->started_at->format('Y') }} -
                       {{ $education->ended_at ? $education->ended_at->format('Y'): __('Now') }}
                    </span>
                </div>
                <div class="timeline-info">
                    <h5 class="color-brand-1">
                        {{ $education->school }}
                        @if ($education->specialized)
                            <span class="ml-5 text-muted">
                                ({{ $education->specialized }})
                            </span>
                        @endif
                    </h5>
                    <p class="color-text-paragraph-2 mb-15">{!! BaseHelper::clean($education->description) !!}</p>
                </div>
                <div class="timeline-actions">
                    <a href="{{ route('public.account.educations.edit', $education->id) }}" class="btn btn-editor"></a>

                    <form method="post" action="{{ route('public.account.educations.destroy', $education->id) }}">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('{{ __('Are you sure you want to delete this item?') }}');" class="btn btn-remove" type="submit"></button>
                    </form>
                </div>
            </div>
        @empty
        @endforelse
    </div>
@endsection
