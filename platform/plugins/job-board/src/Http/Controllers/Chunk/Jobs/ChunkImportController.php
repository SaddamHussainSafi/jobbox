<?php

namespace Botble\JobBoard\Http\Controllers\Chunk\Jobs;

use Botble\JobBoard\Http\Controllers\Chunk\ChunkController;
use Botble\JobBoard\Http\Requests\ChunkFileRequest;
use Botble\JobBoard\Imports\JobsImport;
use Exception;
use Illuminate\Support\Facades\File;

class ChunkImportController extends ChunkController
{
    public function __invoke(ChunkFileRequest $request, JobsImport $jobsImport)
    {
        try {
            $filePath = $this->getFilePath($request->input('file'), 'app/job-import');

        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }

        $offset = $request->integer('offset');
        $limit = $request->integer('limit', 10);
        $rows = $this->getLocationRows($filePath, $offset, $limit);
        $rowsCount = count($rows);
        $total = $offset + $rowsCount;

        if ($rowsCount <= 0) {
            File::delete($filePath);

            return $this
                ->httpResponse()
                ->setMessage(trans('plugins/job-board::import.import_success_message'))
                ->setData([
                    'total_message' => trans('plugins/job-board::import.total_rows', [
                        'total' => number_format($total),
                    ]),
                ]);
        }

        $jobsImport->setUpdateExisting($request->boolean('update_existing'))->handle($rows);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/job-board::import.importing_message', [
                'from' => number_format($offset),
                'to' => number_format($total),
            ]))
            ->setData([
                'offset' => $total,
                'count' => $rowsCount,
            ]);
    }
}
