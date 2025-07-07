<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Forms\Fronts\AccountLanguageForm;
use Botble\JobBoard\Forms\Fronts\AccountSettingForm;
use Botble\JobBoard\Http\Requests\AvatarRequest;
use Botble\JobBoard\Http\Requests\SettingRequest;
use Botble\JobBoard\Http\Requests\UpdatePasswordRequest;
use Botble\JobBoard\Http\Requests\UploadResumeRequest;
use Botble\JobBoard\Http\Resources\ActivityLogResource;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountActivityLog;
use Botble\JobBoard\Models\AccountEducation;
use Botble\JobBoard\Models\AccountExperience;
use Botble\JobBoard\Models\JobSkill;
use Botble\JobBoard\Models\Tag;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Botble\Media\Services\ThumbnailService;
use Botble\Optimize\Facades\OptimizerHelper;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends BaseController
{
    public function __construct()
    {
        OptimizerHelper::disable();
    }

    public function getOverview()
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        SeoHelper::setTitle($account->name);
        Theme::breadcrumb()
            ->add(__('My Profile'), route('public.account.overview'))
            ->add($account->name);

        $educations = AccountEducation::query()
            ->where('account_id', $account->id)
            ->get();

        $experiences = AccountExperience::query()
            ->where('account_id', $account->id)
            ->get();

        $data = compact('account', 'educations', 'experiences');

        return JobBoardHelper::scope('account.overview', $data);
    }

    public function getSettings()
    {
        SeoHelper::setTitle(__('Account settings'));
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $form = AccountSettingForm::createFromModel($account);

        $selectedJobSkills = $account->favoriteSkills()->pluck('jb_job_skills.id')->all();

        $jobSkills = JobSkill::query()
            ->wherePublished()
            ->select(['id', 'name'])
            ->get();

        $selectedJobTags = $account->favoriteTags()->pluck('jb_tags.id')->all();

        $jobTags = Tag::query()
            ->wherePublished()
            ->select(['id', 'name'])
            ->get();

        $languages = $account->languages()->with('languageLevel')->get();

        $languageForm = AccountLanguageForm::create();

        return JobBoardHelper::scope(
            'account.settings.index',
            compact('account', 'jobSkills', 'jobTags', 'selectedJobSkills', 'selectedJobTags', 'languages', 'form', 'languageForm')
        );
    }

    public function postSettings(SettingRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();
        $data = $request->validated();
        Arr::forget($data, ['resume', 'cover_letter']);

        if ($request->hasFile('resume')) {
            $result = RvMedia::handleUpload($request->file('resume'), 0, $account->upload_folder);

            if (! $result['error']) {
                if ($path = $account->resume) {
                    Storage::disk('public')->delete($path);
                }

                $data['resume'] = $result['data']->url;
            }
        }

        if ($request->hasFile('cover_letter')) {
            $result = RvMedia::handleUpload($request->file('cover_letter'), 0, $account->upload_folder);

            if (! $result['error']) {
                if ($path = $account->cover_letter) {
                    Storage::disk('public')->delete($path);
                }

                $data['cover_letter'] = $result['data']->url;
            }
        }

        AccountSettingForm::createFromModel($account)
            ->saving(function (AccountSettingForm $form) use ($data): void {
                $model = $form->getModel();

                $model->fill($data);
                $model->save();
            });

        AccountActivityLog::query()->create(['action' => 'update_setting']);

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.settings'))
            ->setMessage(__('Update profile successfully!'));
    }

    public function getSecurity()
    {
        SeoHelper::setTitle(__('Security'));

        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        return JobBoardHelper::scope('account.settings.security', compact('account'));
    }

    public function postSecurity(UpdatePasswordRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        if (! Hash::check($request->input('old_password'), $account->getAuthPassword())) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/job-board::dashboard.current_password_incorrect'));
        }

        $account->update([
            'password' => Hash::make($request->input('password')),
        ]);

        AccountActivityLog::query()->create(['action' => 'update_security']);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/job-board::dashboard.password_update_success'));
    }

    public function postAvatar(AvatarRequest $request, ThumbnailService $thumbnailService)
    {
        try {
            $account = auth('account')->user();

            $result = RvMedia::handleUpload($request->file('avatar_file'), 0, $account->upload_folder);

            if ($result['error']) {
                return $this
                    ->httpResponse()->setError()->setMessage($result['message']);
            }

            $avatarData = json_decode($request->input('avatar_data'));

            $file = $result['data'];

            $fileUpload = RvMedia::getRealPath($file->url);

            if (RvMedia::isUsingCloud()) {
                $fileUpload = @file_get_contents($fileUpload);
            }

            $thumbnailService
                ->setImage($fileUpload)
                ->setSize((int) $avatarData->width, (int) $avatarData->height)
                ->setCoordinates((int) $avatarData->x, (int) $avatarData->y)
                ->setDestinationPath(File::dirname($file->url))
                ->setFileName(File::name($file->url) . '.' . File::extension($file->url))
                ->save('crop');

            $avatar = MediaFile::query()->find($account->avatar_id);

            if ($avatar) {
                $avatar->forceDelete();
            }

            $account->avatar_id = $file->id;
            $account->save();

            AccountActivityLog::query()->create([
                'action' => 'changed_avatar',
            ]);

            return $this
                ->httpResponse()
                ->setMessage(trans('plugins/job-board::dashboard.update_avatar_success'))
                ->setData(['url' => RvMedia::url($file->url)]);
        } catch (Exception $ex) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    public function deleteAvatar()
    {
        try {
            $account = auth('account')->user();

            if (! $account->avatar_id) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage(trans('plugins/job-board::dashboard.avatar_not_found'));
            }

            $avatar = MediaFile::query()->find($account->avatar_id);

            if ($avatar) {
                $avatar->forceDelete();
            }

            $account->update([
                'avatar_id' => null,
            ]);

            return $this
                    ->httpResponse()
                    ->withDeletedSuccessMessage();

        } catch (Exception $ex) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    public function getActivityLogs()
    {
        $activities = AccountActivityLog::query()
            ->where('account_id', auth('account')->id())
            ->latest('created_at')
            ->paginate(10);

        return $this
            ->httpResponse()
            ->setData(ActivityLogResource::collection($activities))
            ->toApiResponse();
    }

    public function postUpload(UploadResumeRequest $request)
    {
        $account = auth('account')->user();

        $result = RvMedia::handleUpload($request->file('file'), 0, $account->upload_folder);

        if ($result['error']) {
            return $this
                ->httpResponse()
                ->setError();
        }

        return $this
            ->httpResponse()
            ->setData($result['data']);
    }

    public function postUploadFromEditor(Request $request)
    {
        $account = auth('account')->user();

        return RvMedia::uploadFromEditor($request, 0, $account->upload_folder);
    }

    public function postUploadResume(UploadResumeRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $result = RvMedia::handleUpload($request->file('file'), 0, $account->upload_folder);

        if ($result['error']) {
            return $this
                ->httpResponse()->setError();
        }

        $account->update(['resume' => $result['data']->url]);

        $url = null;
        if (! $account->phone) {
            $url = route('public.account.settings');
        }

        return $this
            ->httpResponse()
            ->setData(compact('url'));
    }
}
