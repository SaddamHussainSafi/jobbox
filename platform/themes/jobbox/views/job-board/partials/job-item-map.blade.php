<div
    @class(['job-box col-xl-12 col-12 job-items', 'bookmark-post' => $job->is_saved])
    data-latitude="{{ $job->latitude }}"
    data-longitude="{{ $job->longitude }}"
    data-company_logo_thumb="{{ $job->company->logo_thumb }}"
    data-company_name="{{ $job->company_name ?: $job->name }}"
    data-map_icon="{{ ! JobBoardHelper::isSalaryHiddenForGuests() ? $job->salary_text : $job->name }}"
    data-job_name="{{ $job->name }}"
    data-company_url="{{ $job->company_url }}"
    data-job_types="{{ json_encode($job->jobTypes->toArray()) }}"
    data-number_of_positions="{{ $job->number_of_positions }}"
    data-full_address="{{ $job->full_address }}"
>

</div>
