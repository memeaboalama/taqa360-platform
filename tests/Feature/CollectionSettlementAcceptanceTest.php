<?php

namespace Tests\Feature;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Domain\AppBase\Models\RefundPayment;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Setting\Currency;
use App\Domain\MarketPlace\Models\DriverCollectionFailureReport;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Models\OrderSettlementHistory;
use App\Domain\MarketPlace\Models\PaymentCorrectionRequest;
use App\Domain\User\Models\UserAddress;
use App\Domain\MarketPlace\Services\CollectionFailureWorkflow;
use App\Domain\MarketPlace\Services\DriverCustodyLedger;
use App\Domain\MarketPlace\Services\FinancialMovementCorrectionWorkflow;
use App\Domain\MarketPlace\Services\OrderPaymentLedger;
use App\Domain\MarketPlace\Services\RefundExecutionWorkflow;
use App\Domain\MarketPlace\Services\SettlementWorkflow;
use App\Domain\MarketPlace\Actions\Order\OrderAction;
use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchSingleOrderAction;
use Application\Admin\Livewire\RefundPayment\Modal\Create as AdminRefundCreate;
use Application\Admin\Livewire\RefundPayment\Table\All as AdminRefundTable;
use Application\Admin\Livewire\CollectionFailureReports\Index as AdminCollectionFailureIndex;
use Application\Admin\Livewire\SettlementReview\Index as AdminSettlementReviewIndex;
use Application\Admin\Livewire\SettlementReview\Show as AdminSettlementReviewShow;
use Application\Marketplace\Livewire\Order\Table\All as MerchantOrdersTable;
use Application\Marketplace\Livewire\Finance\Reports\Order\Table\All as MerchantOrderReportTable;
use Application\Marketplace\Controllers\OrderController;
use Application\Marketplace\Controllers\OrderPaymentController;
use Application\Marketplace\Livewire\Finance\Reports\Order\Components\CollectionSettlement as MerchantCollectionSettlementReport;
use Application\Driver\Livewire\Home\Order\Modal\Show as DriverOrderShow;
use Application\Driver\Livewire\Custody\Statement as DriverCustodyStatement;
use Application\Driver\Livewire\Settlement\Create as DriverSettlementCreate;
use Application\Driver\Livewire\Settlement\MySettlements as DriverMySettlements;
use Application\Driver\Controllers\SettlementProofController as DriverSettlementProofController;
use Application\Driver\Controllers\DriverController;
use Domain\Admin\Models\Admin;
use Domain\AppBase\Models\Payment;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;
use Domain\MarketPlace\Models\MerchantEmployee;
use App\Domain\AppBase\Models\WalletTransaction;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CollectionSettlementAcceptanceTest extends TestCase
{
    use DatabaseTransactions;

    private Driver $driver;
    private Driver $otherDriver;
    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->driver = $this->createDriver();
        $this->otherDriver = $this->createDriver();
        $this->admin = Admin::query()->create([
            'name' => 'Acceptance Admin',
            'email' => 'acceptance-admin-' . Str::uuid() . '@example.test',
            'password' => Hash::make(Str::random(24)),
            'type' => 'admin',
            'status' => 1,
        ]);
    }

    public function test_driver_must_accept_assigned_orders_before_starting_delivery(): void
    {
        Auth::guard('driver')->setUser($this->driver);
        $controller = app(DriverController::class);

        foreach ([MerchantOrderStatuses::PENDING, MerchantOrderStatuses::UPCOMING, MerchantOrderStatuses::READY] as $status) {
            $order = $this->createOrder($this->driver, ['status' => $status->value]);
            $component = Livewire::test(DriverOrderShow::class)
                ->call('showOrderDetails', $order->id)
                ->assertSet('order.is_accepted_by_driver', false)
                ->assertSeeHtml('id="acceptOrderForm"');

            $controller->updateOrderStatus(
                Request::create('/driver/orders/' . $order->id, 'POST', ['status' => 'on-way']),
                $order->id
            );
            $this->assertSame($status, $order->fresh()->status);

            $controller->acceptOrder($order->id);
            $this->assertTrue($order->fresh()->isAcceptedByDriver($this->driver->id));
            $component->call('showOrderDetails', $order->id)
                ->assertSet('order.is_accepted_by_driver', true)
                ->assertDontSeeHtml('id="acceptOrderForm"');

            $controller->updateOrderStatus(
                Request::create('/driver/orders/' . $order->id, 'POST', ['status' => 'on-way']),
                $order->id
            );
            $this->assertSame(MerchantOrderStatuses::ON_WAY, $order->fresh()->status);
        }
    }

    public function test_customer_checkout_confirmation_reads_persisted_payment_state_and_owns_the_order(): void
    {
        $user = User::factory()->create();
        $codOrder = $this->createOrder($this->driver, [
            'user_id' => $user->id,
            'channel' => 'web',
            'payment_method' => 'COD',
            'status' => MerchantOrderStatuses::PENDING->value,
        ]);

        $this->actingAs($user)
            ->followingRedirects()
            ->get(route('checkout.success', ['orderId' => $codOrder->id]))
            ->assertOk()
            ->assertSee($codOrder->code)
            ->assertSee(__('ecommerce::ecommerce.checkout_confirmation_state_cod_pending'))
            ->assertSee(__('ecommerce::ecommerce.checkout_confirmation_message_cod_pending', [
                'order' => $codOrder->code,
            ]))
            ->assertSee(__('ecommerce::ecommerce.amount_due'));

        $otherUser = User::factory()->create();
        $this->actingAs($otherUser)
            ->followingRedirects()
            ->get(route('checkout.success', ['orderId' => $codOrder->id]))
            ->assertNotFound();

        $paidOrder = $this->createOrder($this->driver, [
            'user_id' => $user->id,
            'channel' => 'web',
            'payment_method' => 'mada',
            'status' => MerchantOrderStatuses::PENDING->value,
        ]);
        $paidOrder->payment()->create([
            'payment_id' => 'WEB-PAID-' . $paidOrder->id,
            'status' => 'paid',
            'amount' => 10000,
            'captured' => 10000,
            'currency' => 'SAR',
            'capturedAt' => now()->toIso8601String(),
            'source_type' => 'credit_card',
            'source_company' => 'Moyasar',
            'channel' => 'web',
        ]);

        $this->actingAs($user)
            ->followingRedirects()
            ->get(route('checkout.success', ['orderId' => $paidOrder->id]))
            ->assertOk()
            ->assertSee($paidOrder->code)
            ->assertSee(__('ecommerce::ecommerce.checkout_confirmation_state_paid'))
            ->assertSee('Moyasar');

        $failedOrder = $this->createOrder($this->driver, [
            'user_id' => $user->id,
            'channel' => 'web',
            'payment_method' => 'mada',
            'status' => MerchantOrderStatuses::DRAFT->value,
        ]);
        $failedOrder->payment()->create([
            'payment_id' => 'WEB-FAILED-' . $failedOrder->id,
            'status' => 'failed',
            'amount' => 10000,
            'currency' => 'SAR',
            'source_type' => 'credit_card',
            'source_company' => 'Moyasar',
            'channel' => 'web',
        ]);

        $paymentCallback = app(OrderPaymentController::class)->test_payment_status(
            'failed',
            $failedOrder->id,
            app(OrderAction::class)
        );
        $this->assertSame(
            route('checkout.success', ['orderId' => $failedOrder->id]),
            $paymentCallback->getTargetUrl()
        );
        $this->actingAs($user)
            ->followingRedirects()
            ->get(route('checkout.success', ['orderId' => $failedOrder->id]))
            ->assertOk()
            ->assertSee(__('ecommerce::ecommerce.checkout_confirmation_state_failed'));
    }

    private function createMerchantEmployee(Merchant $merchant): MerchantEmployee
    {
        return MerchantEmployee::query()->create([
            'name' => 'Acceptance Merchant Employee',
            'email' => 'acceptance-merchant-employee-' . Str::uuid() . '@example.test',
            'password' => Hash::make(Str::random(24)),
            'merchant_id' => $merchant->id,
            'country_id' => $merchant->country_id,
            'status' => 'active',
        ]);
    }

    private function createMerchantBranch(Merchant $merchant, ?MerchantEmployee $manager = null): MerchantBranch
    {
        $city = City::query()->create([
            'title' => ['en' => 'Acceptance City'],
            'country_id' => $merchant->country_id,
            'status' => 'active',
        ]);
        $district = Distract::query()->create([
            'title' => ['en' => 'Acceptance District'],
            'city_id' => $city->id,
            'country_id' => $merchant->country_id,
            'status' => 'active',
        ]);
        $branch = MerchantBranch::query()->create([
            'merchant_id' => $merchant->id,
            'manager_id' => $manager?->id,
            'title' => ['en' => 'Acceptance Branch'],
            'city_id' => $city->id,
            'distract_id' => $district->id,
        ]);
        if ($manager) {
            $branch->employees()->attach($manager->id);
        }

        return $branch;
    }

    public function test_cod_report_does_not_collect_until_driver_confirms_and_confirmation_is_idempotent(): void
    {
        $order = $this->createOrder($this->driver);
        $workflow = app(CollectionFailureWorkflow::class);
        $report = $workflow->report($order->id, $this->driver->id, 'unable_to_contact', null, $this->driver);

        $this->assertSame('open', $report->status);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));

        $workflow->decide($report->id, 'retry_requested', 'أعد المحاولة عند توفر العميل', $this->admin);
        $workflow->confirmCollection($report->id, $this->driver->id, $this->driver);

        $this->assertSame('collection_succeeded', $report->fresh()->status);
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertSame('paid', app(OrderPaymentLedger::class)->forOrder($order)['payment_status']);
        $this->assertValidationFailure(
            fn () => $workflow->confirmCollection($report->id, $this->driver->id, $this->driver)
        );
        $this->assertSame(1, $this->paymentCount($order));
    }

    public function test_retry_decision_requires_instructions_and_does_not_collect_before_driver_confirmation(): void
    {
        $order = $this->createOrder($this->driver);
        $workflow = app(CollectionFailureWorkflow::class);
        $report = $workflow->report($order->id, $this->driver->id, 'unable_to_contact', null, $this->driver);
        $this->assertValidationFailure(
            fn () => $workflow->decide(
                $report->id,
                'retry_requested',
                '   ',
                $this->admin
            )
        );
        $this->assertSame('open', $report->fresh()->status);
        $this->assertSame(0, $this->paymentCount($order));

        $decidedReport = $workflow->decide(
            $report->id,
            'retry_requested',
            'أعد المحاولة عند توفر العميل',
            $this->admin
        );
        $this->assertSame('retry_requested', $decidedReport->status);
        $this->assertSame(0, $this->paymentCount($order));

        $workflow->confirmCollection($report->id, $this->driver->id, $this->driver);
        $this->assertSame('collection_succeeded', $report->fresh()->status);
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertSame('paid', app(OrderPaymentLedger::class)->forOrder($order)['payment_status']);

        $this->assertValidationFailure(
            fn () => $workflow->confirmCollection($report->id, $this->driver->id, $this->driver)
        );
        $this->assertSame(1, $this->paymentCount($order));
    }

    public function test_driver_reports_on_way_cod_failure_without_collection_and_confirms_only_after_admin_retry(): void
    {
        $order = $this->createOrder($this->driver, ['status' => MerchantOrderStatuses::ON_WAY->value]);
        Auth::guard('driver')->setUser($this->driver);
        $component = Livewire::test(DriverOrderShow::class)
            ->call('showCollectionFailure', $order->id)
            ->assertSet('showCollectionFailureForm', true)
            ->assertSee('#' . $order->code)
            ->assertSee('المستحق')
            ->assertSee('لا يتاح تحصيل جزء من المبلغ');

        $component->set('collectionFailureReason', 'other')
            ->call('reportCollectionFailure')
            ->assertHasErrors('collectionFailureNote');

        $component->set('collectionFailureReason', 'unable_to_contact')
            ->call('reportCollectionFailure')
            ->assertHasNoErrors()
            ->assertSet('showCollectionFailureForm', false)
            ->assertSee('بلاغ تعذر التحصيل');

        $report = $order->collectionFailureReports()->latest('id')->firstOrFail();
        $this->assertSame('unable_to_contact', $report->reason);
        $this->assertSame('open', $report->status);
        $this->assertNotNull($report->created_at);
        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame('pending', app(OrderPaymentLedger::class)->forOrder($order)['payment_status']);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertSame(
            1,
            DriverCollectionFailureReport::query()->where('order_id', $order->id)->count()
        );
        Auth::guard('admin')->setUser($this->admin);
        Livewire::test(AdminCollectionFailureIndex::class)
            ->assertSee('CFR-' . $report->id)
            ->assertSee($order->code);
        $component->set('deliveryAcknowledged', true)
            ->set('collectionAcknowledged', true)
            ->call('confirmDeliveryAndCollection');
        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertValidationFailure(
            fn () => app(CollectionFailureWorkflow::class)->report(
                $order->id,
                $this->driver->id,
                'payment_refused',
                null,
                $this->driver
            )
        );

        $workflow = app(CollectionFailureWorkflow::class);
        $workflow->decide($report->id, 'retry_requested', 'أعد المحاولة وتحصيل كامل المبلغ', $this->admin);
        $this->assertSame('retry_requested', $report->fresh()->status);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame('pending', app(OrderPaymentLedger::class)->forOrder($order)['payment_status']);

        $component->call('refreshPaymentStatus')
            ->assertSee('أعد المحاولة وتحصيل كامل المبلغ')
            ->call('confirmRetryCollection')
            ->assertHasNoErrors();

        $this->assertSame('collection_succeeded', $report->fresh()->status);
        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame('paid', app(OrderPaymentLedger::class)->forOrder($order)['payment_status']);
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_driver_ui_creates_one_collection_and_resubmits_returned_settlement_in_place(): void
    {
        Auth::guard('driver')->setUser($this->driver);
        $order = $this->createOrder($this->driver, [
            'collection_config' => [
                'recipient' => 'platform',
                'enabled_methods' => ['cash'],
                'methods' => [
                    'cash' => [
                        'recipient_name' => 'صندوق المنصة',
                        'instructions' => 'تسليم نقدي',
                    ],
                ],
            ],
        ]);
        $component = Livewire::test(DriverOrderShow::class)
            ->set('order', [
                'id' => $order->id,
                'payment_method' => 'cod',
                'settlement_history' => [],
            ])
            ->set('settlementMethod', 'cash')
            ->call('submitSettlement')
            ->assertHasNoErrors();

        $this->assertSame(0, OrderSettlement::query()->where('order_id', $order->id)->count());
        $this->assertSame(0, $this->paymentCount($order));

        $component->call('confirmCashCollection')->assertHasNoErrors();
        $this->assertSame(1, $this->paymentCount($order));
        $ledger = app(OrderPaymentLedger::class)->forOrder($order);
        $this->assertSame('paid', $ledger['payment_status']);
        $this->assertSame(100.0, $ledger['collected']);
        $this->assertSame($this->driver->name, $ledger['movements'][0]['collector']);
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));

        $component->call('submitSettlement')->assertHasNoErrors();
        $settlement = $order->settlement()->firstOrFail();
        $payment = $order->payment()->firstOrFail();
        $this->assertSame('pending_review', $settlement->status);
        $this->assertSame(1, OrderSettlement::query()->where('order_id', $order->id)->count());
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $component->assertSet('order.settlement_id', $settlement->id);

        app(SettlementWorkflow::class)->returnForCompletion($settlement->id, $this->admin, 'أضف المرجع');
        $component->call('submitSettlement')->assertHasNoErrors();

        $this->assertSame($settlement->id, $order->settlement()->value('id'));
        $this->assertSame('pending_review', $settlement->fresh()->status);
        $this->assertSame($payment->id, $order->payment()->value('id'));
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_driver_submits_and_admin_reviews_a_multi_order_settlement_as_one_batch(): void
    {
        $configuration = [
            'recipient' => 'platform',
            'enabled_methods' => ['cash'],
            'methods' => [
                'cash' => [
                    'recipient_name' => 'صندوق المنصة',
                    'instructions' => 'تسليم نقدي',
                ],
            ],
        ];
        $firstOrder = $this->createOrder($this->driver, ['collection_config' => $configuration]);
        $secondOrder = $this->createOrder($this->driver, ['collection_config' => $configuration]);
        $firstPayment = $this->createCollection($firstOrder);
        $secondPayment = $this->createCollection($secondOrder);

        Auth::guard('driver')->setUser($this->driver);
        $driverComponent = Livewire::test(DriverSettlementCreate::class)
            ->assertSee($firstOrder->code)
            ->assertSee($secondOrder->code)
            ->set('selectedCollectionIds', [$firstPayment->id, $secondPayment->id])
            ->assertSee('200.00 SAR')
            ->set('settlementMethod', 'cash')
            ->set('supplyDate', now()->toDateString())
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('showBatchDetails', true);

        $settlements = OrderSettlement::query()
            ->whereIn('order_id', [$firstOrder->id, $secondOrder->id])
            ->orderBy('id')
            ->get();
        $this->assertCount(2, $settlements);
        $batchId = $settlements->first()->batch_number;
        $this->assertSame([$batchId], $settlements->pluck('batch_id')->unique()->values()->all());
        $this->assertSame(['pending_review'], $settlements->pluck('status')->unique()->values()->all());
        $this->assertSame(
            $settlements->firstWhere('order_id', $firstOrder->id)->id,
            $firstPayment->fresh()->settlement_id
        );
        $this->assertSame(
            $settlements->firstWhere('order_id', $secondOrder->id)->id,
            $secondPayment->fresh()->settlement_id
        );
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($firstOrder->id, $this->driver->id));
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($secondOrder->id, $this->driver->id));
        $driverComponent->call('submit')->assertHasErrors('selectedCollectionIds');
        $this->assertSame(2, OrderSettlement::batchQuery($batchId)->count());

        Livewire::test(DriverMySettlements::class)
            ->assertSee('STL-' . $batchId)
            ->assertSee('200.00 SAR')
            ->assertSee($firstOrder->code)
            ->assertSee($secondOrder->code);

        Auth::guard('admin')->setUser($this->admin);
        Livewire::test(AdminSettlementReviewShow::class, ['settlement' => $settlements->first()])
            ->assertSee('STL-' . $batchId)
            ->assertSee('200.00 SAR')
            ->assertSee($firstOrder->code)
            ->assertSee($secondOrder->code);
        Livewire::test(AdminSettlementReviewIndex::class)
            ->assertSee('STL-' . $batchId)
            ->assertSee('200.00 SAR')
            ->assertSee('2');

        $workflow = app(SettlementWorkflow::class);
        $workflow->review($batchId, $this->admin);
        $workflow->returnForCompletion($batchId, $this->admin, 'أضف بيانات التوريد');
        $this->assertSame(['returned'], OrderSettlement::batchQuery($batchId)
            ->pluck('status')->unique()->values()->all());
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($firstOrder->id, $this->driver->id));
        Auth::guard('driver')->setUser($this->driver);
        $driverComponent->call('resubmitDriverSettlementBatch', $batchId)
            ->assertSet('returnedBatchId', $batchId)
            ->call('submit')
            ->assertHasNoErrors();
        $this->assertSame(2, OrderSettlement::batchQuery($batchId)->count());
        $this->assertSame(['pending_review'], OrderSettlement::batchQuery($batchId)
            ->pluck('status')->unique()->values()->all());

        Auth::guard('admin')->setUser($this->admin);
        $workflow->review($batchId, $this->admin);
        $receipt = $this->receipt();
        $receipt['received_amount'] = 200;
        $workflow->approve($settlements->last()->id, $this->admin, $receipt);
        $this->assertSame(['approved'], OrderSettlement::batchQuery($batchId)
            ->pluck('status')->unique()->values()->all());
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($firstOrder->id, $this->driver->id));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($secondOrder->id, $this->driver->id));
        Auth::guard('driver')->setUser($this->driver);
        Livewire::test(DriverSettlementCreate::class)
            ->call('viewDriverSettlementBatch', $batchId)
            ->assertSee('200.00 SAR')
            ->assertSee($this->admin->name)
            ->assertSee(now()->format('Y-m-d'));

        $rejectedOrder = $this->createOrder($this->driver, ['collection_config' => $configuration]);
        $rejectedPayment = $this->createCollection($rejectedOrder);
        Auth::guard('driver')->setUser($this->driver);
        $driverComponent->call('openDriverSettlementForm')
            ->set('selectedCollectionIds', [$rejectedPayment->id])
            ->set('settlementMethod', 'cash')
            ->call('submit')
            ->assertHasNoErrors();
        $rejectedBatchId = (int) OrderSettlement::query()
            ->where('order_id', $rejectedOrder->id)
            ->where('status', 'pending_review')
            ->value('batch_id');
        Auth::guard('admin')->setUser($this->admin);
        $workflow->reject($rejectedBatchId, $this->admin, 'مرفوضة للمراجعة');
        $this->assertNull($rejectedPayment->fresh()->settlement_id);

        Auth::guard('driver')->setUser($this->driver);
        $driverComponent->call('openDriverSettlementForm')
            ->set('selectedCollectionIds', [$rejectedPayment->id])
            ->set('settlementMethod', 'cash')
            ->call('submit')
            ->assertHasNoErrors();
        $newBatchId = (int) OrderSettlement::query()
            ->where('order_id', $rejectedOrder->id)
            ->where('status', 'pending_review')
            ->value('batch_id');
        $this->assertNotSame($rejectedBatchId, $newBatchId);
        $this->assertSame('rejected', OrderSettlement::batchQuery($rejectedBatchId)->firstOrFail()->status);
    }

    public function test_driver_cannot_combine_different_recipients_or_submit_without_configured_settings(): void
    {
        $cashMethod = [
            'cash' => [
                'recipient_name' => 'صندوق الاستلام',
                'instructions' => 'تسليم نقدي',
            ],
        ];
        $platformOrder = $this->createOrder($this->driver, [
            'collection_config' => [
                'recipient' => 'platform',
                'enabled_methods' => ['cash'],
                'methods' => $cashMethod,
            ],
        ]);
        $merchantOrder = $this->createOrder($this->driver, [
            'collection_config' => [
                'recipient' => 'merchant',
                'enabled_methods' => ['cash'],
                'methods' => $cashMethod,
            ],
        ]);
        $platformPayment = $this->createCollection($platformOrder);
        $merchantPayment = $this->createCollection($merchantOrder);
        Auth::guard('driver')->setUser($this->driver);

        Livewire::test(DriverSettlementCreate::class)
            ->set('selectedCollectionIds', [$platformPayment->id, $merchantPayment->id])
            ->set('settlementMethod', 'cash')
            ->call('submit')
            ->assertHasErrors('selectedCollectionIds');
        $this->assertSame(0, OrderSettlement::query()->count());
        $this->assertNull($platformPayment->fresh()->settlement_id);
        $this->assertNull($merchantPayment->fresh()->settlement_id);

        $unconfiguredOrder = $this->createOrder($this->driver, [
            'collection_config' => ['recipient' => null, 'enabled_methods' => [], 'methods' => []],
        ]);
        $unconfiguredPayment = $this->createCollection($unconfiguredOrder);
        Livewire::test(DriverSettlementCreate::class)
            ->assertSee('إعدادات جهة الاستلام غير مضبوطة لبعض التحصيلات')
            ->set('selectedCollectionIds', [$unconfiguredPayment->id])
            ->call('submit')
            ->assertHasErrors('settings');
        $this->assertSame(0, OrderSettlement::query()->count());
    }

    public function test_driver_settlement_list_filters_details_and_preserves_prior_proofs(): void
    {
        Storage::fake('settlement_proofs');
        $order = $this->createOrder($this->driver, [
            'collection_config' => [
                'recipient' => 'platform',
                'enabled_methods' => ['transfer'],
                'methods' => [
                    'transfer' => [
                        'recipient_name' => 'حساب المنصة',
                        'instructions' => 'أودع المبلغ في الحساب المعتمد.',
                        'account_data' => 'IBAN-TEST-002',
                    ],
                ],
            ],
        ]);
        $payment = $this->createCollection($order);
        Auth::guard('driver')->setUser($this->driver);

        $component = Livewire::test(DriverSettlementCreate::class)
            ->set('selectedCollectionIds', [$payment->id])
            ->set('settlementMethod', 'transfer')
            ->set('settlementReference', 'ORIGINAL-REF')
            ->set('settlementProof', UploadedFile::fake()->image('original-proof.jpg'))
            ->call('submit')
            ->assertHasNoErrors();

        $settlement = $order->settlement()->firstOrFail();
        $batchId = $settlement->batch_number;
        $originalProofPath = $settlement->proof_path;
        $originalSubmission = $settlement->histories()->where('action', 'submitted')->firstOrFail();
        $this->assertSame([(int) $payment->id], $originalSubmission->snapshot['payment_record_ids']);
        Storage::disk('settlement_proofs')->assertExists($originalProofPath);

        app(SettlementWorkflow::class)->returnForCompletion(
            $batchId,
            $this->admin,
            'صحح المرجع وأعد إرفاق الإثبات.'
        );

        $list = Livewire::test(DriverMySettlements::class)
            ->set('statusFilter', 'returned')
            ->set('dateFrom', now()->subDay()->toDateString())
            ->set('dateTo', now()->toDateString())
            ->call('applyFilters')
            ->assertSee('STL-' . $batchId)
            ->assertSee($order->code);
        $list->set('dateFrom', now()->addDay()->toDateString())
            ->set('dateTo', now()->addDay()->toDateString())
            ->call('applyFilters')
            ->assertDontSee('STL-' . $batchId);
        $list
            ->set('dateFrom', now()->subDay()->toDateString())
            ->set('dateTo', now()->toDateString())
            ->set('statusFilter', 'approved')
            ->call('applyFilters')
            ->assertDontSee('STL-' . $batchId);

        $details = Livewire::test(DriverSettlementCreate::class)
            ->call('viewDriverSettlementBatch', $batchId)
            ->assertSee('ORIGINAL-REF')
            ->assertSee('صحح المرجع وأعد إرفاق الإثبات.')
            ->assertSee(route('driver:settlements.history.proof', [$settlement, $originalSubmission]), false);

        $details->call('resubmitDriverSettlementBatch', $batchId)
            ->set('settlementReference', 'UPDATED-REF')
            ->set('settlementProof', UploadedFile::fake()->image('updated-proof.jpg'))
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSee('UPDATED-REF');

        $updatedSettlement = $settlement->fresh();
        $this->assertSame($batchId, $updatedSettlement->batch_number);
        $this->assertSame('UPDATED-REF', $updatedSettlement->reference);
        $this->assertNotSame($originalProofPath, $updatedSettlement->proof_path);
        Storage::disk('settlement_proofs')->assertExists($originalProofPath);
        Storage::disk('settlement_proofs')->assertExists($updatedSettlement->proof_path);

        app(SettlementWorkflow::class)->reject($batchId, $this->admin, 'تعذر مطابقة التوريد.');
        $this->assertNull($payment->fresh()->settlement_id);

        $details->call('viewDriverSettlementBatch', $batchId)
            ->assertSee('تعذر مطابقة التوريد.')
            ->assertSee($payment->payment_id)
            ->assertSee('ORIGINAL-REF')
            ->assertSee('UPDATED-REF');
        Storage::disk('settlement_proofs')->assertExists($originalProofPath);

        $proofController = app(DriverSettlementProofController::class);
        Auth::guard('driver')->setUser($this->driver);
        $this->assertSame(200, $proofController->show($updatedSettlement)->getStatusCode());
        $this->assertSame(
            200,
            $proofController->showHistory($updatedSettlement, $originalSubmission)->getStatusCode()
        );

        Auth::guard('driver')->setUser($this->otherDriver);
        try {
            $proofController->show($updatedSettlement);
            $this->fail('A driver must not download another driver settlement proof.');
        } catch (HttpException $exception) {
            $this->assertSame(404, $exception->getStatusCode());
        }
    }

    public function test_transfer_settlement_requires_reference_and_proof_before_saving(): void
    {
        Storage::fake('settlement_proofs');
        $order = $this->createOrder($this->driver, [
            'collection_config' => [
                'recipient' => 'platform',
                'enabled_methods' => ['transfer'],
                'methods' => [
                    'transfer' => [
                        'recipient_name' => 'حساب المنصة',
                        'instructions' => 'حوّل المبلغ إلى الحساب المعتمد.',
                        'account_data' => 'IBAN-TEST-001',
                    ],
                ],
            ],
        ]);
        $payment = $this->createCollection($order);
        Auth::guard('driver')->setUser($this->driver);

        $component = Livewire::test(DriverSettlementCreate::class)
            ->set('selectedCollectionIds', [$payment->id])
            ->set('settlementMethod', 'transfer')
            ->set('supplyDate', now()->toDateString())
            ->call('submit')
            ->assertHasErrors(['settlementReference', 'settlementProof']);
        $this->assertSame(0, OrderSettlement::query()->where('order_id', $order->id)->count());

        $component->set('settlementReference', 'BANK-REF-100')
            ->set('settlementProof', UploadedFile::fake()->image('receipt.jpg'))
            ->call('submit')
            ->assertHasNoErrors();

        $settlement = $order->settlement()->firstOrFail();
        $this->assertSame('BANK-REF-100', $settlement->reference);
        $this->assertSame('IBAN-TEST-001', $settlement->account_data);
        $this->assertSame(now()->toDateString(), $settlement->supply_date->toDateString());
        Storage::disk('settlement_proofs')->assertExists($settlement->proof_path);
    }

    public function test_driver_custody_statement_uses_shared_ledger_and_scopes_all_rows_to_current_driver(): void
    {
        $order = $this->createOrder($this->driver);
        $collection = $this->createCollection($order);
        $otherOrder = $this->createOrder($this->otherDriver);
        $otherCollection = $this->createCollection($otherOrder);

        Auth::guard('driver')->setUser($this->driver);
        $component = Livewire::test(DriverCustodyStatement::class)
            ->assertSee($order->code)
            ->assertDontSee($otherOrder->code)
            ->assertSee('العهدة المطلوب توريدها')
            ->assertSee('إنشاء طلب تسوية')
            ->assertSee("wire:click=\"\$emit('openDriverSettlementForm', {$collection->id})\"", false)
            ->call('viewMovement', 'collection', $collection->id)
            ->assertSet('movementDetails.order_code', $order->code)
            ->assertSee('تفاصيل الحركة');

        $ledger = app(DriverCustodyLedger::class);
        $summary = $ledger->statement($this->driver->id);
        $this->assertSame(100.0, $summary['custody_balance']);
        $this->assertSame(0.0, $summary['pending_settlement_amount']);
        $this->assertSame(0.0, $summary['approved_settlement_amount']);
        $this->assertNull($ledger->movement($this->driver->id, 'collection', $otherCollection->id));
        $this->assertSame(
            1,
            $ledger->statement($this->driver->id, ['order' => $order->code])['movements']->total()
        );

        try {
            (new DriverCustodyStatement())->viewMovement('collection', $otherCollection->id);
            $this->fail('A driver must not open another driver movement.');
        } catch (HttpException $exception) {
            $this->assertSame(404, $exception->getStatusCode());
        }

        $settlement = $this->createSettlement($order, $this->driver);
        $pendingSummary = $ledger->statement($this->driver->id, [
            'settlement_status' => 'pending_review',
        ]);
        $this->assertSame(100.0, $pendingSummary['custody_balance']);
        $this->assertSame(100.0, $pendingSummary['pending_settlement_amount']);
        $this->assertSame(1, $pendingSummary['movements']->total());

        app(SettlementWorkflow::class)->approve($settlement->id, $this->admin, $this->receipt());
        $approvedSummary = $ledger->statement($this->driver->id, [
            'from' => now()->toDateString(),
            'to' => now()->toDateString(),
            'settlement_status' => 'approved',
        ]);
        $this->assertSame(0.0, $approvedSummary['custody_balance']);
        $this->assertSame(0.0, $approvedSummary['pending_settlement_amount']);
        $this->assertSame(100.0, $approvedSummary['approved_settlement_amount']);
        $this->assertSame(2, $approvedSummary['movements']->total());

        $component->set('from', now()->toDateString())
            ->set('to', now()->toDateString())
            ->set('settlementStatus', 'approved')
            ->call('applyFilters')
            ->assertSee($order->code)
            ->assertDontSee($otherOrder->code);
    }

    public function test_driver_cash_collection_confirmation_is_idempotent_and_does_not_submit_settlement(): void
    {
        Auth::guard('driver')->setUser($this->driver);
        $order = $this->createOrder($this->driver, [
            'status' => MerchantOrderStatuses::PREVIOUS->value,
        ]);
        $component = Livewire::test(DriverOrderShow::class)
            ->set('order', ['id' => $order->id])
            ->call('confirmCashCollection')
            ->assertHasNoErrors();

        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(0, OrderSettlement::query()->where('order_id', $order->id)->count());
        $this->assertSame('paid', app(OrderPaymentLedger::class)->forOrder($order)['payment_status']);
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));

        $component->call('confirmCashCollection')->assertHasNoErrors();
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_opening_driver_order_details_without_confirming_delivery_does_not_change_financial_state(): void
    {
        Auth::guard('driver')->setUser($this->driver);
        $order = $this->createOrder($this->driver, ['status' => MerchantOrderStatuses::ON_WAY->value]);
        $component = new DriverOrderShow();

        $component->showOrderDetails($order->id);

        $this->assertSame($order->id, $component->order['id']);
        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_driver_order_details_include_street_district_city_and_country_in_address(): void
    {
        app()->setLocale('en');
        Auth::guard('driver')->setUser($this->driver);
        $user = User::factory()->create();
        $currency = Currency::query()->create([
            'title' => 'Acceptance Currency',
            'iso' => 'ACT',
            'status' => 'active',
        ]);
        $country = Country::query()->create([
            'title' => 'Saudi Arabia',
            'iso' => 'SAU',
            'vat' => 0,
            'currency_id' => $currency->id,
            'status' => 'active',
        ]);
        $city = City::query()->create([
            'title' => 'Riyadh',
            'country_id' => $country->id,
            'status' => 'active',
        ]);
        $district = Distract::query()->create([
            'title' => 'Al Nada',
            'country_id' => $country->id,
            'city_id' => $city->id,
            'status' => 'active',
        ]);
        $address = UserAddress::query()->create([
            'user_id' => $user->id,
            'country_id' => $country->id,
            'city_id' => $city->id,
            'distract_id' => $district->id,
            'title' => 'Home',
            'address' => 'Riyadh local test street',
            'latitude' => '24.7',
            'longitude' => '46.6',
        ]);
        $order = $this->createOrder($this->driver, [
            'address_id' => $address->id,
            'user_id' => $user->id,
        ]);
        $component = new DriverOrderShow();

        $component->showOrderDetails($order->id);

        $this->assertSame(
            'Riyadh local test street، Al Nada، Riyadh، Saudi Arabia',
            $component->order['address']
        );
    }

    public function test_driver_cannot_complete_delivery_before_starting_it(): void
    {
        $order = $this->createOrder($this->driver, ['status' => MerchantOrderStatuses::READY->value]);
        $response = $this->actingAs($this->driver, 'driver')
            ->post(route('driver:orders.updateStatus', $order->id), ['status' => 'completed']);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(MerchantOrderStatuses::READY->value, $order->fresh()->status->value);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_cod_delivery_requires_both_acknowledgements_and_records_delivery_and_collection_together(): void
    {
        $order = $this->createOrder($this->driver, ['status' => MerchantOrderStatuses::ON_WAY->value]);
        $response = $this->actingAs($this->driver, 'driver')
            ->post(route('driver:orders.updateStatus', $order->id), ['status' => 'completed']);

        $response->assertRedirect();
        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertSame('pending', app(OrderPaymentLedger::class)->forOrder($order)['payment_status']);

        Auth::guard('driver')->setUser($this->driver);
        $component = Livewire::test(DriverOrderShow::class)
            ->call('showOrderDetails', $order->id)
            ->assertSet('deliveryAcknowledged', false)
            ->assertSet('collectionAcknowledged', false)
            ->assertSet('order.collection_remaining', 100.0)
            ->assertSee('تأكيد التسليم والتحصيل')
            ->call('confirmDeliveryAndCollection')
            ->assertHasErrors(['deliveryAcknowledged', 'collectionAcknowledged']);

        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame(0, $this->paymentCount($order));

        $component->set('deliveryAcknowledged', true)
            ->call('confirmDeliveryAndCollection')
            ->assertHasErrors('collectionAcknowledged');

        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame(0, $this->paymentCount($order));

        $component->set('collectionAcknowledged', true)
            ->call('confirmDeliveryAndCollection')
            ->assertHasNoErrors();

        $this->assertSame(MerchantOrderStatuses::PREVIOUS->value, $order->fresh()->status->value);
        $this->assertSame('paid', app(OrderPaymentLedger::class)->forOrder($order->fresh())['payment_status']);
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));

        $component->call('confirmDeliveryAndCollection')
            ->assertHasNoErrors();
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_prepaid_delivery_uses_one_acknowledgement_and_preserves_payment_without_driver_custody(): void
    {
        $order = $this->createOrder($this->driver, [
            'payment_method' => 'card',
            'status' => MerchantOrderStatuses::ON_WAY->value,
        ]);
        $payment = $order->payment()->create([
            'payment_id' => 'ONLINE-' . $order->code,
            'status' => 'paid',
            'amount' => 10000,
            'captured' => 10000,
            'currency' => 'SAR',
            'capturedAt' => now()->toIso8601String(),
            'source_type' => 'card',
            'source_company' => 'mada',
            'source_message' => 'Online payment',
            'channel' => $order->channel,
        ]);

        Auth::guard('driver')->setUser($this->driver);
        $component = Livewire::test(DriverOrderShow::class)
            ->call('showOrderDetails', $order->id)
            ->assertSet('order.payment_status', 'paid')
            ->assertSet('order.prepaid_amount', 100.0)
            ->assertSee('المطلوب تحصيله من العميل: 0.00 ريال')
            ->assertSee('100.00')
            ->assertSee('mada')
            ->assertSee('تأكيد التسليم')
            ->assertSee('سلّمت الطلب')
            ->assertDontSee('حصّلت المبلغ كاملًا')
            ->call('confirmPrepaidDelivery')
            ->assertHasErrors('deliveryAcknowledged');

        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));

        $component->set('deliveryAcknowledged', true)
            ->call('confirmPrepaidDelivery')
            ->assertHasNoErrors();

        $this->assertSame(MerchantOrderStatuses::PREVIOUS->value, $order->fresh()->status->value);
        $this->assertSame('paid', app(OrderPaymentLedger::class)->forOrder($order->fresh())['payment_status']);
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertDatabaseMissing((new OrderSettlement())->getTable(), ['order_id' => $order->id]);
        $deliveryHistory = $this->driver->logOrderHistory()
            ->where('order_id', $order->id)
            ->latest('id')
            ->first();
        $this->assertNotNull($deliveryHistory);
        $this->assertSame(MerchantOrderHistoryStatuses::COMPLETED->value, $deliveryHistory->action);
        $this->assertNotNull($deliveryHistory->created_at);
        $this->assertSame(
            ['payment_id' => $payment->payment_id, 'amount' => $payment->amount, 'captured' => $payment->captured, 'status' => $payment->status],
            $payment->fresh()->only(['payment_id', 'amount', 'captured', 'status'])
        );

        $component->call('confirmPrepaidDelivery')
            ->assertHasNoErrors();
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_direct_web_and_api_completion_cannot_bypass_prepaid_delivery_acknowledgement(): void
    {
        $order = $this->createOrder($this->driver, [
            'payment_method' => 'card',
            'status' => MerchantOrderStatuses::ON_WAY->value,
        ]);
        $order->payment()->create([
            'payment_id' => 'ONLINE-' . $order->code,
            'status' => 'paid',
            'amount' => 10000,
            'captured' => 10000,
            'currency' => 'SAR',
            'capturedAt' => now()->toIso8601String(),
            'source_type' => 'card',
            'source_message' => 'Online payment',
            'channel' => $order->channel,
        ]);

        $this->actingAs($this->driver, 'driver')
            ->post(route('driver:orders.updateStatus', $order->id), ['status' => 'completed'])
            ->assertRedirect();
        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);

        $this->actingAs($this->driver, 'driver-api')
            ->postJson('/api/v1/driver/order/update', [
                'order_id' => $order->id,
                'status' => MerchantOrderStatuses::PREVIOUS->value,
            ])
            ->assertStatus(422);

        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_failed_atomic_delivery_and_collection_save_leaves_order_payment_and_custody_unchanged(): void
    {
        Auth::guard('driver')->setUser($this->driver);
        $order = $this->createOrder($this->driver, ['status' => MerchantOrderStatuses::ON_WAY->value]);
        $failedWrite = false;
        DB::connection()->beforeExecuting(function (string $query) use (&$failedWrite): void {
            if (!$failedWrite && preg_match('/insert\s+into\s+[`"]?payments/i', $query) === 1) {
                $failedWrite = true;
                throw new \RuntimeException('Simulated payment write failure.');
            }
        });

        Livewire::test(DriverOrderShow::class)
            ->call('showOrderDetails', $order->id)
            ->set('deliveryAcknowledged', true)
            ->set('collectionAcknowledged', true)
            ->call('confirmDeliveryAndCollection');

        $this->assertTrue($failedWrite);
        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_driver_api_cannot_complete_cod_order_without_both_acknowledgements(): void
    {
        $order = $this->createOrder($this->driver, ['status' => MerchantOrderStatuses::ON_WAY->value]);

        $this->actingAs($this->driver, 'driver-api')
            ->postJson('/api/v1/driver/order/update', [
                'order_id' => $order->id,
                'status' => MerchantOrderStatuses::PREVIOUS->value,
            ])
            ->assertStatus(422);

        $this->assertSame(MerchantOrderStatuses::ON_WAY->value, $order->fresh()->status->value);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_failed_report_history_write_rolls_back_collection_and_report_state(): void
    {
        $order = $this->createOrder($this->driver);
        $workflow = app(CollectionFailureWorkflow::class);
        $report = $workflow->report($order->id, $this->driver->id, 'unable_to_contact', null, $this->driver);
        $workflow->decide($report->id, 'retry_requested', 'أعد المحاولة', $this->admin);

        $failedWrite = false;
        DB::connection()->beforeExecuting(function (string $query) use (&$failedWrite): void {
            if (!$failedWrite
                && preg_match('/insert\s+into\s+[`"]?driver_collection_failure_report_histories/i', $query) === 1) {
                $failedWrite = true;
                throw new \RuntimeException('Simulated acceptance-test persistence failure.');
            }
        });

        try {
            $workflow->confirmCollection($report->id, $this->driver->id, $this->driver);
            $this->fail('The simulated persistence failure should be surfaced.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Simulated acceptance-test persistence failure.', $exception->getMessage());
        }

        $this->assertTrue($failedWrite);
        $this->assertSame('retry_requested', $report->fresh()->status);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_prepaid_or_already_collected_order_cannot_create_a_cod_collection(): void
    {
        $workflow = app(CollectionFailureWorkflow::class);
        $prepaid = $this->createOrder($this->driver, ['payment_method' => 'visa']);
        $this->assertValidationFailure(
            fn () => $workflow->report($prepaid->id, $this->driver->id, 'سبب', null, $this->driver)
        );
        $this->assertValidationFailure(
            fn () => $workflow->confirmOrderCollection($prepaid->id, $this->driver->id, $this->driver)
        );
        $this->assertSame(0, $this->paymentCount($prepaid));

        $collected = $this->createOrder($this->driver);
        $this->createCollection($collected);
        $this->assertValidationFailure(
            fn () => $workflow->report($collected->id, $this->driver->id, 'سبب', null, $this->driver)
        );
        $this->assertSame(1, $this->paymentCount($collected));
    }

    public function test_return_instruction_does_not_mark_order_returned_or_create_payment_or_refund(): void
    {
        $order = $this->createOrder($this->driver);
        $workflow = app(CollectionFailureWorkflow::class);
        $report = $workflow->report($order->id, $this->driver->id, 'other', 'ملاحظة', $this->driver);
        $workflow->decide($report->id, 'return_requested', 'وجّه بإرجاع الطلب', $this->admin);

        $this->assertSame(MerchantOrderStatuses::PREVIOUS, $order->fresh()->status);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0, RefundPayment::query()->where('order_id', $order->id)->count());

        $workflow->confirmReturn($report->id, $this->driver->id, $this->driver);
        $this->assertSame('return_completed', $report->fresh()->status);
        $this->assertSame(MerchantOrderStatuses::PREVIOUS, $order->fresh()->status);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0, RefundPayment::query()->where('order_id', $order->id)->count());
    }

    public function test_returned_settlement_reuses_its_record_and_only_approved_settlement_reduces_custody_once(): void
    {
        $order = $this->createOrder($this->driver);
        $payment = $this->createCollection($order);
        $workflow = app(SettlementWorkflow::class);
        $settlement = $this->createSettlement($order, $this->driver);
        $workflow->recordSubmission($settlement, $this->driver, 'submitted', null, ['amount' => 100]);

        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $workflow->returnForCompletion($settlement->id, $this->admin, 'أضف المرجع');
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));

        $settlement->refresh();
        $this->assertSame('returned', $settlement->status);
        $settlement->update(['status' => 'pending_review']);
        $workflow->recordSubmission($settlement, $this->driver, 'resubmitted', 'returned', ['amount' => 100]);
        $this->assertSame($settlement->id, OrderSettlement::query()->where('order_id', $order->id)->value('id'));
        $this->assertSame(1, OrderSettlement::query()->where('order_id', $order->id)->count());

        $workflow->reject($settlement->id, $this->admin, 'المرجع غير صحيح');
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertSame(4, OrderSettlementHistory::query()->where('settlement_id', $settlement->id)->count());

        $newSettlement = $this->createSettlement($order, $this->driver);
        $this->assertSame('pending_review', $newSettlement->fresh()->status);
        $workflow->approve($newSettlement->id, $this->admin, $this->receipt());
        $this->assertSame('approved', $newSettlement->fresh()->status);
        $this->assertSame($newSettlement->id, $payment->fresh()->settlement_id);
        $this->assertSame(1, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertValidationFailure(
            fn () => $workflow->approve($newSettlement->id, $this->admin, $this->receipt())
        );
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_incomplete_receipt_acknowledgement_does_not_approve_settlement_or_change_custody(): void
    {
        $order = $this->createOrder($this->driver);
        $payment = $this->createCollection($order);
        $settlement = $this->createSettlement($order, $this->driver);
        $incompleteReceipts = [
            array_merge($this->receipt(), ['receipt_confirmed' => false]),
            array_merge($this->receipt(), ['received_by' => '   ']),
            array_merge($this->receipt(), ['received_amount' => 0]),
            array_merge($this->receipt(), ['received_at' => null]),
        ];

        foreach ($incompleteReceipts as $receipt) {
            $this->assertValidationFailure(
                fn () => app(SettlementWorkflow::class)->approve($settlement->id, $this->admin, $receipt)
            );
            $this->assertSame('pending_review', $settlement->fresh()->status);
            $this->assertNull($settlement->fresh()->approved_at);
            $this->assertNull($payment->fresh()->settlement_id);
            $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        }
    }

    public function test_driver_can_open_settlement_form_from_settlement_pages(): void
    {
        Auth::guard('driver')->setUser($this->driver);

        Livewire::test(DriverMySettlements::class)
            ->assertSee("wire:click=\"\$emit('openDriverSettlementForm')\"", false)
            ->assertSee('إنشاء طلب تسوية');

        Livewire::test(DriverSettlementCreate::class)
            ->call('openDriverSettlementForm')
            ->assertEmitted('openModal', '#driverSettlementSubmissionModal');
    }

    public function test_merchant_payment_card_uses_shared_payment_and_remittance_statuses(): void
    {
        $order = $this->createOrder($this->driver);
        $payment = $this->createCollection($order);
        $ledger = app(OrderPaymentLedger::class)->forOrder($order);

        $this->assertSame('paid', $ledger['payment_status']);
        $this->assertSame(__('ecommerce::ecommerce.funds_held_by_driver'), $ledger['custody']);
        $this->assertSame(MerchantOrderStatuses::PREVIOUS->value, $ledger['delivery_status']);
        $this->assertSame('not_submitted', $ledger['settlement_status']);
        $this->assertNull($ledger['settlement_number']);
        $this->assertSame(
            \Carbon\Carbon::parse($payment->capturedAt)->format('Y-m-d H:i'),
            $ledger['collection_date']
        );

        $settlement = $this->createSettlement($order, $this->driver);
        $pendingLedger = app(OrderPaymentLedger::class)->forOrder($order->fresh());
        $this->assertSame('paid', $pendingLedger['payment_status']);
        $this->assertSame('pending_review', $pendingLedger['settlement_status']);
        $this->assertSame('STL-' . $settlement->id, $pendingLedger['settlement_number']);

        app(SettlementWorkflow::class)->approve($settlement->id, $this->admin, $this->receipt());
        $approvedLedger = app(OrderPaymentLedger::class)->forOrder($order->fresh());
        $this->assertSame('paid', $approvedLedger['payment_status']);
        $this->assertSame('approved', $approvedLedger['settlement_status']);
        $this->assertSame(__('ecommerce::ecommerce.platform'), $approvedLedger['custody']);
    }

    public function test_changed_collection_amount_blocks_approval_without_partial_success(): void
    {
        $order = $this->createOrder($this->driver);
        $payment = $this->createCollection($order, 99);
        $settlement = $this->createSettlement($order, $this->driver);

        $this->assertValidationFailure(
            fn () => app(SettlementWorkflow::class)->approve($settlement->id, $this->admin, $this->receipt())
        );

        $this->assertSame('pending_review', $settlement->fresh()->status);
        $this->assertNull($payment->fresh()->settlement_id);
        $this->assertNull($settlement->fresh()->approved_at);
        $this->assertSame(99.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_driver_funded_refund_changes_custody_but_other_funded_refund_does_not(): void
    {
        $order = $this->createOrder($this->driver);
        $this->createCollection($order);
        $settlement = $this->createSettlement($order, $this->driver);
        app(SettlementWorkflow::class)->approve($settlement->id, $this->admin, $this->receipt());

        RefundPayment::query()->create([
            'refund_payment_id' => 'REF-DRIVER-' . Str::uuid(),
            'amount' => 10,
            'currency' => 'SAR',
            'note' => 'اختبار رد ممول من السائق',
            'funded_by' => 'driver',
            'driver_id' => $this->driver->id,
            'order_id' => $order->id,
        ]);
        RefundPayment::query()->create([
            'refund_payment_id' => 'REF-PLATFORM-' . Str::uuid(),
            'amount' => 10,
            'currency' => 'SAR',
            'note' => 'اختبار رد ممول من المنصة',
            'funded_by' => 'platform',
            'order_id' => $order->id,
        ]);

        $this->assertSame(-10.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_refund_request_reserves_amount_without_execution_and_can_only_execute_once(): void
    {
        Auth::guard('admin')->setUser($this->admin);
        $user = User::factory()->create();
        $order = $this->createOrder($this->driver, ['user_id' => $user->id, 'client_name' => $user->name]);
        $payment = $this->createCollection($order);
        $workflow = app(RefundExecutionWorkflow::class);

        $refund = $workflow->request(
            $order->id,
            $payment->id,
            '80.00',
            'استرداد جزئي',
            'driver',
            'company',
            $this->admin
        );

        $available = $workflow->availableForCollection($order, $payment);
        $this->assertSame('pending', $refund->status);
        $this->assertSame(80.0, $available['pending_amount']);
        $this->assertSame(20.0, $available['available_amount']);
        $this->assertSame(0, WalletTransaction::query()->where('user_id', $user->id)->count());
        $this->assertSame(0, (int) $payment->fresh()->refunded);
        $this->assertSame(100.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));

        $ledgerMovement = collect(app(OrderPaymentLedger::class)->forOrder($order)['movements'])
            ->firstWhere('record_id', $refund->id);
        $this->assertSame(0.0, (float) $ledgerMovement['amount']);
        $this->assertSame(80.0, $ledgerMovement['requested_amount']);
        $this->assertSame('pending', $ledgerMovement['refund_status']);

        $this->assertValidationFailure(fn () => $workflow->request(
            $order->id,
            $payment->id,
            '20.01',
            'يتجاوز المتاح بعد الطلب المعلق',
            'driver',
            'company',
            $this->admin
        ));

        $executed = $workflow->execute($refund->id, $this->admin);
        $this->assertSame('executed', $executed->status);
        $this->assertNotEmpty($executed->reference);
        $this->assertSame($this->admin->id, $executed->executed_by);
        $this->assertNotNull($executed->executed_at);
        $this->assertSame(8000, (int) $payment->fresh()->refunded);
        $this->assertSame(1, WalletTransaction::query()->where('user_id', $user->id)->count());
        $this->assertSame(80.0, (float) $user->getWallet('UID_' . $user->id . '_Main')->balanceFloatNum);
        $this->assertSame(20.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));

        $this->assertValidationFailure(fn () => $workflow->execute($refund->id, $this->admin));
        $this->assertSame(1, WalletTransaction::query()->where('user_id', $user->id)->count());
        $this->assertSame(20.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));

        $platformRefund = $workflow->request(
            $order->id,
            $payment->id,
            '10.00',
            'استرداد ممول من المنصة',
            'platform',
            'company',
            $this->admin
        );
        $workflow->execute($platformRefund->id, $this->admin);
        $this->assertSame(2, WalletTransaction::query()->where('user_id', $user->id)->count());
        $this->assertSame(20.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
    }

    public function test_refund_after_approved_settlement_is_linked_and_deducts_only_the_recipient_source(): void
    {
        Auth::guard('admin')->setUser($this->admin);
        $user = User::factory()->create();
        $order = $this->createOrder($this->driver, ['user_id' => $user->id, 'client_name' => $user->name]);
        $payment = $this->createCollection($order);
        $settlement = $this->createSettlement($order, $this->driver);
        $settlement->update(['recipient' => 'merchant']);
        app(SettlementWorkflow::class)->approve($settlement->id, $this->admin, $this->receipt());
        $this->assertSame($settlement->id, $payment->fresh()->settlement_id);

        $workflow = app(RefundExecutionWorkflow::class);
        $refund = $workflow->request(
            $order->id,
            $payment->id,
            '25.00',
            'استرداد بعد التوريد',
            'merchant',
            'store',
            $this->admin
        );
        $this->assertSame($settlement->id, $refund->settlement_id);

        $executed = $workflow->execute($refund->id, $this->admin);
        $this->assertSame($settlement->id, $executed->settlement_id);
        $this->assertSame(2500, (int) $payment->fresh()->refunded);
        $this->assertSame(1, WalletTransaction::query()->where('user_id', $user->id)->count());
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertSame(25.0, app(OrderPaymentLedger::class)->forOrder($order)['refunded']);
    }

    public function test_admin_refund_component_creates_a_pending_request_then_executes_it(): void
    {
        Auth::guard('admin')->setUser($this->admin);
        $user = User::factory()->create();
        $order = $this->createOrder($this->driver, ['user_id' => $user->id, 'client_name' => $user->name]);
        $this->createCollection($order);

        $component = Livewire::test(AdminRefundCreate::class, ['order' => $order])
            ->set('amount', '10.00')
            ->set('note', 'استرداد عبر لوحة الإدارة')
            ->set('calculated_on', 'company')
            ->call('store')
            ->assertHasNoErrors();

        $refund = RefundPayment::query()->where('order_id', $order->id)->firstOrFail();
        $this->assertSame('pending', $refund->status);
        $this->assertSame(0, WalletTransaction::query()->where('user_id', $user->id)->count());

        $component->call('executeRefund', $refund->id)->assertHasNoErrors();
        $this->assertSame('executed', $refund->fresh()->status);
        $this->assertNotEmpty($refund->fresh()->reference);
        $this->assertSame(1, WalletTransaction::query()->where('user_id', $user->id)->count());
    }

    public function test_admin_refund_list_exposes_collection_history_and_funding_details(): void
    {
        Auth::guard('admin')->setUser($this->admin);
        $user = User::factory()->create();
        $order = $this->createOrder($this->driver, ['user_id' => $user->id, 'client_name' => $user->name]);
        $payment = $this->createCollection($order);
        $workflow = app(RefundExecutionWorkflow::class);
        $firstRefund = $workflow->request(
            $order->id,
            $payment->id,
            '20.00',
            'استرداد منفذ سابقًا',
            'driver',
            'company',
            $this->admin
        );
        $workflow->execute($firstRefund->id, $this->admin);
        $pendingRefund = $workflow->request(
            $order->id,
            $payment->id,
            '10.00',
            'استرداد بانتظار التنفيذ',
            'platform',
            'company',
            $this->admin
        );

        $rows = (new AdminRefundTable())->builder()->orderBy('refund_payments.id')->get();
        $pendingRow = $rows->firstWhere('id', $pendingRefund->id);

        $this->assertSame(100.0, (float) $pendingRow->collection_amount);
        $this->assertSame(20.0, (float) $pendingRow->previously_refunded_amount);
        $this->assertSame(0.0, (float) $pendingRow->pending_before_amount);
        $this->assertSame('platform', $pendingRow->funded_by);
        $this->assertSame('wallet', $pendingRow->refund_method);
        $this->assertSame('executed', $rows->firstWhere('id', $firstRefund->id)->status);
    }

    public function test_only_executed_store_calculated_refunds_reduce_merchant_entitlement(): void
    {
        Auth::guard('admin')->setUser($this->admin);
        $user = User::factory()->create();
        $order = $this->createOrder($this->driver, [
            'user_id' => $user->id,
            'client_name' => $user->name,
        ]);
        $payment = $this->createCollection($order);
        $workflow = app(RefundExecutionWorkflow::class);
        $baselineMerchantDue = (float) $order->fresh()->dueToMerchantFromSub;

        $storeRefund = $workflow->request(
            $order->id,
            $payment->id,
            '10.00',
            'استرداد يخصم من مستحق التاجر',
            'merchant',
            'store',
            $this->admin
        );
        $this->assertSame($baselineMerchantDue, (float) $order->fresh()->dueToMerchantFromSub);
        $workflow->execute($storeRefund->id, $this->admin);
        $afterStoreRefund = (float) $order->fresh()->dueToMerchantFromSub;
        $this->assertSame(round($baselineMerchantDue - 10, 2), round($afterStoreRefund, 2));

        $platformRefund = $workflow->request(
            $order->id,
            $payment->id,
            '5.00',
            'استرداد تتحمله المنصة',
            'platform',
            'company',
            $this->admin
        );
        $workflow->execute($platformRefund->id, $this->admin);
        $this->assertSame($afterStoreRefund, (float) $order->fresh()->dueToMerchantFromSub);
    }

    public function test_correction_updates_ledger_without_mutating_origin_or_creating_refund_and_rejects_stale_requests(): void
    {
        Auth::guard('admin')->setUser($this->admin);
        $workflow = app(FinancialMovementCorrectionWorkflow::class);
        $order = $this->createOrder($this->driver);
        $payment = $this->createCollection($order);
        $settlement = $this->createSettlement($order, $this->driver);
        app(SettlementWorkflow::class)->approve($settlement->id, $this->admin, $this->receipt());

        $request = $workflow->request($order->id, 'collection', $payment->id, '90.00', 'تصحيح قيمة التحصيل', $this->admin);
        $workflow->approve($request->id, $this->admin);

        $this->assertSame(10000, (int) $payment->fresh()->captured);
        $this->assertSame(90.0, app(OrderPaymentLedger::class)->forOrder($order)['collected']);
        $this->assertSame(-10.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertSame(0, RefundPayment::query()->where('order_id', $order->id)->count());
        $this->assertSame($settlement->id, $request->fresh()->settlement_id);

        $staleOrder = $this->createOrder($this->driver);
        $stalePayment = $this->createCollection($staleOrder);
        $staleRequest = $workflow->request(
            $staleOrder->id,
            'collection',
            $stalePayment->id,
            '90.00',
            'تصحيح بعد تعديل الحركة',
            $this->admin
        );
        $stalePayment->update(['captured' => 9900]);

        $this->assertValidationFailure(fn () => $workflow->approve($staleRequest->id, $this->admin));
        $this->assertSame('pending', $staleRequest->fresh()->status);
        $this->assertSame(99.0, app(DriverCustodyLedger::class)->balanceForOrder($staleOrder->id, $this->driver->id));
    }

    public function test_driver_ownership_and_admin_correction_permission_are_enforced(): void
    {
        $order = $this->createOrder($this->otherDriver);
        $workflow = app(CollectionFailureWorkflow::class);

        $this->assertModelNotFound(
            fn () => $workflow->report($order->id, $this->driver->id, 'سبب', null, $this->driver)
        );
        $this->assertSame(0, DriverCollectionFailureReport::query()->where('order_id', $order->id)->count());

        $ownedOrder = $this->createOrder($this->driver);
        $payment = $this->createCollection($ownedOrder);
        Auth::guard('admin')->logout();
        $this->assertForbidden(
            fn () => app(FinancialMovementCorrectionWorkflow::class)->request(
                $ownedOrder->id,
                'collection',
                $payment->id,
                '90.00',
                'تصحيح غير مخول',
                $this->admin
            )
        );
        $this->assertSame(0, PaymentCorrectionRequest::query()->where('order_id', $ownedOrder->id)->count());

        $employee = Admin::query()->create([
            'name' => 'Acceptance Employee',
            'email' => 'acceptance-employee-' . Str::uuid() . '@example.test',
            'password' => Hash::make(Str::random(24)),
            'type' => 'employee',
            'status' => 1,
        ]);
        Auth::guard('admin')->setUser($employee);
        $settlement = $this->createSettlement($ownedOrder, $this->driver);
        $component = new \Application\Admin\Livewire\SettlementReview\Show();
        $component->settlementId = $settlement->id;

        $this->assertForbidden(
            fn () => $component->approve(app(SettlementWorkflow::class))
        );
        $this->assertSame('pending_review', $settlement->fresh()->status);
    }

    public function test_merchant_cannot_fetch_another_merchants_order(): void
    {
        $owner = $this->createMerchant();
        $otherMerchant = $this->createMerchant();
        $order = $this->createOrder($this->driver, ['merchant_id' => $owner->id]);
        $fetchOrder = app(FetchSingleOrderAction::class);

        $this->assertNull($fetchOrder->handle($order->id, $otherMerchant->id));
        $this->assertSame($order->id, $fetchOrder->handle($order->id, $owner->id)?->id);
    }

    public function test_merchant_employee_order_lists_and_reports_only_include_assigned_branches(): void
    {
        $merchant = $this->createMerchant();
        $employee = $this->createMerchantEmployee($merchant);
        $authorizedBranch = $this->createMerchantBranch($merchant, $employee);
        $otherBranch = $this->createMerchantBranch($merchant);
        $authorizedOrder = $this->createOrder($this->driver, [
            'merchant_id' => $merchant->id,
            'merchant_branch_id' => $authorizedBranch->id,
        ]);
        $otherBranchOrder = $this->createOrder($this->driver, [
            'merchant_id' => $merchant->id,
            'merchant_branch_id' => $otherBranch->id,
        ]);

        Auth::guard('merchant_employee')->setUser($employee);
        app()->instance('_InitialMerchant', $merchant->id);

        $ordersTable = new MerchantOrdersTable();
        $ordersTable->merchantId = $merchant->id;
        $this->assertSame(
            [$authorizedOrder->id],
            $ordersTable->builder()->pluck('merchant_orders.id')->all()
        );

        $reportTable = new MerchantOrderReportTable();
        $reportTable->merchantId = $merchant->id;
        $reportTable->filters = ['merchant_id' => $merchant->id];
        $this->assertSame(
            [$authorizedOrder->id],
            $reportTable->builder()->pluck('merchant_orders.id')->all()
        );
        $this->assertHidden(
            fn () => app(OrderController::class)->showOrder(
                $otherBranchOrder->id,
                app(FetchSingleOrderAction::class),
                Request::create('/merchant/orders/' . $otherBranchOrder->id)
            )
        );

        $this->assertNotContains(
            $otherBranchOrder->id,
            MerchantOrder::query()->forMerchantEmployee($employee)->pluck('id')->all()
        );
    }

    public function test_merchant_collection_report_uses_ledger_and_hides_unassigned_branch_orders(): void
    {
        $merchant = $this->createMerchant();
        $employee = $this->createMerchantEmployee($merchant);
        $authorizedBranch = $this->createMerchantBranch($merchant, $employee);
        $otherBranch = $this->createMerchantBranch($merchant);
        $authorizedOrder = $this->createOrder($this->driver, [
            'merchant_id' => $merchant->id,
            'merchant_branch_id' => $authorizedBranch->id,
        ]);
        $uncollectedOrder = $this->createOrder($this->driver, [
            'merchant_id' => $merchant->id,
            'merchant_branch_id' => $authorizedBranch->id,
        ]);
        $pendingSettlementOrder = $this->createOrder($this->driver, [
            'merchant_id' => $merchant->id,
            'merchant_branch_id' => $authorizedBranch->id,
        ]);
        $otherOrder = $this->createOrder($this->driver, [
            'merchant_id' => $merchant->id,
            'merchant_branch_id' => $otherBranch->id,
        ]);
        $this->createCollection($authorizedOrder);
        $authorizedOrder->update([
            'package_id' => 1,
            'commission_rate' => 0,
            'commission_amount' => 0,
        ]);
        $this->createCollection($otherOrder);
        $this->createSettlement($pendingSettlementOrder, $this->driver);

        Auth::guard('merchant_employee')->setUser($employee);
        app()->instance('_InitialMerchant', $merchant->id);

        Livewire::test(MerchantCollectionSettlementReport::class)
            ->assertSee($authorizedOrder->code)
            ->assertSee($uncollectedOrder->code)
            ->assertDontSee($otherOrder->code)
            ->assertSee('100.00')
            ->assertSee(__('marketplace::home.merchant_due_undefined'))
            ->assertSee(__('marketplace::home.payment_fee') . ': 0.00')
            ->assertSee(__('marketplace::home.not_remitted'))
            ->assertSee(__('marketplace::home.payout_not_recorded'))
            ->set('collectionStatus', 'collected')
            ->call('applyFilters')
            ->assertSee($authorizedOrder->code)
            ->assertDontSee($uncollectedOrder->code)
            ->assertDontSee($otherOrder->code)
            ->call('clearFilters')
            ->set('settlementStatus', 'pending_review')
            ->set('fromDate', now()->toDateString())
            ->set('toDate', now()->toDateString())
            ->call('applyFilters')
            ->assertSee($pendingSettlementOrder->code)
            ->assertDontSee($authorizedOrder->code)
            ->assertDontSee($otherOrder->code);
    }

    private function createDriver(): Driver
    {
        return Driver::query()->create([
            'name' => 'Acceptance Driver',
            'email' => 'acceptance-driver-' . Str::uuid() . '@example.test',
            'phone' => (string) random_int(500000000, 599999999),
            'password' => Hash::make(Str::random(24)),
            'status' => 'active',
        ]);
    }

    private function createMerchant(): Merchant
    {
        $currency = Currency::query()->create([
            'title' => ['en' => 'Acceptance Currency'],
            'iso' => 'ACT',
            'status' => 'active',
        ]);
        $country = Country::query()->create([
            'title' => ['en' => 'Acceptance Country'],
            'iso' => 'ACT',
            'vat' => 0,
            'currency_id' => $currency->id,
            'status' => 'active',
        ]);

        return Merchant::query()->create([
            'name' => 'Acceptance Merchant',
            'company_name' => ['en' => 'Acceptance Merchant'],
            'email' => 'acceptance-merchant-' . Str::uuid() . '@example.test',
            'country_id' => $country->id,
            'status' => 'active',
        ]);
    }

    private function createOrder(Driver $driver, array $overrides = []): MerchantOrder
    {
        return MerchantOrder::query()->create(array_merge([
            'code' => 'ACC-' . Str::upper(Str::random(12)),
            'channel' => 'mobile',
            'order_type' => 'fixed',
            'payment_method' => 'cod',
            'status' => MerchantOrderStatuses::PREVIOUS->value,
            'driver_id' => $driver->id,
            'total_after_vat' => 100,
            'total_after_discount' => 100,
        ], $overrides));
    }

    private function createCollection(MerchantOrder $order, float $amount = 100): Payment
    {
        $amountInHalalas = (int) round($amount * 100);

        return $order->payment()->create([
            'payment_id' => 'COD-' . $order->code,
            'status' => 'paid',
            'amount' => $amountInHalalas,
            'captured' => $amountInHalalas,
            'currency' => 'SAR',
            'capturedAt' => now()->toIso8601String(),
            'source_type' => 'cash',
            'source_name' => $this->driver->name,
            'source_number' => (string) $this->driver->id,
            'source_message' => MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE,
            'channel' => $order->channel,
        ]);
    }

    private function createSettlement(MerchantOrder $order, Driver $driver): OrderSettlement
    {
        return OrderSettlement::query()->create([
            'order_id' => $order->id,
            'driver_id' => $driver->id,
            'recipient' => 'platform',
            'method' => 'cash',
            'recipient_name' => 'صندوق المنصة',
            'instructions' => 'تسليم نقدي',
            'amount' => 100,
            'status' => 'pending_review',
        ]);
    }

    private function receipt(): array
    {
        return [
            'received_amount' => 100,
            'received_at' => now(),
            'received_by' => 'Acceptance Receiver',
            'receipt_confirmed' => true,
        ];
    }

    private function paymentCount(MerchantOrder $order): int
    {
        return Payment::query()
            ->where('payable_id', $order->id)
            ->where('payable_type', $order->getMorphClass())
            ->count();
    }

    private function assertValidationFailure(callable $action): void
    {
        try {
            $action();
            $this->fail('Expected validation failure was not raised.');
        } catch (ValidationException $exception) {
            $this->assertNotEmpty($exception->errors());
        }
    }

    private function assertModelNotFound(callable $action): void
    {
        try {
            $action();
            $this->fail('Expected missing model to be hidden from the caller.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            $this->assertInstanceOf(\Illuminate\Database\Eloquent\ModelNotFoundException::class, $exception);
        }
    }

    private function assertForbidden(callable $action): void
    {
        try {
            $action();
            $this->fail('Expected unauthorized action to be rejected.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
    }

    private function assertHidden(callable $action): void
    {
        try {
            $action();
            $this->fail('Expected unauthorized branch order to be hidden.');
        } catch (HttpException $exception) {
            $this->assertSame(404, $exception->getStatusCode());
        }
    }
}
