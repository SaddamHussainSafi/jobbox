<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Exports\CompaniesExport;
use Botble\JobBoard\Models\Company;
use Maatwebsite\Excel\Excel;

class ExportCompanyController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::company.name'), route('companies.index'));
    }

    public function index()
    {
        $this->pageTitle(trans('plugins/job-board::export.companies.name'));

        Assets::addScriptsDirectly(['vendor/core/plugins/job-board/js/export.js']);

        $totalCompanies = Company::query()->count();

        return view('plugins/job-board::export.companies', compact('totalCompanies'));
    }

    public function export(CompaniesExport $companiesExport)
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        return $companiesExport->download('export_companies.csv', Excel::CSV, ['Content-Type' => 'text/csv']);
    }
}
