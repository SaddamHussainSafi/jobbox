<div class="mb-3">
    <label class="form-label">{{ __('Subtitle') }}</label>
    <input type="text" name="subtitle" value="{{ Arr::get($attributes, 'subtitle') }}" class="form-control" placeholder="{{ __('Subtitle') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Highlight text in subtitle') }}</label>
    <input type="text" name="highlight_sub_title_text" value="{{ Arr::get($attributes, 'highlight_sub_title_text') }}" class="form-control" placeholder="{{ __('Highlight text in subtitle') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Title 1') }}</label>
    <input type="text" name="title_1" value="{{ Arr::get($attributes, 'title_1') }}" class="form-control" placeholder="{{ __('Title_1') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Title 2') }}</label>
    <input type="text" name="title_2" value="{{ Arr::get($attributes, 'title_2') }}" class="form-control" placeholder="{{ __('Title_2') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Button apply text') }}</label>
    <input type="text" name="button_apply_text" value="{{ Arr::get($attributes, 'button_apply_text') }}" class="form-control" placeholder="{{ __('Change button apply text') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Button apply link') }}</label>
    <input type="url" name="button_apply_link" value="{{ Arr::get($attributes, 'button_apply_link') }}" class="form-control" placeholder="{{ __('https://example.com') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Description') }}</label>
    <textarea name="description" class="form-control" rows="3" placeholder="{{ __('Description') }}">{{ Arr::get($attributes, 'description') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image left') }}</label>
    {!! Form::mediaImage('apply_image_left', Arr::get($attributes, 'apply_image_left')) !!}
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image right') }}</label>
    {!! Form::mediaImage('apply_image_right', Arr::get($attributes, 'apply_image_right')) !!}
</div>

@php($random = Str::random(20))

<script>
    'use strict';

    $('#style_{{ $random }}').on('change', function() {
        if ('style-3' === $(this).val()) {
            $('.search-box-style-shortcode').show();
        } else {
            $('.search-box-style-shortcode').hide();
        }

        if ('style-2' === $(this).val()) {
            $('.search-box-keyword-shortcode').show();
        } else {
            $('.search-box-keyword-shortcode').hide();
        }
    });
</script>
