@php
    Theme::set('pageTitle', $page->name);
    Theme::set('pageDescription', $page->description);
    Theme::set('pageCoverImage', $page->getMetaData('background_breadcrumb', true));
@endphp

{!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, Html::tag('div', BaseHelper::clean($page->content), ['class' => 'ck-content'])->toHtml(), 
$page) !!}
