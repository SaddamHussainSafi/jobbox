<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\PackageForm;
use Botble\JobBoard\Http\Requests\PackageRequest;
use Botble\JobBoard\Models\Package;
use Botble\JobBoard\Tables\PackageTable;
use Illuminate\Http\Request;

class PackageController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::package.name'), route('packages.index'));
    }

    public function index(PackageTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::package.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::package.create'));

        return PackageForm::create()->renderForm();
    }

    public function store(PackageRequest $request)
    {
        if (! $request->input('price')) {
            $request->merge(['price' => 0]);
        }

        $package = Package::query()->create($request->input());

        event(new CreatedContentEvent(PACKAGE_MODULE_SCREEN_NAME, $request, $package));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('packages.index'))
            ->setNextUrl(route('packages.edit', $package->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(Package $package, Request $request)
    {
        event(new BeforeEditContentEvent($request, $package));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $package->name]));

        return PackageForm::createFromModel($package)->renderForm();
    }

    public function update(Package $package, PackageRequest $request)
    {
        if (! $request->input('price')) {
            $request->merge(['price' => 0]);
        }

        $package->fill($request->input());
        $package->save();

        event(new UpdatedContentEvent(PACKAGE_MODULE_SCREEN_NAME, $request, $package));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('packages.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Package $package)
    {
        return DeleteResourceAction::make($package);
    }
}
