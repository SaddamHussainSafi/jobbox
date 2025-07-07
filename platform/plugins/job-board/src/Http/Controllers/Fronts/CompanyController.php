<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Forms\Fronts\CompanyForm;
use Botble\JobBoard\Http\Requests\AccountCompanyRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountActivityLog;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Tables\Fronts\CompanyTable;
use Botble\Media\Facades\RvMedia;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Services\SlugService;
use Botble\Theme\Facades\Theme;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            abort_unless(JobBoardHelper::employerManageCompanyInfo(), 403);

            return $next($request);
        });

        $this->middleware(function (Request $request, Closure $next) {
            /**
             * @var Account $account
             */
            $account = $request->user('account');

            abort_if(! JobBoardHelper::employerCreateMultipleCompanies() && $account->companies()->exists(), 403);

            return $next($request);
        })->only(['create', 'store']);
    }

    public function index(CompanyTable $table)
    {
        $this->pageTitle(__('Companies'));

        SeoHelper::setTitle(__('Companies'));

        Theme::breadcrumb()
            ->add(__('My Profile'), route('public.account.dashboard'))
            ->add(__('Companies'));

        return $table->render(JobBoardHelper::viewPath('dashboard.table.base'));
    }

    public function create()
    {
        SeoHelper::setTitle(__('Create a company'));
        Theme::breadcrumb()
            ->add(__('My Profile'), route('public.account.dashboard'))
            ->add(__('Companies'), route('public.account.companies.index'))
            ->add(__('Create a company'));

        $this->pageTitle(__('Create a company'));

        Assets::addStyles(['datetimepicker'])
            ->addScripts([
                'moment',
                'datetimepicker',
                'jquery-ui',
                'input-mask',
                'blockui',
            ]);

        return CompanyForm::create()->renderForm();
    }

    public function store(AccountCompanyRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $request->merge([
            'status' => setting('verify_account_created_company', 1) == 1 ? BaseStatusEnum::PENDING : BaseStatusEnum::PUBLISHED,
            'logo' => null,
            'cover_image' => null,
            'is_featured' => false,
        ]);

        $request = $this->handleUpload($request);

        /** @var \Botble\JobBoard\Models\Company $company */
        $company = Company::query()->create($request->input());

        $company->accounts()->syncWithoutDetaching(['account_id' => $account->getKey()]);

        $slug = app(SlugService::class)->create($request->input('name'), 0, Company::class);

        $request->merge(compact('slug'));

        event(new CreatedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));

        AccountActivityLog::query()->create([
            'action' => 'create_company',
            'reference_name' => $company->name,
            'reference_url' => route('public.account.companies.edit', $company->id),
        ]);

        EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'company_name' => $company->name,
                'company_url' => route('companies.edit', $company->id),
                'employer_name' => $account->name,
            ])
            ->sendUsingTemplate('new-company-profile-created');

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('public.account.companies.index'))
            ->setNextUrl(route('public.account.companies.edit', $company->id))
            ->setMessage(__('Create company profile successfully!'));
    }

    protected function handleUpload(Request $request)
    {
        if ($request->hasFile('logo_input')) {
            $result = RvMedia::handleUpload($request->file('logo_input'), 0, 'companies');
            if (! $result['error']) {
                $file = $result['data'];
                $request->merge(['logo' => $file->url]);
            }
        }

        if ($request->hasFile('cover_image_input')) {
            $result = RvMedia::handleUpload($request->file('cover_image_input'), 0, 'companies');
            if (! $result['error']) {
                $file = $result['data'];
                $request->merge(['cover_image' => $file->url]);
            }
        }

        return $request;
    }

    protected function getCompany(int $id, int $accountId)
    {
        return Company::query()
            ->select(['jb_companies.*'])
            ->where('id', $id)
            ->whereHas('accounts', function ($query) use ($accountId): void {
                $query->where('account_id', $accountId);
            })
            ->first();
    }

    public function edit(int|string $id)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $company = $this->getCompany($id, $account->getKey());
        abort_unless($company, 404);

        $title = __('Edit company ":name"', ['name' => $company->name]);
        SeoHelper::setTitle($title);
        $this->pageTitle($title);

        Theme::breadcrumb()
            ->add(__('My Profile'), route('public.account.dashboard'))
            ->add(__('Companies'), route('public.account.companies.index'))
            ->add($title);

        Assets::addStyles(['datetimepicker'])
            ->addScripts([
                'moment',
                'datetimepicker',
                'jquery-ui',
                'input-mask',
                'blockui',
            ]);

        return CompanyForm::createFromModel($company)
            ->renderForm();
    }

    public function update(int|string $id, AccountCompanyRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $company = $this->getCompany($id, $account->getKey());
        abort_unless($company, 404);

        $request->except([
            'status',
            'logo',
            'cover_image',
            'is_featured',
        ]);

        $request = $this->handleUpload($request);

        $company->fill($request->input());
        $company->save();

        event(new UpdatedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));

        AccountActivityLog::query()->create([
            'action' => 'update_company',
            'reference_name' => $company->name,
            'reference_url' => route('public.account.companies.edit', $company->id),
        ]);

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('public.account.companies.index'))
            ->setNextUrl(route('public.account.companies.edit', $company->id))
            ->setMessage(__('Update company profile successfully!'));
    }

    public function destroy(int|string $id, Request $request)
    {
        try {
            /**
             * @var Account $account
             */
            $account = auth('account')->user();

            $company = $this->getCompany($id, $account->getKey());
            abort_unless($company, 404);

            $company->delete();

            event(new DeletedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));

            AccountActivityLog::query()->create([
                'action' => 'delete_company',
                'reference_name' => $company->name,
            ]);

            return $this
                ->httpResponse()
                ->withDeletedSuccessMessage();
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
