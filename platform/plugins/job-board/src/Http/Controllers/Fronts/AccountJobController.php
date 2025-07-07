<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Enums\JobStatusEnum;
use Botble\JobBoard\Enums\ModerationStatusEnum;
use Botble\JobBoard\Events\EmployerPostedJobEvent;
use Botble\JobBoard\Events\JobPublishedEvent;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Forms\Fronts\JobForm;
use Botble\JobBoard\Http\Requests\AccountJobRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountActivityLog;
use Botble\JobBoard\Models\CustomFieldValue;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\JobApplication;
use Botble\JobBoard\Models\Tag;
use Botble\JobBoard\Repositories\Interfaces\AnalyticsInterface;
use Botble\JobBoard\Services\StoreTagService;
use Botble\JobBoard\Tables\Fronts\JobTable;
use Botble\Media\Facades\RvMedia;
use Botble\Optimize\Facades\OptimizerHelper;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountJobController extends BaseController
{
    public function __construct(
        protected AnalyticsInterface $analyticsRepository
    ) {
        OptimizerHelper::disable();
    }

    public function index(JobTable $table)
    {
        $this->pageTitle(__('Manage Jobs'));

        Theme::breadcrumb()
            ->add(__('My Profile'), route('public.account.dashboard'))
            ->add(__('Manage Jobs'));

        SeoHelper::setTitle(__('Manage Jobs'));

        return $table->render(JobBoardHelper::viewPath('dashboard.table.base'));
    }

    public function create()
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        if (! $account->canPost()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl(route('public.account.packages'))
                ->setMessage(__('Please purchase a package to post a job.'));
        }

        if (JobBoardHelper::employerManageCompanyInfo() && ! $account->companies()->exists()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl(route('public.account.companies.create'))
                ->setMessage(__('Please update your company information first.'));
        }

        $this->pageTitle(__('Post a job'));

        SeoHelper::setTitle(__('Post a job'));

        return JobForm::create()->renderForm();
    }

    public function store(AccountJobRequest $request, StoreTagService $storeTagService)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        if (! $account->canPost()) {
            return $this
                ->httpResponse()->setNextUrl(route('public.account.jobs.index'));
        }

        $this->processRequestData($request);

        $request->except([
            'is_featured',
            'moderation_status',
            'never_expired',
        ]);

        $request->merge([
            'expire_date' => Carbon::now()->addDays(JobBoardHelper::jobExpiredDays()),
            'author_id' => $account->getAuthIdentifier(),
            'author_type' => Account::class,
        ]);

        if (! $request->has('employer_colleagues')) {
            $request->merge(['employer_colleagues' => []]);
        }

        $job = new Job();
        $job->fill($request->input());

        if (JobBoardHelper::isEnabledJobApproval()) {
            $job->moderation_status = ModerationStatusEnum::PENDING;
        } else {
            $job->moderation_status = ModerationStatusEnum::APPROVED;

            event(new JobPublishedEvent($job));
        }

        $job->save();

        $customFields = CustomFieldValue::formatCustomFields($request->input('custom_fields') ?? []);

        $job->customFields()
            ->whereNotIn('id', collect($customFields)->pluck('id')->all())
            ->delete();

        $job->customFields()->saveMany($customFields);

        $job->skills()->sync($request->input('skills', []));
        $job->jobTypes()->sync($request->input('jobTypes', []));
        $job->categories()->sync($request->input('categories', []));

        $storeTagService->execute($request, $job);

        event(new CreatedContentEvent(JOB_MODULE_SCREEN_NAME, $request, $job));

        AccountActivityLog::query()->create([
            'action' => 'create_job',
            'reference_name' => $job->name,
            'reference_url' => route('public.account.jobs.edit', $job->id),
        ]);

        if (JobBoardHelper::isEnabledCreditsSystem() && $account->credits > 0) {
            $account->credits--;
            $account->save();
        }

        if (Job::query()->whereKey($job->getKey())->value('status')->getValue() == JobStatusEnum::PUBLISHED) {
            EmployerPostedJobEvent::dispatch($job, $account);
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('public.account.jobs.index'))
            ->setNextUrl(route('public.account.jobs.edit', $job->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(Job $job, Request $request)
    {
        abort_unless($this->canManageJob($job), 404);

        event(new BeforeEditContentEvent($request, $job));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $job->name]));

        return JobForm::createFromModel($job)
            ->renderForm();
    }

    protected function canManageJob(Job $job): bool
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();
        if (! $account->isEmployer()) {
            return false;
        }

        if ($job->company_id && in_array($job->company_id, $account->companies()->pluck('id')->all())) {
            return true;
        }

        return $account->id == $job->author_id && $job->author_type == Account::class;
    }

    public function update(Job $job, AccountJobRequest $request, StoreTagService $storeTagService)
    {
        abort_unless($this->canManageJob($job), 404);

        $this->processRequestData($request);

        $request->except([
            'is_featured',
            'moderation_status',
            'never_expired',
            'expire_date',
        ]);

        if (! $request->has('employer_colleagues')) {
            $request->merge(['employer_colleagues' => []]);
        }

        if ($job->status != JobStatusEnum::PUBLISHED && $request->input('status') == JobStatusEnum::PUBLISHED) {
            $job->loadMissing('author');
            EmployerPostedJobEvent::dispatch($job, $job->author);
        }

        $job->fill($request->input());
        $job->save();

        $customFields = CustomFieldValue::formatCustomFields($request->input('custom_fields') ?? []);

        $job->customFields()
            ->whereNotIn('id', collect($customFields)->pluck('id')->all())
            ->delete();

        $job->customFields()->saveMany($customFields);

        $job->skills()->sync($request->input('skills', []));
        $job->jobTypes()->sync($request->input('jobTypes', []));
        $job->categories()->sync($request->input('categories', []));

        $storeTagService->execute($request, $job);

        event(new UpdatedContentEvent(JOB_MODULE_SCREEN_NAME, $request, $job));

        AccountActivityLog::query()->create([
            'action' => 'update_job',
            'reference_name' => $job->name,
            'reference_url' => route('public.account.jobs.edit', $job->id),
        ]);

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('public.account.jobs.index'))
            ->setNextUrl(route('public.account.jobs.edit', $job->getKey()))
            ->withUpdatedSuccessMessage();
    }

    protected function processRequestData(Request $request): Request
    {
        if ($request->hasFile('featured_image_input')) {
            $account = auth('account')->user();
            $result = RvMedia::handleUpload($request->file('featured_image_input'), 0, $account->upload_folder);
            if (! $result['error']) {
                $file = $result['data'];
                $request->merge(['featured_image' => $file->url]);
            }
        }

        $shortcodeCompiler = shortcode()->getCompiler();

        $request->merge([
            'content' => $shortcodeCompiler->strip(
                $request->input('content'),
                $shortcodeCompiler->whitelistShortcodes()
            ),
        ]);

        $except = [
            'is_featured',
        ];

        foreach ($except as $item) {
            $request->request->remove($item);
        }

        return $request;
    }

    public function destroy(Job $job)
    {
        abort_unless($this->canManageJob($job), 404);

        $job->delete();

        AccountActivityLog::query()->create([
            'action' => 'delete_job',
            'reference_name' => $job->name,
        ]);

        return $this
            ->httpResponse()->setMessage(__('Delete job successfully!'));
    }

    public function renew(int|string $id)
    {
        /** @var \Botble\JobBoard\Models\Job $job */
        $job = Job::query()->findOrFail($id);

        abort_unless($this->canManageJob($job), 404);
        /**
         * @var Account $account
         */
        $account = auth('account')->user();
        if ($account->credits < 1) {
            return $this
                ->httpResponse()->setError()->setMessage(__("You don't have enough credit to renew this job!"));
        }

        $job->expire_date = $job->expire_date->addDays(JobBoardHelper::jobExpiredDays());
        $job->save();

        if (JobBoardHelper::isEnabledCreditsSystem() && $account->credits > 0) {
            $account->credits--;
            $account->save();
        }

        AccountActivityLog::query()->create([
            'action' => 'renew_job',
            'reference_name' => $job->name,
        ]);

        return $this
            ->httpResponse()->setMessage(__('Renew job successfully'));
    }

    public function analytics(int|string $id)
    {
        /** @var \Botble\JobBoard\Models\Job $job */
        $job = Job::query()->findOrFail($id);

        abort_unless($this->canManageJob($job), 404);

        $job->loadCount([
            'savedJobs',
            'applicants',
        ]);

        $numberSaved = $job->saved_jobs_count;
        $applicants = $job->applicants_count;
        $viewsToday = $this->analyticsRepository->getTodayViews($job->id);
        $referrers = $this->analyticsRepository->getReferrers($job->id);
        $countries = $this->analyticsRepository->getCountriesViews($job->id);

        $title = __('Analytics for job: :name', ['name' => $job->name]);

        SeoHelper::setTitle($title);
        $this->pageTitle($title);

        $data = compact('job', 'viewsToday', 'numberSaved', 'applicants', 'referrers', 'countries', 'title');

        return JobBoardHelper::view('dashboard.jobs.analytics', $data);
    }

    public function appliedJobs(Request $request)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $with = [
            'job',
            'job.slugable',
            'job.jobTypes',
            'job.jobExperience',
            'job.company',
            'job.company.slugable',
        ];

        if (is_plugin_active('location')) {
            $with = array_merge($with, ['job.state', 'job.city']);
        }

        $applications = JobApplication::query()
            ->whereHas('job')
            ->where('account_id', $account->getKey())
            ->with($with);

        switch ($request->input('order_by')) {
            case 'newest':
                $applications = $applications->orderBy('created_at', 'DESC');

                break;
            case 'oldest':
                $applications = $applications->orderBy('created_at', 'ASC');

                break;
            case 'random':
                $applications = $applications->inRandomOrder();

                break;
        }

        $applications = $applications->paginate(10);

        SeoHelper::setTitle(__('Applied Jobs'));
        Theme::breadcrumb()
            ->add(__('My Profile'), route('public.account.overview'))
            ->add(__('Applied Jobs'));

        $data = compact('account', 'applications');

        return JobBoardHelper::scope('account.jobs.applied', $data);
    }

    public function savedJobs(Request $request)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $with = [
            'slugable',
            'company',
        ];

        if (is_plugin_active('location')) {
            $with = array_merge($with, ['city', 'state']);
        }

        // @phpstan-ignore-next-line
        $jobs = Job::query()
            ->select(['jb_jobs.*'])
            ->active()
            ->whereHas('savedJobs', function ($query) use ($account): void {
                $query->where('jb_saved_jobs.account_id', $account->getKey());
            })
            ->addApplied()
            ->with($with);

        if ($category = $request->integer('category')) {
            $jobs->whereHas('categories', function ($query) use ($category): void {
                $query->where('jb_categories.id', $category);
            });
        }

        switch ($request->input('order_by')) {
            case 'newest':
                $jobs = $jobs->orderBy('jb_jobs.created_at', 'DESC');

                break;
            case 'oldest':
                $jobs = $jobs->orderBy('jb_jobs.created_at', 'ASC');

                break;
            case 'random':
                $jobs = $jobs->inRandomOrder();

                break;
        }

        $jobs = $jobs->paginate();

        SeoHelper::setTitle(__('Saved Jobs'));
        Theme::breadcrumb()
            ->add(__('My Profile'), route('public.account.overview'))
            ->add(__('Saved Jobs'));

        $data = compact('account', 'jobs');

        return JobBoardHelper::scope('account.jobs.saved', $data);
    }

    public function savedJob(Request $request, ?int $id = null)
    {
        if (! $id) {
            $id = $request->input('job_id');
        }

        abort_unless($id, 404);

        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        // @phpstan-ignore-next-line
        $job = Job::query()
            ->select(['jb_jobs.id', 'jb_jobs.name'])
            ->active()
            ->where(['jb_jobs.id' => $id])
            ->addSaved()
            ->firstOrFail();

        if (! $job->is_saved) {
            $account->savedJobs()->attach($job->id);
            $message = __('Job :job added from saved jobs!', ['job' => $job->name]);
        } else {
            $account->savedJobs()->detach($job->id);
            $message = __('Job :job removed from saved jobs!', ['job' => $job->name]);
        }

        return $this
            ->httpResponse()
            ->setData([
                'is_saved' => ! $job->is_saved,
                'count' => $account->savedJobs()->count(),
            ])
            ->setMessage($message);
    }

    public function getAllTags(): array
    {
        return Tag::query()->pluck('name')->all();
    }
}
