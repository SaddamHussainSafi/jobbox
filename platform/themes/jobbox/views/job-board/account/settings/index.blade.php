@php
    Theme::asset()->container('footer')->add('location-js', asset('vendor/core/plugins/location/js/location.js'), ['jquery']);
@endphp

@extends(Theme::getThemeNamespace('views.job-board.account.partials.layout-settings'))

@section('content')
    <div>
        <h3 class="mt-0 mb-15 color-brand-1">{{ __('My Account') }}</h3>

        @if ($account->avatar_id)
            <form id="delete-avatar-form" method="POST" action="{{ route('public.account.avatar.destroy') }}">
                @csrf
                @method('DELETE')
            </form>
        @endif

        {!! Form::open(['route' => 'public.account.post.settings', 'method' => 'POST', 'files' => true]) !!}
                <div class="mt-35 mb-40 box-info-profile avatar-view d-inline-block">
                    <div class="image-profile">
                        <img src="{{ $account->avatar_url }}" id="profile-img" alt="{{ $account->name }}">
                    </div>
                    <a class="btn btn-apply">{{ __('Upload Avatar') }}</a>
                </div>

                @if ($account->avatar_id)
                    <button class="btn btn-danger btn-remove-avatar" data-confirm="{{  __('Are you sure you want to remove this avatar?') }}">
                        <x-core::icon name="ti ti-trash"/>
                    </button>
                @endif

            {!! $form
                ->when($account->type->getValue() === 'job-seeker', function ($form) use ($languages, $languageForm) {
                    return $form->addAfter('favorite_tags', 'languages', 'html', \Botble\Base\Forms\FieldOptions\HtmlFieldOption::make()->content(
                        view(Theme::getThemeNamespace('views.job-board.account.partials.languages'), compact('languages', 'languageForm'))->render()
                    ));
                })
                ->renderForm(showStart: false, showEnd: false) !!}

            <div class="box-button mt-15">
                <button type="submit" class="btn btn-apply-big font-md font-bold">{{ __('Save All Changes') }}</button>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="modal fade" id="addLanguageModal" tabindex="-1" aria-labelledby="addLanguageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addLanguageModalLabel">{{ __('Add a new language') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! $languageForm->renderForm() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" form="account-language-form" class="btn btn-primary">{{ __('Add') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(function () {
                $(document).on('click', '[data-bb-toggle="delete-language"]', function (e) {
                    e.preventDefault();
                    if (! confirm('{{ __('Are you sure you want to delete this language?') }}')) {
                        return;
                    }
                    var url = $(this).data('url');
                    var $this = $(this);
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $(this).closest('form').find('input[name="_token"]').val(),
                        },
                        success: function (response) {
                            if (response.error) {
                                return;
                            }
                            $this.closest('li').remove();
                            if (! $('.list-group li').length) {
                                $('.list-group').html('<div class="alert alert-warning mb-0"><small>{{ __('You have not added any language yet!') }}</small></div>');
                            }
                        }
                    });
                });
            });
        });
    </script>
@endsection
