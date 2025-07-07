@php
    $defaultCompanyLogo = theme_option('default_company_logo', true);
@endphp

<div class="quick-search-result">
    @foreach($jobs as $job)
        @php
            $companyLogo = $job->company->logo;
        @endphp

        <div class="quick-search-result__item">
            @if($defaultCompanyLogo || $companyLogo)
                <div class="quick-search-result__item__image">
                    <a href="{{ $job->company->url }}">
                        {{ RvMedia::image($companyLogo ?: $defaultCompanyLogo, $job->company->name, 'thumb') }}
                    </a>
                </div>
            @endif

            <div class="quick-search-result__item__content text-truncate">
                <h3 class="quick-search-result__item__content__title text-truncate">
                    <a href="{{ $job->url }}">{{ $job->name }}</a>
                </h3>
                <div class="quick-search-result__item__content__location">
                    <i class="fa fa-map-marker"></i>
                    {{ $job->location }}
                </div>
            </div>
        </div>
    @endforeach
</div>
