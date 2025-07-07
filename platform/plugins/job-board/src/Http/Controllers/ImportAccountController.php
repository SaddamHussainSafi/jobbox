<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Exports\AccountsTemplateExport;
use Botble\JobBoard\Http\Requests\AccountImportRequest;
use Botble\JobBoard\Http\Requests\BulkImportRequest;
use Botble\JobBoard\Imports\AccountsImport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportAccountController extends BaseController
{
    public function index(AccountsTemplateExport $templateExport): View
    {
        $this->pageTitle(trans('plugins/job-board::account.import.name'));

        Assets::addScriptsDirectly('vendor/core/plugins/job-board/js/bulk-import.js');

        $accounts = $templateExport->collection();
        $rules = $templateExport->rules();
        $headings = $templateExport->headings();

        return view('plugins/job-board::accounts.import', compact('accounts', 'rules', 'headings'));
    }

    public function import(BulkImportRequest $request, AccountsImport $candidatesImport)
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        try {
            $candidatesImport->validator(AccountImportRequest::class)->import($request->file('file'));

            $message = trans('plugins/job-board::import.import_success_message');

            return $this
                ->httpResponse()
                ->setData([
                    'message' => $message . ' ' . trans('plugins/job-board::import.results', [
                        'success' => $candidatesImport->successes()->count(),
                        'failed' => $candidatesImport->failures()->count(),
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

    public function downloadTemplate(Request $request, AccountsTemplateExport $templateExport): BinaryFileResponse
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

        return $templateExport->download("template_accounts.$extension", $extension, ['Content-Type' => $contentType]);
    }
}
