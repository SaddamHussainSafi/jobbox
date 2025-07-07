<?php

namespace Botble\JobBoard;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::disableForeignKeyConstraints();

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
        Schema::dropIfExists('jb_jobs_types');
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
        Schema::dropIfExists('jb_account_favorite_skills');
        Schema::dropIfExists('jb_account_favorite_tags');
        Schema::dropIfExists('jb_account_experiences');
        Schema::dropIfExists('jb_account_educations');

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
        Schema::dropIfExists('jb_tags');
        Schema::dropIfExists('jb_jobs_tags');
        Schema::dropIfExists('jb_jobs_tags_translations');

        Schema::dropIfExists('jb_invoice_items');
        Schema::dropIfExists('jb_invoices');

        Schema::dropIfExists('jb_reviews');

        Schema::dropIfExists('jb_tags_translations');

        Schema::dropIfExists('jb_educations');
        Schema::dropIfExists('jb_experiences');

        Schema::dropIfExists('jb_custom_fields');
        Schema::dropIfExists('jb_custom_field_options');
        Schema::dropIfExists('jb_custom_field_values');
        Schema::dropIfExists('jb_packages_translations');

        Schema::dropIfExists('jb_custom_fields_translations');
        Schema::dropIfExists('jb_custom_field_options_translations');
        Schema::dropIfExists('jb_custom_field_values_translations');

        Schema::dropIfExists('jb_accounts_translations');

        Setting::delete([
            'job_board_add_space_between_price_and_currency',
            'job_board_company_address_for_invoicing',
            'job_board_company_email_for_invoicing',
            'job_board_company_logo_for_invoicing',
            'job_board_company_name_for_invoicing',
            'job_board_company_phone_for_invoicing',
            'job_board_decimal_separator',
            'job_board_enable_auto_detect_visitor_currency',
            'job_board_enable_credits_system',
            'job_board_enable_guest_apply',
            'job_board_enable_invoice_stamp',
            'job_board_enable_post_approval',
            'job_board_enabled_register',
            'job_board_invoice_code_prefix',
            'job_board_invoice_font_family',
            'job_board_invoice_support_arabic_language',
            'job_board_thousands_separator',
            'job_board_using_custom_font_for_invoice',
            'job_expired_after_days',
            'job_board_job_location_display',
            'currencies_is_default',
            'verify_account_email',
        ]);
    }
}
