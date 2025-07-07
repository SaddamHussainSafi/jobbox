<?php

namespace Botble\JobBoard\Forms\Fronts;

use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Forms\JobApplicationForm;
use Botble\JobBoard\Http\Requests\EditJobApplicationRequest;
use Botble\JobBoard\Models\JobApplication;

class ApplicantForm extends JobApplicationForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setupModel(new JobApplication())
            ->setValidatorClass(EditJobApplicationRequest::class)
            ->template(JobBoardHelper::viewPath('dashboard.forms.base'));
    }
}
