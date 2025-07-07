@if($company->jobs_count == 1)
    {{ __(':count Opening Job', ['count' => $company->jobs_count]) }}
@elseif($company->jobs_count > 1)
    {{ __(':count Opening Jobs', ['count' => $company->jobs_count]) }}
@else
    {{ __('No Opening Job') }}
@endif
