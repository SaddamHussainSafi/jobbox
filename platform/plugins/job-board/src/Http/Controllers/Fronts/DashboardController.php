<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Assets;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Http\Resources\AccountResource;
use Botble\JobBoard\Http\Resources\PackageResource;
use Botble\JobBoard\Http\Resources\TransactionResource;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountActivityLog;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\JobApplication;
use Botble\JobBoard\Models\Package;
use Botble\JobBoard\Models\Transaction;
use Botble\JobBoard\Services\CouponService;
use Botble\Language\Facades\Language;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\PayPal\Services\Gateways\PayPalPaymentService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DashboardController extends BaseController
{
    public function index()
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $this->pageTitle(__('Dashboard'));

        Theme::breadcrumb()
            ->add(__('Dashboard'));

        $totalCompanies = $account->companies()->count();

        // @phpstan-ignore-next-line
        $totalJobs = Job::query()
            ->select(['jb_jobs.id'])
            ->byAccount($account->getKey())
            ->count();

        $totalApplicants = JobApplication::query()
            ->select(['jb_applications.id'])
            ->whereHas('job', function (Builder $query) use ($account): void {
                // @phpstan-ignore-next-line
                $query->byAccount($account->getKey());
            })
            ->count();

        // @phpstan-ignore-next-line
        $expiredJobs = Job::query()
            ->select([
                'id',
                'name',
                'status',
                'company_id',
                'expire_date',
            ])
            ->byAccount($account->getKey())
            ->where(function ($query): void {
                $query->where('jb_jobs.expire_date', '>=', Carbon::now())
                    ->where('jb_jobs.expire_date', '<=', Carbon::now()->addDays(30))
                    ->where('never_expired', false);
            })
            ->with('company')
            ->withCount(['applicants'])
            ->orderBy('jb_jobs.expire_date', 'asc')
            ->get();

        $newApplicants = JobApplication::query()
            ->select([
                'jb_applications.id',
                'jb_applications.first_name',
                'jb_applications.last_name',
                'jb_applications.email',
                'jb_applications.phone',
            ])
            ->whereHas('job', function (Builder $query) use ($account): void {
                // @phpstan-ignore-next-line
                $query->byAccount($account->getKey());
            })
            ->orderBy('jb_applications.created_at', 'desc')
            ->limit(10)
            ->get();

        $activities = AccountActivityLog::query()
            ->where('account_id', $account->getKey())
            ->latest('created_at')
            ->paginate(10);

        $data = compact('totalJobs', 'totalCompanies', 'totalApplicants', 'expiredJobs', 'newApplicants', 'activities');

        return JobBoardHelper::view('dashboard.index', $data);
    }

    public function getPackages()
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $this->pageTitle(__('Packages'));
        SeoHelper::setTitle(__('Packages'));

        Assets::addScriptsDirectly('vendor/core/plugins/job-board/js/components.js');
        Assets::usingVueJS();

        $account->load(['packages']);

        $packages = Package::query()
            ->wherePublished()
            ->latest('order')
            ->withCount([
                'accounts' => function ($query) use ($account): void {
                    $query->where('account_id', $account->getKey());
                },
            ])
            ->get();

        if (is_plugin_active('language') && is_plugin_active('language-advanced')) {
            Language::setCurrentAdminLocale(App::getLocale());
            LanguageAdvancedManager::initModelRelations();

            $packages->load('translations');
        }

        $paidPackages = $packages->filter(function ($package) {
            return $package->total_price > 0;
        });

        $freePackages = $packages->filter(function ($package) {
            return $package->total_price == 0;
        });

        $data = compact('paidPackages', 'freePackages', 'packages');

        return JobBoardHelper::view('dashboard.packages', $data);
    }

    public function ajaxGetPackages()
    {
        abort_unless(JobBoardHelper::isEnabledCreditsSystem(), 404);

        $account = Account::query()
            ->with(['packages'])
            ->findOrFail(auth('account')->id());

        $packages = Package::query()
            ->wherePublished()
            ->get();

        if (is_plugin_active('language') && is_plugin_active('language-advanced')) {
            Language::setCurrentAdminLocale(App::getLocale());
            LanguageAdvancedManager::initModelRelations();

            $packages->load('translations');
        }

        $packages = $packages->filter(function ($package) use ($account) {
            return $package->account_limit === null || $account->packages->where(
                'id',
                $package->id
            )->count() < $package->account_limit;
        });

        return $this
            ->httpResponse()
            ->setData([
                'packages' => PackageResource::collection($packages),
                'account' => new AccountResource($account),
            ]);
    }

    public function subscribePackage(
        Request $request,
    ) {
        $id = $request->input('id');
        abort_if(! JobBoardHelper::isEnabledCreditsSystem() || ! $id, 404);

        /**
         * @var Package $package
         */
        $package = $this->getPackageById($id);

        /**
         * @var Account $account
         */
        $account = Account::query()->findOrFail(auth('account')->id());

        abort_if($package->account_limit && $account->packages()->where(
            'package_id',
            $package->id
        )->count() >= $package->account_limit, 403);

        if ((float) $package->price) {
            session(['subscribed_packaged_id' => $package->id]);

            return $this
                ->httpResponse()

                ->setNextUrl(route('public.account.package.subscribe', $package->id))
                ->setData(['next_page' => route('public.account.package.subscribe', $package->id)]);
        }

        $this->savePayment($package, null, true);

        return $this
            ->httpResponse()

            ->setData(new AccountResource($account->refresh()))
            ->setMessage(trans('plugins/job-board::package.add_credit_success'));
    }

    protected function getPackageById(int $id)
    {
        $package = Package::query()
            ->where([
                'id' => $id,
                'status' => BaseStatusEnum::PUBLISHED,
            ])
            ->firstOrFail();

        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        if ($package->account_limit) {
            $accountLimit = $account->packages()->where('package_id', $package->getKey())->count();
            abort_if($accountLimit >= $package->account_limit, 403);
        }

        return $package;
    }

    protected function savePayment(Package $package, ?string $chargeId, bool $force = false)
    {
        abort_unless(JobBoardHelper::isEnabledCreditsSystem(), 404);

        $payment = Payment::query()
            ->where('charge_id', $chargeId)
            ->first();

        if (! $payment && ! $force) {
            return false;
        }

        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        if (($payment && $payment->status == PaymentStatusEnum::COMPLETED) || $force) {
            $account->credits += $package->number_of_listings;
            $account->save();

            $account->packages()->attach($package);
        }

        Transaction::query()->create([
            'user_id' => 0,
            'account_id' => auth('account')->id(),
            'credits' => $package->number_of_listings,
            'payment_id' => $payment?->id,
        ]);

        $emailHandler = EmailHandler::setModule(JOB_BOARD_MODULE_SCREEN_NAME);

        if (! $package->price) {
            $emailHandler
                ->setVariableValues([
                    'account_name' => $account->name,
                    'account_email' => $account->email,
                ])
                ->sendUsingTemplate('free-credit-claimed');
        } else {
            $emailHandler
                ->setVariableValues([
                    'account_name' => $account->name,
                    'account_email' => $account->email,
                    'package_name' => $package->name,
                    'package_price' => $package->price ?: 0,
                    'package_percent_discount' => $package->percent_save,
                    'package_number_of_listings' => $package->number_of_listings ?: 1,
                    'package_price_per_credit' => $package->price ? $package->price / ($package->number_of_listings ?: 1) : 0,
                ])
                ->sendUsingTemplate('payment-received');
        }

        $emailHandler
            ->setVariableValues([
                'account_name' => $account->name,
                'account_email' => $account->email,
                'package_name' => $package->name,
                'package_price' => $package->price ?: 0,
                'package_percent_discount' => $package->percent_save,
                'package_number_of_listings' => $package->number_of_listings ?: 1,
                'package_price_per_credit' => $package->price ? $package->price / ($package->number_of_listings ?: 1) : 0,
            ])
            ->sendUsingTemplate('payment-receipt', auth('account')->user()->email);

        return true;
    }

    public function getSubscribePackage(int|string $id, CouponService $service)
    {
        abort_unless(JobBoardHelper::isEnabledCreditsSystem(), 404);

        Assets::addScripts('form-validation');

        $package = $this->getPackageById($id);

        Session::put('cart_total', $package->price);

        SeoHelper::setTitle(trans('plugins/job-board::package.subscribe_package', ['name' => $package->name]));

        add_filter(PAYMENT_FILTER_AFTER_PAYMENT_METHOD, function () use ($service, $package) {
            $totalAmount = $service->getAmountAfterDiscount(
                Session::get('coupon_discount_amount', 0),
                $package->price
            );

            return view('plugins/job-board::coupons.partials.form', compact('package', 'totalAmount'));
        });

        return view(JobBoardHelper::viewPath('dashboard.checkout'), compact('package'));
    }

    public function getPackageSubscribeCallback(int $packageId, Request $request)
    {
        abort_unless(JobBoardHelper::isEnabledCreditsSystem(), 404);

        /**
         * @var Package $package
         */
        $package = $this->getPackageById($packageId);

        if (is_plugin_active('paypal') && $request->input('type') == PAYPAL_PAYMENT_METHOD_NAME) {
            $validator = Validator::make($request->input(), [
                'amount' => ['required', 'numeric'],
                'currency' => ['required'],
            ]);

            if ($validator->fails()) {
                return $this
                    ->httpResponse()
                    ->setError()->setMessage($validator->getMessageBag()->first());
            }

            $payPalService = app(PayPalPaymentService::class);

            $paymentStatus = $payPalService->getPaymentStatus($request);
            if ($paymentStatus) {
                $chargeId = session('paypal_payment_id');

                $payPalService->afterMakePayment($request->input());

                $this->savePayment($package, $chargeId);

                return $this
                    ->httpResponse()

                    ->setNextUrl(route('public.account.packages'))
                    ->setMessage(trans('plugins/job-board::package.add_credit_success'));
            }

            return $this
                ->httpResponse()

                ->setError()
                ->setNextUrl(route('public.account.packages'))
                ->setMessage($payPalService->getErrorMessage());
        }

        $this->savePayment($package, $request->input('charge_id'));

        if (! $request->has('success') || $request->input('success')) {
            return $this
                ->httpResponse()

                ->setNextUrl(route('public.account.packages'))
                ->setMessage(session()->get('success_msg') ?: trans('plugins/job-board::package.add_credit_success'));
        }

        return $this
            ->httpResponse()

            ->setError()
            ->setNextUrl(route('public.account.packages'))
            ->setMessage(__('Payment failed!'));
    }

    public function ajaxGetTransactions()
    {
        $transactions = Transaction::query()
            ->where('account_id', auth('account')->id())
            ->orderByDesc('created_at')
            ->with(['payment', 'user'])
            ->paginate(10);

        return $this
            ->httpResponse()
            ->setData(TransactionResource::collection($transactions))->toApiResponse();
    }
}
