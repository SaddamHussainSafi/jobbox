<?php

use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class () extends Migration {
    public function up(): void
    {
        if (! theme_option('job_list_page_id')) {
            $page = \Botble\Page\Models\Page::query()->create([
                'name' => 'Jobs',
                'user_id' => 1,
                'template' => 'default',
                'content' => '[job-list][/job-list]',
            ]);

            \Botble\Slug\Models\Slug::query()->create([
                'reference_type' => Page::class,
                'reference_id' => $page->id,
                'key' => Str::slug($page->name),
            ]);

            theme_option()->setOption('job_list_page_id', $page->id)->saveOptions();
        }
    }
};
