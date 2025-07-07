<?php

namespace Botble\Team\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Team\Http\Requests\TeamRequest;
use Botble\Team\Models\Team;
use Illuminate\Support\Arr;

class TeamForm extends FormAbstract
{
    public function setup(): void
    {
        $social = $this->model->socials ?? [];

        $this
            ->setupModel(new Team())
            ->setValidatorClass(TeamRequest::class)
            ->withCustomFields()
            ->columns()
            ->add('name', TextField::class, NameFieldOption::make()->required()->colspan(2)->toArray())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->colspan(2)->toArray())
            ->add('content', EditorField::class, ContentFieldOption::make()->allowedShortcodes()->colspan(2)->toArray())
            ->add('title', TextField::class, [
                'label' => trans('plugins/team::team.forms.title'),
                'attr' => [
                    'placeholder' => trans('plugins/team::team.forms.title_placeholder'),
                    'data-counter' => 120,
                ],
                'colspan' => 2,
            ])
            ->add('phone', 'number', [
                'label' => trans('plugins/team::team.forms.phone_number'),
                'attr' => [
                    'placeholder' => trans('plugins/team::team.forms.phone_number'),
                    'data-counter' => 120,
                ],
                'colspan' => 2,
            ])
            ->add('email', TextField::class, [
                'label' => trans('plugins/team::team.forms.email'),
                'attr' => [
                    'placeholder' => trans('plugins/team::team.forms.email'),
                    'data-counter' => 120,
                ],
            ])
            ->add('address', TextField::class, [
                'label' => trans('plugins/team::team.forms.address'),
                'attr' => [
                    'placeholder' => trans('plugins/team::team.forms.address'),
                    'data-counter' => 120,
                ],
            ])
            ->add('website', TextField::class, [
                'label' => trans('plugins/team::team.forms.website'),
                'attr' => [
                    'placeholder' => trans('plugins/team::team.forms.website'),
                    'data-counter' => 120,
                ],
            ])
            ->add('location', TextField::class, [
                'label' => trans('plugins/team::team.forms.location'),
                'attr' => [
                    'placeholder' => trans('plugins/team::team.forms.location_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('socials[facebook]', TextField::class, [
                'label' => trans('plugins/team::team.forms.socials_facebook'),
                'value' => Arr::get($social, 'facebook'),
                'attr' => [
                    'placeholder' => trans('plugins/team::team.forms.socials_facebook_placeholder'),
                    'data-counter' => 120,
                ],
                'colspan' => 2,
            ])
            ->add('socials[twitter]', TextField::class, [
                'label' => trans('plugins/team::team.forms.socials_twitter'),
                'value' => Arr::get($social, 'twitter'),
                'attr' => [
                    'placeholder' => trans('plugins/team::team.forms.socials_twitter_placeholder'),
                    'data-counter' => 120,
                ],
                'colspan' => 2,
            ])
            ->add('socials[instagram]', TextField::class, [
                'label' => trans('plugins/team::team.forms.socials_instagram'),
                'value' => Arr::get($social, 'instagram'),
                'attr' => [
                    'placeholder' => trans('plugins/team::team.forms.socials_instagram_placeholder'),
                    'data-counter' => 120,

                ],
                'colspan' => 2,
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add(
                'photo',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(trans('plugins/team::team.forms.photo'))
                    ->toArray()
            )
            ->setBreakFieldPoint('status');
    }
}
