<?php

namespace Botble\JobBoard\Http\Controllers\Chunk\Jobs;

use Botble\JobBoard\Http\Controllers\Chunk\ChunkController;
use Botble\Media\Chunks\Handler\DropZoneUploadHandler;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChunkUploadController extends ChunkController
{
    public function __invoke(Request $request)
    {
        $receiver = new FileReceiver('file', $request, DropZoneUploadHandler::class);
        $sessionId = $request->input('dzuuid', Str::uuid());

        return $this->uploadFile($receiver, $sessionId, 'app/job-import');
    }
}
