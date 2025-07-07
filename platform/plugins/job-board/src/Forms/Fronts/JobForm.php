<?php

namespace Botble\JobBoard\Forms\Fronts;

use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Forms\Fields\CustomEditorField;
use Botble\JobBoard\Forms\JobForm as FormsJobForm;
use Botble\JobBoard\Http\Requests\AccountJobRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Job;

class JobForm extends FormsJobForm
{
    public function setup(): void
    {
        parent::setup();

        /**
         * @var Account $account
         */
        $account = auth('account')->user();
        $companies = $account->companies->pluck('name', 'id')->all();

        $this
            ->template(JobBoardHelper::viewPath('dashboard.forms.base'))
            ->hasFiles()
            ->setValidatorClass(AccountJobRequest::class)
            ->remove('is_featured')
            ->remove('moderation_status')
            ->remove('content')
            ->remove('company_id')
            ->remove('never_expired')
            ->removeMetaBox('image')
            ->when(JobBoardHelper::isUniqueIdFieldHiddenInFrontForm(), function (FormAbstract $form) {
                $form->remove('unique_id');
            })
            ->modify('auto_renew', 'onOff', [
                'label' => __(
                    'Renew automatically (you will be charged again in :days days)?',
                    ['days' => JobBoardHelper::jobExpiredDays()]
                ),
                'default_value' => false,
            ], true)
            ->addAfter('description', 'content', CustomEditorField::class, [
                'label' => trans('core/base::forms.content'),
                'attr' => [
                    'model' => Job::class,
                ],
            ])
            ->modify('tag', 'tags', [
                'attr' => [
                    'placeholder' => trans('plugins/job-board::job.write_some_tags'),
                    'data-url' => route('public.account.jobs.tags.all'),
                ],
            ]);

        if (count($companies) === 1) {
            $this->addBefore('number_of_positions', 'company_id', 'hidden', [
                'default_value' => array_key_first($companies),
            ]);
        } else {
            $this->addBefore('number_of_positions', 'company_id', 'customSelect', [
                'label' => __('Company'),
                'required' => true,
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
                'choices' => $companies,
            ]);
        }
    }
}
