<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Helper;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\JobBoard\Enums\JobStatusEnum;
use Botble\JobBoard\Events\JobAppliedEvent;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Http\Requests\ApplyJobRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountEducation;
use Botble\JobBoard\Models\AccountExperience;
use Botble\JobBoard\Models\Analytics;
use Botble\JobBoard\Models\Category;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Currency;
use Botble\JobBoard\Models\Job as JobModel;
use Botble\JobBoard\Models\JobApplication;
use Botble\JobBoard\Models\JobExperience;
use Botble\JobBoard\Models\JobSkill;
use Botble\JobBoard\Models\JobType;
use Botble\JobBoard\Models\Tag;
use Botble\JobBoard\Repositories\Interfaces\JobInterface;
use Botble\Location\Facades\Location;
use Botble\Media\Facades\RvMedia;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;
use Exception;
use GeoIp2\Database\Reader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PublicController extends BaseController
{
    public function __construct(
        protected JobInterface $jobRepository,
    ) {
    }

    public function getJob(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(JobModel::class));

        abort_unless($slug, 404);

        $condition = ['jb_jobs.id' => $slug->reference_id];

        if (Auth::check()) {
            Arr::forget($condition, 'status');
            Arr::forget($condition, 'moderation_status');
        }

        $job = $this->jobRepository->getJobs([], [
            'condition' => $condition,
            'take' => 1,
            'with' => [],
        ]);

        abort_unless($job, 404);

        $job->setRelation('slugable', $slug);

        SeoHelper::setTitle($job->name)->setDescription($job->description);

        $meta = new SeoOpenGraph();
        $meta->setDescription($job->description);
        $meta->setUrl($job->url);
        $meta->setTitle($job->name);
        $meta->setType('article');

        $companyJobs = collect();

        $company = $job->company;

        if ($company && $company->id) {
            $company->loadCount('jobs');

            if (! $job->hide_company) {
                if ($company->logo) {
                    $meta->setImage(RvMedia::getImageUrl($company->logo));
                }

                $condition = [
                    ['jb_jobs.company_id', '=', $company->id],
                    ['jb_jobs.id', '!=', $job->id],
                    ['jb_jobs.hide_company', '=', false],
                ];

                $companyJobs = $this->jobRepository->getJobs(
                    [],
                    [
                        'condition' => $condition,
                        'take' => 5,
                        'order_by' => [
                            'jb_jobs.created_at' => 'desc',
                        ],
                    ],
                );
            }
        }

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Jobs'), JobBoardHelper::getJobsPageURL())
            ->add($job->name, $job->url);

        if (function_exists('admin_bar')) {
            admin_bar()->registerLink(__('Edit this job'), route('jobs.edit', $job->id), 'jobs.edit');
        }

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, JOB_MODULE_SCREEN_NAME, $job);

        $viewed = Helper::handleViewCount($job, 'viewed_job');

        if ($viewed) {
            $ip = Helper::getIpFromThirdParty();

            $countries = $this->getCountries($ip);

            Analytics::query()->create([
                'job_id' => $job->id,
                'country' => Arr::get($countries, 'countryCode'),
                'country_full' => Arr::get($countries, 'countryName'),
                'referer' => Str::limit(request()->server('HTTP_REFERER') ?? null, 250),
                'ip_address' => Str::limit($ip, 39),
                'ip_hashed' => 0,
            ]);
        }

        $job->loadMissing('customFields');

        return Theme::scope(
            'job-board.job',
            compact('job', 'companyJobs', 'company'),
            'plugins/job-board::themes.job'
        )->render();
    }

    public function getJobs(Request $request)
    {
        $requestQuery = JobBoardHelper::getJobFilters($request->input());

        if (! empty($requestQuery['keyword'])) {
            SeoHelper::setTitle(__('Search results for ":keyword"', ['keyword' => $requestQuery['keyword']]));
        }

        if (! empty($requestQuery['job_categories'])) {
            $categories = Category::query()
                ->whereIn('id', $requestQuery['job_categories'])
                ->select('id', 'name')
                ->get()
                ->map(fn ($category) => $category->name)
                ->implode(', ');

            if ($categories) {
                if (! empty($requestQuery['keyword'])) {
                    SeoHelper::setTitle(__('Search results for ":keyword" in :categories', [
                        'keyword' => $requestQuery['keyword'],
                        'categories' => $categories,
                    ]));
                } else {
                    SeoHelper::setTitle(__('Jobs in :categories', [
                        'keyword' => $requestQuery['keyword'],
                        'categories' => $categories,
                    ]));
                }
            }
        }

        $with = [
            'tags.slugable',
            'jobTypes',
            'slugable',
            'jobExperience',
            'company',
            'company.metadata',
            'company.slugable',
        ];

        $sortBy = match ($request->input('sort_by') ?: 'newest') {
            'oldest' => [
                'jb_jobs.created_at' => 'ASC',
            ],
            default => [
                'jb_jobs.created_at' => 'DESC',
            ],
        };

        if (JobBoardHelper::isPinFeaturedJobsInTheTop()) {
            $sortBy = ['jb_jobs.is_featured' => 'DESC', ...$sortBy];
        }

        if (is_plugin_active('location')) {
            $with = array_merge($with, array_keys(Location::getSupported(JobModel::class)));
        }

        $jobs = app(JobInterface::class)->getJobs(
            $requestQuery,
            [
                'with' => $with,
                'order_by' => $sortBy,
                'paginate' => [
                    'per_page' => $requestQuery['per_page'] ?? Arr::first(JobBoardHelper::getPerPageParams()),
                    'current_paged' => $requestQuery['page'] ?? 1,
                ],
            ],
        );

        $additional['total'] = $jobs->total();

        if ($additional['total']) {
            $message = __('Showing :from – :to of :total results', [
                'from' => $jobs->firstItem(),
                'to' => $jobs->lastItem(),
                'total' => $jobs->total(),
            ]);
        } else {
            $message = __('No results found');
        }

        $additional['message'] = $message;

        $jobsView = Theme::getThemeNamespace('views.job-board.partials.job-items');

        if (! view()->exists($jobsView)) {
            $jobsView = 'plugins/job-board::themes.partials.job-items';
        }

        $filtersData['jobs'] = $jobs;
        if ($requestQuery['city_id']) {
            $filtersData['stateId'] = $requestQuery['city_id'];
        }
        if ($requestQuery['state_id']) {
            $filtersData['stateId'] = $requestQuery['state_id'];
        }

        $filtersView = Theme::getThemeNamespace('views.job-board.partials.filters');

        if (view()->exists($filtersView)) {
            $additional['filters_html'] = view(
                $filtersView,
                $filtersData
            )->render();
        }

        return $this
            ->httpResponse()
            ->setData(view($jobsView, compact('jobs'))->render())
            ->setAdditional($additional)
            ->setMessage($message);
    }

    public function getCompanies(Request $request)
    {
        $requestQuery = JobBoardHelper::getCompanyFilterParams($request->input());

        $companies = Company::query()
            ->withCount([
                'activeJobs as jobs_count',
                'reviews',
            ])
            ->withAvg('reviews', 'star')
            ->with(['slugable'])
            ->orderByDesc('is_featured');

        if ($requestQuery['keyword']) {
            $companies = $companies->where('name', 'LIKE', $requestQuery['keyword'] . '%');
        }

        if (JobBoardHelper::isPinFeaturedCompaniesInTheTop()) {
            $companies = $companies->orderByDesc('is_featured');
        }

        match ($requestQuery['sort_by'] ?? 'oldest') {
            'newest' => $companies = $companies->latest(),
            default => $companies = $companies->oldest(),
        };

        $companies = $companies->paginate($requestQuery['per_page'] ?: 12);

        $total = $companies->total();

        if ($total) {
            $message = __('Showing :from – :to of :total results', [
                'from' => $companies->firstItem(),
                'to' => $companies->lastItem(),
                'total' => $companies->total(),
            ]);
        } else {
            $message = __('No results found');
        }

        $view = Theme::getThemeNamespace('views.job-board.partials.companies');

        if (! view()->exists($view)) {
            $view = 'plugins/job-board::themes.partials.companies';
        }

        return $this
            ->httpResponse()
            ->setData(view($view, compact('companies'))->render())
            ->setAdditional([
                'total' => $total,
                'message' => $message,
            ])
            ->setMessage($message);
    }

    public function postApplyJob(ApplyJobRequest $request, int $id = null)
    {
        if (! auth('account')->check() && ! JobBoardHelper::isGuestApplyEnabled()) {
            throw new HttpException(422, __('Please login to apply this job!'));
        }

        try {
            if (! $id) {
                $id = $request->input('job_id');
            }

            if (! $id) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setCode(404);
            }

            $request->merge(['account_id' => null]);

            $job = $this->jobRepository->getJobs([], [
                'condition' => ['jb_jobs.id' => $id],
                'take' => 1,
                'with' => ['author'],
            ]);

            if (! $job) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setCode(404);
            }

            if (! $job->isJobOpen()) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage(__('This job has been closed.'))
                    ->setCode(404);
            }

            $jobType = $request->input('job_type');

            if (($job->apply_url && $jobType !== 'external') ||
                (! $job->apply_url && $jobType !== 'internal')
            ) {
                return $this
                    ->httpResponse()->setError()->setMessage(__('This job is not available for apply.'));
            }

            $account = null;

            if (auth('account')->check()) {
                /**
                 * @var Account $account
                 */
                $account = auth('account')->user();

                if ($account->isEmployer()) {
                    return $this
                        ->httpResponse()
                        ->setError()
                        ->setMessage(__('Employers cannot apply for the job'));
                }

                $request->merge(['account_id' => $account->getKey()]);

                if ($job->is_applied) {
                    return $this
                        ->httpResponse()
                        ->setError()
                        ->setMessage(
                            __('You have already applied for this job. Please wait for the employer to respond.')
                        );
                }
            }

            $jobApplication = new JobApplication();

            $request->merge(['job_id' => $job->id]);

            if (! $job->apply_url) {
                if ($request->hasFile('resume')) {
                    $result = RvMedia::handleUpload($request->file('resume'), 0, 'job-applications');

                    if (! $result['error']) {
                        $file = $result['data'];
                        $request->merge(['resume' => $file->url]);
                    } else {
                        $request->merge(['resume' => null]);
                    }
                } elseif ($account && $resume = $account->resume) {
                    $request->merge(['resume' => $resume]);
                }

                if ($request->hasFile('cover_letter')) {
                    $result = RvMedia::handleUpload($request->file('cover_letter'), 0, 'job-applications');

                    if (! $result['error']) {
                        $file = $result['data'];
                        $request->merge(['cover_letter' => $file->url]);
                    } else {
                        $request->merge(['cover_letter' => null]);
                    }
                } elseif ($account && $coverLetter = $account->cover_letter) {
                    $request->merge(['cover_letter' => $coverLetter]);
                }
            } else {
                $request->merge(['resume' => null, 'cover_letter' => null]);
                $jobApplication->is_external_apply = true;
            }

            $jobApplication->fill($request->input());
            $jobApplication->save();

            $job::withoutEvents(fn () => $job::withoutTimestamps(fn () => $job->increment('number_of_applied')));

            if (! $job->apply_url) {
                $jobApplication->setRelation('job', $job);

                if ($account) {
                    $jobApplication->setRelation('account', $account);
                }

                JobAppliedEvent::dispatch($jobApplication, $job);
            }

            if (! $request->ajax()) {
                return redirect()->to($job->apply_url);
            }

            return $this
                ->httpResponse()
                ->setData(['url' => $job->apply_url])
                ->setMessage(trans('plugins/job-board::job-application.email.success'));
        } catch (Exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/job-board::job-application.email.failed'));
        }
    }

    public function getJobCategory(
        string $slug,
        Request $request
    ) {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Category::class));

        abort_unless($slug, 404);

        $condition = [
            'id' => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check()) {
            Arr::forget($condition, 'status');
            Arr::forget($condition, 'moderation_status');
        }

        $category = Category::query()
            ->where($condition)
            ->firstOrFail();

        SeoHelper::setTitle($category->name)->setDescription($category->description);

        $meta = new SeoOpenGraph();
        $meta->setDescription($category->description);
        $meta->setUrl($category->url);
        $meta->setTitle($category->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Categories'), JobBoardHelper::getJobCategoriesPageURL())
            ->add($category->name, $category->url);

        if (function_exists('admin_bar')) {
            admin_bar()->registerLink(
                __('Edit this job category'),
                route('job-categories.edit', $category->id),
                'job-categories.edit'
            );
        }

        $requestQuery = JobBoardHelper::getJobFilters($request->input());

        $jobs = $this->jobRepository->getJobs(
            array_merge($requestQuery, [
                'job_categories' => [$category->id],
            ]),
            [
                'paginate' => [
                    'per_page' => $requestQuery['per_page'] ?: 20,
                    'current_paged' => $requestQuery['page'] ?: 1,
                ],
            ]
        );

        $data = $this->getJobFilterData();

        $data['category'] = $category;
        $data['jobs'] = $jobs;

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, JOB_CATEGORY_MODULE_SCREEN_NAME, $category);

        return Theme::scope('job-board.job-category', $data, 'plugins/job-board::themes.job-category')->render();
    }

    protected function getJobFilterData(): array
    {
        $jobCategories = Category::query()->where('status', BaseStatusEnum::PUBLISHED)->get();
        $jobTypes = JobType::query()->where('status', BaseStatusEnum::PUBLISHED)->get();
        $jobExperiences = JobExperience::query()->where('status', BaseStatusEnum::PUBLISHED)->get();
        $jobSkills = JobSkill::query()->where('status', BaseStatusEnum::PUBLISHED)->get();

        $withCountJobs = [
            'jobs' => function ($query): void {
                $query
                    ->where('jb_jobs.status', JobStatusEnum::PUBLISHED)
                    ->notExpired();
            },
        ];

        $jobFeaturedCategories = $jobCategories
            ->where('is_featured')
            ->loadCount($withCountJobs);

        $jobTypes->loadCount($withCountJobs);
        $jobSkills->loadCount($withCountJobs);

        return compact(
            'jobCategories',
            'jobTypes',
            'jobExperiences',
            'jobFeaturedCategories',
            'jobSkills'
        );
    }

    public function getJobTag(string $slug, Request $request)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Tag::class));

        abort_unless($slug, 404);

        $condition = [
            'id' => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check()) {
            Arr::forget($condition, 'status');
            Arr::forget($condition, 'moderation_status');
        }

        $tag = Tag::query()
            ->where($condition)
            ->firstOrFail();

        SeoHelper::setTitle($tag->name)->setDescription($tag->description);

        $meta = new SeoOpenGraph();
        $meta->setDescription($tag->description);
        $meta->setUrl($tag->url);
        $meta->setTitle($tag->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add($tag->name, $tag->url);

        if (function_exists('admin_bar')) {
            admin_bar()->registerLink(
                __('Edit this job tag'),
                route('job-board.tag.edit', $tag->id),
                'job-board.tag.edit'
            );
        }

        $requestQuery = JobBoardHelper::getJobFilters($request->input());

        $jobs = $this->jobRepository->getJobs(
            array_merge($requestQuery, [
                'tags' => [$tag->getKey()],
                'job_tags' => [$tag->getKey()],
            ]),
            [
                'paginate' => [
                    'per_page' => $requestQuery['per_page'] ?: 20,
                    'current_paged' => $requestQuery['page'] ?: 1,
                ],
            ]
        );

        $data = $this->getJobFilterData();

        $data['tag'] = $tag;
        $data['jobs'] = $jobs;

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, JOB_BOARD_TAG_MODULE_SCREEN_NAME, $tag);

        return Theme::scope('job-board.job-tag', $data, 'plugins/job-board::themes.job-tag')->render();
    }

    protected function getCountries(string $ip): array
    {
        // We try to get the IP country using (or not) the anonymized IP
        // If it fails, because GeoLite2 doesn't know the IP country, we
        // will set it to Unknown
        try {
            $reader = new Reader(__DIR__ . '/../../../database/GeoLite2-Country.mmdb');
            $record = $reader->country($ip);
            $countryCode = $record->country->isoCode;
            $countryName = $record->country->name;
        } catch (Exception) {
            $countryCode = 'N/A';
            $countryName = 'Unknown';
        }

        return compact('countryCode', 'countryName');
    }

    public function getCompany(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Company::class));

        abort_unless($slug, 404);

        $condition = [
            'id' => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check() && request('preview')) {
            Arr::forget($condition, 'status');
        }

        /**
         * @var Company $company
         */
        $company = Company::query()
            ->where($condition)
            ->withCount([
                'jobs' => function (Builder $query): void {
                    // @phpstan-ignore-next-line
                    $query
                        ->active()
                        ->where(['jb_jobs.hide_company' => false]);
                },
                'reviews',
            ])
            ->withAvg('reviews', 'star')
            ->firstOrFail();

        $company->setRelation('slugable', $slug);

        $params = [
            'condition' => [
                'jb_jobs.company_id' => $company->getKey(),
                'jb_jobs.hide_company' => false,
            ],
            'order_by' => ['created_at' => 'DESC'],
            'paginate' => [
                'per_page' => 3,
                'current_paged' => request()->integer('page') ?: 1,
            ],
        ];

        $jobs = $this->jobRepository->getJobs([], $params);

        if (request()->ajax()) {
            $view = Theme::getThemeNamespace('views.job-board.partials.company-job-items');

            if (! view()->exists($view)) {
                $view = 'plugins/job-board::themes.partials.job-items';
            }

            return $this
                ->httpResponse()->setData(view($view, compact('jobs', 'company'))->render());
        }

        if (function_exists('admin_bar')) {
            admin_bar()->registerLink(__('Edit this company'), route('companies.edit', $company->getKey()), 'companies.edit');
        }

        SeoHelper::setTitle($company->name)->setDescription($company->description);

        $meta = new SeoOpenGraph();
        if ($company->logo) {
            $meta->setImage(RvMedia::getImageUrl($company->logo));
        }
        $meta->setDescription($company->description);
        $meta->setUrl($company->url);
        $meta->setTitle($company->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Helper::handleViewCount($company, 'viewed_company');

        Theme::breadcrumb()
            ->add(__('Companies'), JobBoardHelper::getJobCompaniesPageURL())
            ->add($company->name, $company->url);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, COMPANY_MODULE_SCREEN_NAME, $company);

        if (JobBoardHelper::isEnabledReview()) {
            $company->setRelation('reviews', $company->reviews()->with('createdBy')->paginate(10));

            /** @var Account $account */
            $account = Auth::guard('account')->user();

            $canReview = $account
                && ! $account->isEmployer()
                && $account->canReview($company);
        } else {
            $canReview = false;
        }

        $canReviewCompany = $canReview;

        return Theme::scope(
            'job-board.company',
            compact('company', 'jobs', 'canReview', 'canReviewCompany'),
            'plugins/job-board::themes.company'
        )->render();
    }

    public function getCandidate(string $slug)
    {
        abort_if(JobBoardHelper::isDisabledPublicProfile(), 404);

        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Account::class));

        abort_unless($slug, 404);

        $condition = [
            ['id', '=', $slug->reference_id],
            ['is_public_profile', '=', 1],
            ['type', '=', AccountTypeEnum::JOB_SEEKER],
        ];

        if (setting('verify_account_email', 0)) {
            $condition[] = ['confirmed_at', '!=', null];
        }

        /**
         * @var Account $candidate
         */
        $candidate = Account::query()
            ->where($condition)
            ->firstOrFail();

        $candidate->setRelation('slugable', $slug);

        SeoHelper::setTitle($candidate->name)->setDescription($candidate->description);

        $meta = new SeoOpenGraph();
        if ($candidate->avatar_url) {
            $meta->setImage(RvMedia::getImageUrl($candidate->avatar_url));
        }
        $meta->setDescription($candidate->description);
        $meta->setUrl($candidate->url);
        $meta->setTitle($candidate->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Helper::handleViewCount($candidate, 'viewed_account');

        Theme::breadcrumb()
            ->add(__('Candidates'), JobBoardHelper::getJobCandidatesPageURL())
            ->add($candidate->name, $candidate->url);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, ACCOUNT_MODULE_SCREEN_NAME, $candidate);

        $experiences = AccountExperience::where('account_id', $candidate->id)->get();
        $educations = AccountEducation::where('account_id', $candidate->id)->get();

        /**
         * @var Account $account
         */
        $account = Auth::guard('account')->user();

        if (JobBoardHelper::isEnabledReview()) {
            $candidate
                ->loadCount('reviews')
                ->loadAvg('reviews', 'star')
                ->setRelation('reviews', $candidate->reviews()->latest()->paginate(10));

            $canReview = $account
                && $account->isEmployer()
                && $account->canReview($candidate);
        } else {
            $canReview = false;
        }

        return Theme::scope(
            'job-board.candidate',
            compact('candidate', 'experiences', 'educations', 'account', 'canReview'),
            'plugins/job-board::themes.candidate'
        )->render();
    }

    public function getCandidates(Request $request)
    {
        abort_if(! $request->ajax() || JobBoardHelper::isDisabledPublicProfile(), 404);

        $candidates = JobBoardHelper::filterCandidates(request()->input());

        return $this
            ->httpResponse()
            ->setData([
                'list' => view(
                    Theme::getThemeNamespace('views.job-board.partials.candidate-list'),
                    compact('candidates')
                )->render(),
                'total_text' => __('Showing :from-:to of :total candidate(s)', [
                    'from' => $candidates->firstItem(),
                    'to' => $candidates->lastItem(),
                    'total' => $candidates->total(),
                ]),
            ]);
    }

    public function changeCurrency(Request $request, string $title = null)
    {
        if (empty($title)) {
            $title = $request->input('currency');
        }

        if (! $title) {
            return $this->httpResponse();
        }

        /**
         * @var Currency $currency
         */
        $currency = Currency::query()
            ->where('title', $title)
            ->first();

        if ($currency) {
            cms_currency()->setApplicationCurrency($currency);
        }

        return $this->httpResponse();
    }
}
