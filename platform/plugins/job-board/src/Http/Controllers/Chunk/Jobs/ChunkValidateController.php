<?php

namespace Botble\JobBoard\Http\Controllers\Chunk\Jobs;

use Botble\JobBoard\Enums\JobStatusEnum;
use Botble\JobBoard\Enums\ModerationStatusEnum;
use Botble\JobBoard\Http\Controllers\Chunk\ChunkController;
use Botble\JobBoard\Http\Requests\ChunkFileRequest;
use Botble\JobBoard\Models\Job;
use Exception;
use Illuminate\Validation\Rule;

class ChunkValidateController extends ChunkController
{
    public function __invoke(ChunkFileRequest $request)
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
        $limit = $request->integer('limit', 1000);
        $rows = $this->getLocationRows($filePath, $offset, $limit);
        $failed = [];

        foreach ($rows as $key => $row) {
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:400',
                'content' => 'nullable|string',
                'apply_url' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'is_freelance' => 'nullable|string|in:Yes,No',
                'salary_from' => 'nullable|numeric|min:0',
                'salary_to' => 'nullable|numeric|min:0',
                'salary_range' => 'nullable|string|max:255',
                'hide_salary' => 'nullable|string|in:Yes,No',
                'number_of_positions' => 'nullable|integer|min:0',
                'expire_date' => 'nullable|sometimes|date',
                'hide_company' => 'nullable|string|in:Yes,No',
                'latitude' => 'nullable|max:20',
                'longitude' => 'nullable|max:20',
                'start_date' => 'nullable|sometimes|date',
                'application_closing_date' => 'nullable|sometimes|date',
                'never_expired' => 'nullable|string|in:Yes,No',
                'moderation_status' => ['nullable', Rule::in(ModerationStatusEnum::values())],
                'status' => ['nullable', Rule::in(JobStatusEnum::values())],
            ];

            // Filter out empty values before validation
            $filteredRow = array_filter($row, function ($value) {
                return $value !== null && $value !== '';
            });

            $validator = validator()->make($filteredRow, $rules);

            if ($validator->fails()) {
                $failed[] = [
                    'row' => $offset + $key + 1,
                    'errors' => $validator->errors()->all(),
                ];
            }

            // Check if job exists by ID or unique_id and update_existing is not enabled
            if (! $request->boolean('update_existing')) {
                $existingJob = false;

                // Check by ID if provided
                if (! empty($row['id'])) {
                    $existingJob = Job::query()->where('id', $row['id'])->exists();
                    if ($existingJob) {
                        $failed[] = [
                            'row' => $offset + $key + 1,
                            'errors' => [
                                trans('plugins/job-board::import.id_exists', ['id' => $row['id']]),
                            ],
                        ];
                    }
                }

                // Check by unique_id if provided and not already failed
                if (! $existingJob && ! empty($row['unique_id'])) {
                    $existingJob = Job::query()->where('unique_id', $row['unique_id'])->exists();
                    if ($existingJob) {
                        $failed[] = [
                            'row' => $offset + $key + 1,
                            'errors' => [
                                trans('plugins/job-board::import.unique_id_exists', ['id' => $row['unique_id']]),
                            ],
                        ];
                    }
                }
            }
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/job-board::import.validating_message', [
                'from' => number_format($offset),
                'to' => number_format($offset + count($rows)),
            ]))
            ->setData([
                'offset' => $offset + count($rows),
                'count' => count($rows),
                'failed' => $failed,
            ]);
    }
}
