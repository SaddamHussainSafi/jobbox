<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Exports\CompaniesTemplateExport;
use Botble\JobBoard\Http\Requests\BulkImportRequest;
use Botble\JobBoard\Http\Requests\ImportCompanyRequest;
use Botble\JobBoard\Imports\ImportCompanies;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportCompanyController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::company.name'), route('companies.index'));
    }

    public function index(CompaniesTemplateExport $export)
    {
        $this->pageTitle(trans('plugins/job-board::import.company.name'));

        Assets::addScriptsDirectly('vendor/core/plugins/job-board/js/bulk-import.js');

        $companies = $export->collection();
        $rules = $export->rules();
        $headings = $export->headings();

        return view('plugins/job-board::import.companies', compact('companies', 'rules', 'headings'));
    }

    public function store(BulkImportRequest $request, ImportCompanies $importCompanies)
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        try {
            $importCompanies->validator(ImportCompanyRequest::class)->import($request->file('file'));

            $message = trans('plugins/job-board::import.import_success_message');

            return $this
                ->httpResponse()
                ->setData([
                    'message' => $message . ' ' . trans('plugins/job-board::import.results', [
                        'success' => $importCompanies->successes()->count(),
                        'failed' => $importCompanies->failures()->count(),
                    ]),
                ])
                ->setMessage($message);
        } catch (ValidationException $e) {
            return $this
                ->httpResponse()
                ->setError()
                ->setData($e->failures())
                ->setMessage(trans('plugins/job-board::import.import_failed_message'));
        }
    }

    public function downloadTemplate(Request $request, CompaniesTemplateExport $companiesTemplateExport)
    {
        $request->validate([
            'extension' => 'required|in:csv,xlsx',
        ]);

        $extension = Excel::XLSX;
        $contentType = 'text/xlsx';

        if ($request->input('extension') === 'csv') {
            $extension = Excel::CSV;
            $contentType = 'text/csv';
        }

        $fileName = 'template_companies_import.' . $extension;

        return $companiesTemplateExport->download($fileName, $extension, ['Content-Type' => $contentType]);
    }
}
