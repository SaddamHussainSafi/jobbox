@extends(JobBoardHelper::viewPath('dashboard.layouts.master'))

@section('content')
    <div class="row row-cols-1 row-cols-lg-3 mb-3 row-cards">
        @foreach ($packages as $package)
            <div class="col">
                <div @class(['card card-md box-package h-100', 'active' => $package->is_default])>
                    @if ($package->percent_save)
                        <div class="ribbon ribbon-top ribbon-bookmark bg-green">
                            {{ $package->percent_save_text }}
                            <span class="sr-only">{{ trans('plugins/job-board::dashboard.save') }}</span>
                        </div>
                    @endif

                    <div class="card-body">
                        @if($package->price)
                            <div class="box-package-price d-flex align-items-end">
                                <h4>{{  $package->price_text }}</h4><span class="text-muted">/{{ $package->number_of_listings }} {{ __('post(s)') }}</span>
                            </div>
                        @else
                            <div class="box-package-price">
                                <h4>{{ __('Free') }}</h4>
                            </div>
                        @endif

                        <div class="box-package-title">
                            {{ $package->name }}
                        </div>

                        @if($package->description)
                            <p class="text-muted">{{ $package->description }}</p>
                        @endif

                        @if($features = $package->formatted_features)
                            <ul class="box-package-features list-unstyled">
                                @foreach($features as $feature)
                                    @continue(! $feature)

                                    <li class="item">
                                        <x-core::icon name="ti ti-check" class="text-success" />
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="text-center mt-4">
                            <x-core::form :url="route('public.account.package.subscribe.put')" method="put">
                                <input type="hidden" name="id" value="{{ $package->id }}">
                                <x-core::button type="submit" class="w-100" color="{{ $package->is_default ? 'success' : null }}" :disabled="$package->isPurchased()">
                                    {{ $package->isPurchased() ? __('Purchased') : __('Purchase') }}
                                </x-core::button>
                            </x-core::form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (auth('account')->user()->transactions()->exists())
        <x-core::card>
            <x-core::card.header>
                <x-core::card.title>
                    {{ trans('plugins/job-board::dashboard.transactions_title') }}
                </x-core::card.title>
            </x-core::card.header>
            <payment-history-component
                url="{{ route('public.account.ajax.transactions') }}"
                v-slot="{ isLoading, isLoadingMore, data, getData }"
            >
                <x-core::loading v-if="isLoading" />

                <template v-else>
                    <div class="empty" v-if="data.meta.total === 0">
                        <div class="empty-icon">
                            <x-core::icon name="ti ti-exclamation-circle" />
                        </div>
                        <p class="empty-title">
                            {{ trans('plugins/job-board::dashboard.oops') }}
                        </p>
                        <p class="empty-subtitle text-muted">
                            {{ trans('plugins/job-board::dashboard.no_transactions') }}
                        </p>
                    </div>

                    <div v-if="data.meta.total !== 0" class="list-group list-group-flush">
                        <div v-for="item in data.data" :key="item.id" class="list-group-item">
                            <x-core::icon name="ti ti-clock" class="me-2" />
                            <span
                                :title="$sanitize(item.description, { allowedTags: [] })"
                                v-html="$sanitize(item.description)"
                            ></span>
                        </div>
                    </div>
                    <x-core::card.footer v-if="data.links.next">
                        <a href="javascript:void(0)" v-if="!isLoadingMore" @click="getData(data.links.next)">
                            {{  trans('plugins/job-board::dashboard.load_more') }}
                        </a>
                        <a href="javascript:void(0)" v-if="isLoadingMore">
                            {{ trans('plugins/job-board::dashboard.loading_more') }}
                        </a>
                    </x-core::card.footer>
                </template>
            </payment-history-component>
        </x-core::card>
    @endif
@stop
