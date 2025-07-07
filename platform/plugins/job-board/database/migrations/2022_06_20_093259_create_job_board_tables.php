<?php

use Botble\ACL\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jb_currencies', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 60);
            $table->string('symbol', 10);
            $table->tinyInteger('is_prefix_symbol')->unsigned()->default(0);
            $table->tinyInteger('decimals')->unsigned()->default(0);
            $table->integer('order')->default(0)->unsigned();
            $table->tinyInteger('is_default')->default(0);
            $table->double('exchange_rate')->default(1);
            $table->timestamps();
        });

        Schema::create('jb_jobs', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('apply_url')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('country_id')->default(1)->nullable();
            $table->foreignId('state_id')->nullable();
            $table->foreignId('city_id')->nullable();
            $table->tinyInteger('is_freelance')->unsigned()->default(0);
            $table->foreignId('career_level_id')->nullable();
            $table->decimal('salary_from', 15, 0)->unsigned()->nullable();
            $table->decimal('salary_to', 15, 0)->unsigned()->nullable();
            $table->string('salary_range', 30)->default('hour');
            $table->foreignId('currency_id')->nullable();
            $table->foreignId('degree_level_id')->nullable();
            $table->foreignId('job_type_id')->nullable();
            $table->foreignId('job_shift_id')->nullable();
            $table->foreignId('job_experience_id')->nullable();
            $table->foreignId('functional_area_id')->nullable();
            $table->boolean('hide_salary')->default(false);
            $table->integer('number_of_positions')->unsigned()->default(1);
            $table->date('expire_date')->nullable();
            $table->foreignId('author_id')->nullable();
            $table->string('author_type')->default(addslashes(User::class));
            $table->integer('views')->unsigned()->default(0);
            $table->integer('number_of_applied')->unsigned()->default(0);
            $table->boolean('hide_company')->default(false);
            $table->string('latitude', 25)->nullable();
            $table->string('longitude', 25)->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->integer('external_apply_clicks')->unsigned()->default(0);
            $table->boolean('never_expired')->default(false);
            $table->tinyInteger('is_featured')->default(0);
            $table->string('status', 60)->default('published');
            $table->string('moderation_status', 60)->default('pending');
            $table->timestamps();
        });

        Schema::create('jb_job_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_job_skills', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_job_shifts', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_job_experiences', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_language_levels', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_career_levels', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_functional_areas', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('description', 400)->nullable();
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_jobs_categories', function (Blueprint $table): void {
            $table->foreignId('job_id');
            $table->foreignId('category_id');
        });

        Schema::create('jb_degree_levels', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_degree_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->foreignId('degree_level_id');
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_major_subjects', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_jobs_skills', function (Blueprint $table): void {
            $table->foreignId('job_id');
            $table->foreignId('job_skill_id');
        });

        Schema::create('jb_applications', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name', 120)->nullable();
            $table->string('last_name', 120)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 60);
            $table->text('message')->nullable();
            $table->foreignId('job_id');
            $table->string('resume')->nullable();
            $table->string('cover_letter')->nullable();
            $table->foreignId('account_id')->nullable();
            $table->boolean('is_external_apply')->default(false);
            $table->string('status', 60)->default('pending');
            $table->timestamps();
        });

        Schema::create('jb_analytics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_id');
            $table->string('country', 10)->nullable();
            $table->string('country_full', 50)->nullable();
            $table->string('referer', 300)->nullable();
            $table->string('ip_address', 300);
            $table->timestamps();
        });

        Schema::create('jb_packages', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->double('price', 15, 2)->unsigned();
            $table->foreignId('currency_id');
            $table->integer('percent_save')->unsigned()->default(0)->nullable();
            $table->integer('number_of_listings')->unsigned();
            $table->tinyInteger('order')->default(0);
            $table->integer('account_limit')->unsigned()->nullable();
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jb_accounts', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name', 120);
            $table->string('last_name', 120);
            $table->text('description')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('avatar_id')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone', 25)->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->string('email_verify_token', 120)->nullable();
            $table->string('type', 30)->default('job-seeker');
            $table->integer('credits')->unsigned()->nullable();
            $table->string('resume', 200)->nullable();
            $table->string('address', 250)->nullable();
            $table->mediumText('bio')->nullable();
            $table->tinyInteger('is_public_profile')->unsigned()->default(0);
            $table->bigInteger('views')->unsigned()->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('jb_account_password_resets', function (Blueprint $table): void {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('jb_account_activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('action', 120);
            $table->text('user_agent')->nullable();
            $table->string('reference_url')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('ip_address', 39)->nullable();
            $table->foreignId('account_id')->index();
            $table->timestamps();
        });

        Schema::create('jb_companies', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120)->nullable();
            $table->string('email', 60)->nullable();
            $table->string('description', 400)->nullable();
            $table->mediumText('content')->nullable();
            $table->string('website', 120)->nullable();
            $table->string('logo', 120)->nullable();
            $table->string('latitude', 25)->nullable();
            $table->string('longitude', 25)->nullable();
            $table->string('address', 250)->nullable();
            $table->foreignId('country_id')->default(1)->nullable();
            $table->foreignId('state_id')->nullable();
            $table->foreignId('city_id')->nullable();
            $table->string('postal_code', 30)->nullable();
            $table->string('phone', 30)->nullable();
            $table->integer('year_founded')->unsigned()->nullable();
            $table->string('ceo', 120)->nullable();
            $table->integer('number_of_offices')->unsigned()->nullable();
            $table->string('number_of_employees', 60)->nullable();
            $table->string('annual_revenue', 60)->nullable();
            $table->string('cover_image', 120)->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->tinyInteger('is_featured')->default(0);
            $table->string('status', 60)->default('published');
            $table->bigInteger('views')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('jb_transactions', function (Blueprint $table): void {
            $table->id();
            $table->integer('credits')->unsigned();
            $table->string('description', 400)->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('account_id')->nullable();
            $table->string('type')->default('add');

            $table->foreignId('payment_id')->nullable();
            $table->timestamps();
        });

        Schema::create('jb_saved_jobs', function (Blueprint $table): void {
            $table->foreignId('account_id');
            $table->foreignId('job_id');
            $table->primary(['account_id', 'job_id']);
        });

        Schema::create('jb_account_packages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_id');
            $table->foreignId('package_id');
            $table->timestamps();
        });

        Schema::create('jb_companies_accounts', function (Blueprint $table): void {
            $table->foreignId('company_id');
            $table->foreignId('account_id');
        });

        Schema::create('jb_jobs_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_jobs_id');
            $table->string('name')->nullable();
            $table->string('description', 400)->nullable();
            $table->longText('content')->nullable();

            $table->primary(['lang_code', 'jb_jobs_id'], 'jb_jobs_translations_primary');
        });

        Schema::create('jb_career_levels_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_career_levels_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_career_levels_id'], 'jb_career_levels_translations_primary');
        });

        Schema::create('jb_categories_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_categories_id');
            $table->string('name')->nullable();
            $table->string('description', 400)->nullable();

            $table->primary(['lang_code', 'jb_categories_id'], 'jb_categories_translations_primary');
        });

        Schema::create('jb_degree_levels_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_degree_levels_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_degree_levels_id'], 'jb_degree_levels_translations_primary');
        });

        Schema::create('jb_degree_types_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_degree_types_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_degree_types_id'], 'jb_degree_types_translations_primary');
        });

        Schema::create('jb_functional_areas_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_functional_areas_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_functional_areas_id'], 'jb_functional_areas_translations_primary');
        });

        Schema::create('jb_job_experiences_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_job_experiences_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_job_experiences_id'], 'jb_job_experiences_translations_primary');
        });

        Schema::create('jb_job_shifts_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_job_shifts_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_job_shifts_id'], 'jb_job_shifts_translations_primary');
        });

        Schema::create('jb_job_skills_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_job_skills_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_job_skills_id'], 'jb_job_skills_translations_primary');
        });

        Schema::create('jb_job_types_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_job_types_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_job_types_id'], 'jb_job_types_translations_primary');
        });

        Schema::create('jb_language_levels_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_language_levels_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_language_levels_id'], 'jb_language_levels_translations_primary');
        });

        Schema::create('jb_packages_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('jb_packages_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'jb_packages_id'], 'jb_packages_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jb_currencies');
        Schema::dropIfExists('jb_analytics');
        Schema::dropIfExists('jb_applications');
        Schema::dropIfExists('jb_jobs');
        Schema::dropIfExists('jb_career_levels');
        Schema::dropIfExists('jb_categories');
        Schema::dropIfExists('jb_degree_levels');
        Schema::dropIfExists('jb_degree_types');
        Schema::dropIfExists('jb_job_experiences');
        Schema::dropIfExists('jb_functional_areas');
        Schema::dropIfExists('jb_job_shifts');
        Schema::dropIfExists('jb_job_skills');
        Schema::dropIfExists('jb_job_types');
        Schema::dropIfExists('jb_language_levels');
        Schema::dropIfExists('jb_jobs_categories');
        Schema::dropIfExists('jb_major_subjects');
        Schema::dropIfExists('jb_jobs_skills');
        Schema::dropIfExists('jb_account_packages');
        Schema::dropIfExists('jb_saved_jobs');
        Schema::dropIfExists('jb_transactions');
        Schema::dropIfExists('jb_companies');
        Schema::dropIfExists('jb_account_activity_logs');
        Schema::dropIfExists('jb_account_password_resets');
        Schema::dropIfExists('jb_accounts');
        Schema::dropIfExists('jb_packages');
        Schema::dropIfExists('jb_companies_accounts');

        Schema::dropIfExists('jb_jobs_translations');
        Schema::dropIfExists('jb_career_levels_translations');
        Schema::dropIfExists('jb_categories_translations');
        Schema::dropIfExists('jb_degree_levels_translations');
        Schema::dropIfExists('jb_degree_types_translations');
        Schema::dropIfExists('jb_job_experiences_translations');
        Schema::dropIfExists('jb_functional_areas_translations');
        Schema::dropIfExists('jb_job_shifts_translations');
        Schema::dropIfExists('jb_job_skills_translations');
        Schema::dropIfExists('jb_job_types_translations');
        Schema::dropIfExists('jb_language_levels_translations');
        Schema::dropIfExists('jb_packages_translations');
    }
};
