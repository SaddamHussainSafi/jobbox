@php
    $account = auth('account')->user();
@endphp

{!! SeoHelper::render() !!}

@include('plugins/job-board::themes.dashboard.layouts.header-meta')

<link href="{{ asset('vendor/core/plugins/job-board/css/dashboard/style.css') }}" rel="stylesheet">

@if (session('locale_direction', 'ltr') == 'rtl')
    <link href="{{ asset('vendor/core/core/base/css/core.rtl.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/core/plugins/job-board/css/dashboard/style-rtl.css') }}" rel="stylesheet">
@endif

@if (File::exists($styleIntegration = Theme::getStyleIntegrationPath()))
    {!! Html::style(Theme::asset()->url('css/style.integration.css?v=' . filectime($styleIntegration))) !!}
@endif
