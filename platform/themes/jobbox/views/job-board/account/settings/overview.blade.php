<div class="col-lg-12">
    <div class="card profile-content-page mt-4 mt-lg-0">
        <ul class="profile-content-nav nav nav-pills border-bottom mb-4" id="pills-tab"
            role="tablist">
            <li class="nav-item" role="presentation">
                <h3 class="mt-0 mb-15 mt-15 color-brand-1">{{ __('Overview') }}</h3>
            </li>
        </ul>
        <div class="card-body p-4">
            <div class="tab-content" id="pills-tabContent">
                <h5 class="fs-18 fw-bold">{{ __('About') }}</h5>
                <p class="text-muted mt-4">{!! BaseHelper::clean($account->description) !!}</p>
            </div>
        </div>
    </div>
</div>
