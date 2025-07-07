<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Events\AdminApprovedCompanyEvent;
use Botble\JobBoard\Forms\CompanyForm;
use Botble\JobBoard\Http\Requests\AjaxCompanyRequest;
use Botble\JobBoard\Http\Requests\CompanyRequest;
use Botble\JobBoard\Http\Resources\CompanyResource;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Services\StoreCompanyAccountService;
use Botble\JobBoard\Tables\CompanyTable;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::company.name'), route('companies.index'));
    }

    public function index(CompanyTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::company.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::company.create'));

        return CompanyForm::create()->renderForm();
    }

    public function store(CompanyRequest $request, StoreCompanyAccountService $storeCompanyAccountService)
    {
        /**
         * @var Company $company
         */
        $company = Company::query()->create($request->input());

        event(new CreatedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));

        $storeCompanyAccountService->execute($request, $company);

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('companies.index'))
            ->setNextUrl(route('companies.edit', $company->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Company $company, Request $request)
    {
        event(new BeforeEditContentEvent($request, $company));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $company->name]));

        return CompanyForm::createFromModel($company)->renderForm();
    }

    public function update(Company $company, CompanyRequest $request, StoreCompanyAccountService $storeCompanyAccountService)
    {
        $isApproved = $company->status->getValue() == BaseStatusEnum::PENDING && $request->input('status') == BaseStatusEnum::PUBLISHED;

        $company->fill($request->input());
        $company->save();

        if ($isApproved) {
            AdminApprovedCompanyEvent::dispatch($company);
        }

        $storeCompanyAccountService->execute($request, $company);

        event(new UpdatedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('companies.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Company $company)
    {
        return DeleteResourceAction::make($company);
    }

    public function getList(Request $request)
    {
        $keyword = $request->input('q');

        if (! $keyword) {
            return $this
                ->httpResponse()
                ->setData([]);
        }

        $data = Company::query()
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->select(['id', 'name'])
            ->paginate(10);

        return $this
            ->httpResponse()
            ->setData(CompanyResource::collection($data));
    }

    public function ajaxGetCompany(Company $company)
    {
        return $this
            ->httpResponse()
            ->setData(new CompanyResource($company));
    }

    public function ajaxCreateCompany(AjaxCompanyRequest $request)
    {
        $company = Company::query()->create($request->input());

        event(new CreatedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));

        return $this
            ->httpResponse()
            ->setData(new CompanyResource($company));
    }

    public function getAllCompanies()
    {
        return Company::query()->pluck('name')->all();
    }

    public function analytics(Company $company)
    {
        Assets::addScripts(['counterup', 'equal-height'])
            ->addStylesDirectly('vendor/core/core/dashboard/css/dashboard.css');

        $this->pageTitle(__('Analytics for company ":name"', ['name' => $company->name]));

        $company->loadCount('jobs');

        return view('plugins/job-board::company.analytics', compact('company'));
    }
}
