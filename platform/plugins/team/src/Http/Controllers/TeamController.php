<?php

namespace Botble\Team\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Team\Forms\TeamForm;
use Botble\Team\Http\Requests\TeamRequest;
use Botble\Team\Models\Team;
use Botble\Team\Tables\TeamTable;
use Exception;
use Illuminate\Http\Request;

class TeamController extends BaseController
{
    public function index(TeamTable $table)
    {
        PageTitle::setTitle(trans('plugins/team::team.name'));

        return $table->renderTable();
    }

    public function create()
    {
        PageTitle::setTitle(trans('plugins/team::team.create'));

        return TeamForm::create()->renderForm();
    }

    public function store(TeamRequest $request)
    {
        $team = Team::query()->create($request->validated());

        event(new CreatedContentEvent(TEAM_MODULE_SCREEN_NAME, $request, $team));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('team.index'))
            ->setNextUrl(route('team.edit', $team->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Team $team, Request $request)
    {
        event(new BeforeEditContentEvent($request, $team));

        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $team->name]));

        return TeamForm::createFromModel($team)->renderForm();
    }

    public function update(Team $team, TeamRequest $request)
    {
        $team->update($request->validated());

        event(new UpdatedContentEvent(TEAM_MODULE_SCREEN_NAME, $request, $team));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('team.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Team $team, Request $request)
    {
        try {
            $team->delete();

            event(new DeletedContentEvent(TEAM_MODULE_SCREEN_NAME, $request, $team));

            return $this
                ->httpResponse()
                ->withDeletedSuccessMessage();
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
