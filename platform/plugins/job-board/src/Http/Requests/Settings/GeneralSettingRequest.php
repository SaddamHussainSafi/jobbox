<?php

namespace Botble\JobBoard\Http\Requests\Settings;

use Botble\Base\Rules\MediaImageRule;
use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class GeneralSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'job_board_enable_guest_apply' => $onOffRule = new OnOffRule(),
            'job_board_enabled_register_as_employer' => $onOffRule,
            'verify_account_email' => $onOffRule,
            'verify_account_created_company' => $onOffRule,
            'job_board_enable_credits_system' => $onOffRule,
            'job_board_enable_post_approval' => $onOffRule,
            'job_expired_after_days' => ['required', 'numeric', 'min:1'],
            'job_board_job_location_display' => ['required', 'in:state_and_country,city_and_state,city_state_and_country'],
            'job_board_search_location_by' => ['required', 'in:city,city_and_state,state'],
            'job_board_zip_code_enabled' => $onOffRule,
            'job_board_enable_recaptcha_in_register_page' => $onOffRule,
            'job_board_enable_recaptcha_in_apply_job' => $onOffRule,
            'job_board_is_enabled_review_feature' => $onOffRule,
            'job_board_disabled_public_profile' => $onOffRule,
            'job_board_hide_company_email_enabled' => $onOffRule,
            'job_board_enable_lat_long_fields' => $onOffRule,
            'job_board_default_account_avatar' => ['nullable', new MediaImageRule()],
            'job_board_enabled_custom_fields_feature' => $onOffRule,
            'job_board_allow_employer_create_multiple_companies' => $onOffRule,
            'job_board_allow_employer_manage_company_info' => $onOffRule,
            'job_board_enable_pin_featured_jobs_to_the_top' => $onOffRule,
            'job_board_enable_pin_featured_companies_to_the_top' => $onOffRule,
            'job_board_accessible_expired_job' => $onOffRule,
            'job_board_listing_expired_job' => $onOffRule,
            'job_board_accessible_closed_job' => $onOffRule,
            'job_board_listing_closed_job' => $onOffRule,
            'job_board_hide_salary_for_guests' => $onOffRule,
            'job_board_hide_company_information_for_guests' => $onOffRule,
            'job_board_hide_candidate_information_for_guests' => $onOffRule,
            'job_board_only_employer_can_view_candidate_information' => $onOffRule,
            'job_board_auto_generate_unique_id' => $onOffRule,
            'job_board_unique_id_format' => ['nullable', 'string', 'max:120'],
            'job_board_hide_unique_id_field_in_admin_form' => $onOffRule,
            'job_board_hide_unique_id_field_in_front_form' => $onOffRule,
        ];
    }
}
