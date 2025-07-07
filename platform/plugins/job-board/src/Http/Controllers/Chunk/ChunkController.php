<?php

namespace Botble\JobBoard\Http\Controllers\Chunk;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Concerns\ChunkFile;
use Botble\Media\Chunks\Exceptions\UploadMissingFileException;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File as ValidationFile;

class ChunkController extends BaseController
{
    use ChunkFile;

    public function __construct()
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();
    }
    protected function uploadFile(FileReceiver $receiver, string $sessionId, string $basePath = null)
    {
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if (! $save->isFinished()) {
            $handler = $save->handler();

            return $this
                ->httpResponse()
                ->setData([
                    'done' => $handler->getPercentageDone(),
                    'status' => true,
                ]);
        }

        $file = $save->getFile();

        Validator::validate(['uploaded_file' => $file], [
            'uploaded_file' => ['required', ValidationFile::types(['csv', 'xls', 'xlsx'])],
        ]);

        $filePath = sprintf('%s.%s', $sessionId, $file->getClientOriginalExtension());
        $file->move(storage_path($basePath ?: 'app/job-import'), $filePath);

        return $this
            ->httpResponse()
            ->setData([
                'file_path' => $filePath,
            ]);
    }

    protected function validator(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);

        $failed = [];

        foreach ($validator->errors()->toArray() as $index => $errors) {
            $failed[] = [
                'row' => $index,
                'errors' => $errors,
            ];
        }

        return $failed;
    }
}
