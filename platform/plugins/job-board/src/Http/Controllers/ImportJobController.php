<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Exports\JobsTemplateExport;
use Botble\JobBoard\Http\Requests\BulkImportRequest;
use Botble\JobBoard\Http\Requests\ImportJobRequest;
use Botble\JobBoard\Imports\JobsImport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportJobController extends BaseController
{
    public function index(JobsTemplateExport $export): View
    {
        $this->pageTitle(trans('plugins/job-board::import.name'));

        Assets::addScriptsDirectly('vendor/core/plugins/job-board/js/bulk-import.js')
            ->addScripts(['dropzone'])
            ->addStyles(['dropzone']);

        $jobs = $export->collection();
        $rules = $export->rules();
        $headings = $export->headings();

        return view('plugins/job-board::import.index', compact('jobs', 'rules', 'headings'));
    }

    public function store(BulkImportRequest $request, JobsImport $jobsImport)
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        try {
            $jobsImport
                ->setUpdateExisting($request->boolean('update_existing'))
                ->validator(ImportJobRequest::class)
                ->import($request->file('file'));

            $message = trans('plugins/job-board::import.import_success_message');

            return $this
                ->httpResponse()
                ->setData([
                    'message' => $message . ' ' . trans('plugins/job-board::import.results', [
                        'success' => $jobsImport->successes()->count(),
                        'failed' => $jobsImport->failures()->count(),
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

    public function downloadTemplate(Request $request, JobsTemplateExport $jobsTemplateExport)
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

        $fileName = 'template_jobs_import.' . $extension;

        return $jobsTemplateExport->download($fileName, $extension, ['Content-Type' => $contentType]);
    }
}
