@if($canReview)
    <form action="{{ route('public.reviews.create') }}" method="post" class="review-form mt-4 pt-3">
        @csrf
        <input type="hidden" name="reviewable_type" value="{{ get_class($reviewable) }}">
        <input type="hidden" name="reviewable_id" value="{{ $reviewable->getKey() }}">
        <h6 class="fs-17 fw-semibold mb-2">{{ __('Reviews for :reviewable', ['reviewable' => $reviewable->name]) }}</h6>
        @guest('account')
            <p class="text-danger my-3">
                {!! BaseHelper::clean(__('Please <a href=":route">login</a> to write review!', ['route' => route('public.account.login')])) !!}
            </p>
        @endguest
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-3">
                    <select name="star" class="jquery-bar-rating" data-read-only="false">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5" selected>5</option>
                    </select>
                </div>
            </div>

            @if($reviewable instanceof \Botble\JobBoard\Models\Account)
                <div class="mb-3">
                    @if($account->companies->count() === 1)
                        <label for="company_id" class="form-label fw-bold">{{ __('Review as :company', ['company' => $account->companies->value('name')]) }}</label>
                        <input type="hidden" name="company_id" value="{{ $account->companies->value('id') }}">
                    @else
                        <label for="company_id" class="form-label fw-bold">{{ __('Review as company:') }}</label>
                        <select name="company_id" id="company_id" class="form-select">
                            @foreach($account->companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            @endif

            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="review" class="form-label">{{ __('Review') }}</label>
                    <textarea class="form-control" id="review" name="review" @disabled(! $canReview) placeholder="{{ __('Add your review') }}"></textarea>
                </div>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary btn-hover" @disabled(! $canReview)>
                {{ __('Submit Review') }}
            </button>
        </div>
    </form>
@endif
