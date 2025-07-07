<?php

namespace Botble\JobBoard\Services;

use Botble\Base\Events\CreatedContentEvent;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\Tag;
use Illuminate\Http\Request;

class StoreTagService
{
    public function execute(Request $request, Job $job): void
    {
        $tags = $job->tags->pluck('name')->all();

        $tagsInput = collect(json_decode((string) $request->input('tag'), true))->pluck('value')->all();

        if (count($tags) != count($tagsInput) || count(array_diff($tags, $tagsInput)) > 0) {
            $job->tags()->detach();
            foreach ($tagsInput as $tagName) {
                if (! trim($tagName)) {
                    continue;
                }

                $tag = Tag::query()->where('name', $tagName)->first();

                if ($tag === null && ! empty($tagName)) {
                    $tag = Tag::query()->create(['name' => $tagName]);

                    $request->merge(['slug' => $tagName]);

                    event(new CreatedContentEvent(JOB_BOARD_TAG_MODULE_SCREEN_NAME, $request, $tag));
                }

                if (! empty($tag)) {
                    $job->tags()->attach($tag->id);
                }
            }
        }
    }
}
