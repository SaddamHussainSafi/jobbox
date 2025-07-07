<?php

namespace Botble\JobBoard\Forms;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Http\Requests\AccountExperienceRequest;
use Botble\JobBoard\Models\AccountExperience;

class AccountExperienceForm extends FormAbstract
{
    public function setup(): void
    {
        $data = $this->getData();

        $this
            ->setupModel(new AccountExperience())
            ->setValidatorClass(AccountExperienceRequest::class)
            ->contentOnly()
            ->setFormOptions([
                'url' => route('accounts.experiences.store'),
            ])
            ->add('company', 'text', [
                'label' => trans('plugins/job-board::account.form.company'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/job-board::account.form.company_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('account_id', 'hidden', [
                'label' => 'Account',
                'required' => true,
                'value' => $data['account']->id ?? $this->getModel()->account_id,
            ])
            ->add('position', 'text', [
                'label' => trans('plugins/job-board::account.form.position'),
                'attr' => [
                    'placeholder' => trans('plugins/job-board::account.form.position_placeholder'),
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
