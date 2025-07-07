<?php

namespace Botble\JobBoard\Forms;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Http\Requests\AccountEducationRequest;
use Botble\JobBoard\Models\AccountEducation;

class AccountEducationForm extends FormAbstract
{
    public function setup(): void
    {
        $data = $this->getData();

        $this
            ->setupModel(new AccountEducation())
            ->setValidatorClass(AccountEducationRequest::class)
            ->contentOnly()
            ->setUrl(route('accounts.educations.store'))
            ->add('school', 'text', [
                'label' => trans('plugins/job-board::account.form.school'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/job-board::account.form.school_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('account_id', 'hidden', [
                'label' => 'account',
                'required' => true,
                'value' => $data['account']->id ?? $this->getModel()->account_id,
            ])
            ->add('specialized', 'text', [
                'label' => trans('plugins/job-board::account.form.specialized'),
                'attr' => [
                    'placeholder' => trans('plugins/job-board::account.form.specialized_placeholder'),
                ],
            ])
            ->add('started_at', 'date', [
                'label' => trans('plugins/job-board::account.form.started_at'),
                'required' => true,
                'value' => $this->getModel()->id ? BaseHelper::formatDate($this->getModel()->started_at) : '',
            ])
            ->add('ended_at', 'date', [
                'label' => trans('plugins/job-board::account.form.ended_at'),
                'value' => $this->getModel()->id ? BaseHelper::formatDate($this->getModel()->ended_at) : '',
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
