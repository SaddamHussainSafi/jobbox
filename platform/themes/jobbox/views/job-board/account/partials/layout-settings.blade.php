@php
    Theme::asset()->add('avatar-css', 'vendor/core/plugins/job-board/css/avatar.css');
    Theme::asset()->add('tagify-css', 'vendor/core/core/base/libraries/tagify/tagify.css');
    Theme::asset()->container('footer')->add('cropper-js', 'vendor/core/plugins/job-board/libraries/cropper.js', ['jquery']);
    Theme::asset()->container('footer')->add('avatar-js', 'vendor/core/plugins/job-board/js/avatar.js');
    Theme::asset()->container('footer')->add('editor-lib-js', config('core.base.general.editor.' . BaseHelper::getRichEditor() . '.js'));
    Theme::asset()->container('footer')->add('editor-js', 'vendor/core/core/base/js/editor.js');
    Theme::asset()->container('footer')->add('tagify-js', 'vendor/core/core/base/libraries/tagify/tagify.js');
    Theme::asset()->container('footer')->add('tag-js', 'vendor/core/core/base/js/tags.js');

    $url = url()->current();

    $coverImage = '';

    if ($account->getMetaData('cover_image', true)) {
        $coverImage = $account->getMetaData('cover_image', true);
    } else if (theme_option('background_cover_candidate_default')) {
        $coverImage = theme_option('background_cover_candidate_default');
    }
@endphp

<main class="main crop-avatar user-profile-section">
    <section class="section-box-2">
        <div class="container">
            <div class="banner-hero banner-image-single" style="background: url('{{ RvMedia::getImageUrl($coverImage, null, false, RvMedia::getDefaultImage()) }}') center no-repeat"></div>
            <div class="box-company-profile">
                <div class="image-candidate"><img src="{{ $account->avatar_url }}" alt="{{ $account->name }}"></div>
                <div class="row mt-30">
                    <div class="col-lg-8 col-md-12">
                        <h5 class="f-18">{{ $account->name }} <span class="card-location font-regular ml-20">{{ $account->address }}</span></h5>
                        <p class="mt-0 font-md color-text-paragraph-2 mb-15">{!! BaseHelper::clean($account->description) !!}</p>
                    </div>
                    @if ($account->is_public_profile)
                        <div class="col-lg-4 col-md-12 text-lg-end">
                            <a class="btn btn-preview-icon btn-apply btn-apply-big" href="{{ $account->url }}"  >{{ __('Preview') }}</a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="border-bottom pt-10 pb-10"></div>
        </div>
    </section>
    <section class="section-box mt-50 ">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="box-nav-tabs nav-tavs-profile mb-5">
                        <ul class="nav" role="tablist">
                            <li><a @class(['btn btn-border aboutus-icon mb-20', 'active' => $url === route('public.account.settings')]) href="{{ route('public.account.settings') }}" >{{ __('My Profile') }}</a></li>
                            <li><a @class(['btn btn-border recruitment-icon mb-20', 'active' => $url === route('public.account.security')]) href="{{ route('public.account.security') }}" >{{ __('Security') }}</a></li>
                            <li><a @class(['btn btn-border recruitment-icon mb-20', 'active' => $url === route('public.account.overview')]) href="{{ route('public.account.overview') }}">{{ __('Overview') }}</a></li>
                           @if ($account->isJobSeeker())
                                <li><a @class(['btn btn-border recruitment-icon mb-20', 'active' => $url === route('public.account.experiences.index')]) href="{{ route('public.account.experiences.index') }}" >{{ __('Experiences') }}</a></li>
                                <li><a @class(['btn btn-border recruitment-icon mb-20', 'active' => $url === route('public.account.educations.index')]) href="{{ route('public.account.educations.index') }}" >{{ __('Educations') }}</a></li>
                           @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12 col-12 mb-50">
                    <div class="content-single ">
                        <div class="tab-content">
                           @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" aria-labelledby="avatar-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form class="avatar-form" method="post" action="{{ route('public.account.avatar') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title" id="avatar-modal-label">
                            <strong>{{ __('Profile Image') }}</strong>
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="avatar-body">

                            <!-- Upload image and data -->
                            <div class="avatar-upload">
                                <input class="avatar-src" name="avatar_src" type="hidden">
                                <input class="avatar-data" name="avatar_data" type="hidden">
                                @csrf
                                <label for="avatarInput">{{ __('New image') }}</label>
                                <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                            </div>

                            <div class="loading" tabindex="-1" role="img" aria-label="{{ __('Loading') }}"></div>

                            <!-- Crop and preview -->
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="avatar-wrapper"></div>
                                    <div class="error-message text-danger" style="display: none"></div>
                                </div>
                                <div class="col-md-3 avatar-preview-wrapper">
                                    <div class="avatar-preview preview-lg"></div>
                                    <div class="avatar-preview preview-md"></div>
                                    <div class="avatar-preview preview-sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button class="btn btn-outline-primary avatar-save" type="submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    'use strict';

    var RV_MEDIA_URL = {
        base: '{{ url('') }}',
        filebrowserImageBrowseUrl: false,
        media_upload_from_editor: '{{ route('public.account.upload-from-editor') }}'
    }

    function setImageValue(file) {
        $('.mce-btn.mce-open').parent().find('.mce-textbox').val(file);
    }
</script>
<iframe id="form_target" name="form_target" style="display:none"></iframe>
<form id="tinymce_form" action="{{ route('public.account.upload-from-editor') }}" target="form_target" method="post" enctype="multipart/form-data"
      style="width:0; height:0; overflow:hidden; display: none;">
    @csrf
    <input name="upload" id="upload_file" type="file" onchange="$('#tinymce_form').submit();this.value='';">
    <input type="hidden" value="tinymce" name="upload_type">
</form>
