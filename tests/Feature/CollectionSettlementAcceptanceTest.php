<?php

namespace Tests\Feature;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
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
use App\Domain\MarketPlace\Services\CollectionFailureWorkflow;
use App\Domain\MarketPlace\Services\DriverCustodyLedger;
use App\Domain\MarketPlace\Services\FinancialMovementCorrectionWorkflow;
use App\Domain\MarketPlace\Services\OrderPaymentLedger;
use App\Domain\MarketPlace\Services\RefundExecutionWorkflow;
use App\Domain\MarketPlace\Services\SettlementWorkflow;
use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchSingleOrderAction;
use Application\Admin\Livewire\RefundPayment\Modal\Create as AdminRefundCreate;
use Application\Admin\Livewire\RefundPayment\Table\All as AdminRefundTable;
use Application\Marketplace\Livewire\Order\Table\All as MerchantOrdersTable;
use Application\Marketplace\Livewire\Finance\Reports\Order\Table\All as MerchantOrderReportTable;
use Application\Marketplace\Controllers\OrderController;
use Application\Marketplace\Livewire\Finance\Reports\Order\Components\CollectionSettlement as MerchantCollectionSettlementReport;
use Application\Driver\Livewire\Home\Order\Modal\Show as DriverOrderShow;
use Domain\Admin\Models\Admin;
use Domain\AppBase\Models\Payment;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;
use Domain\MarketPlace\Models\MerchantEmployee;
use App\Domain\AppBase\Models\WalletTransaction;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $report = $workflow->report($order->id, $this->driver->id, 'تعذر الوصول للعميل', null, $this->driver);

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
        $report = $workflow->report($order->id, $this->driver->id, 'تعذر الوصول للعميل', null, $this->driver);
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

    public function test_delivery_completion_does_not_create_payment_or_driver_custody(): void
    {
        $order = $this->createOrder($this->driver, ['status' => MerchantOrderStatuses::ON_WAY->value]);
        $response = $this->actingAs($this->driver, 'driver')
            ->post(route('driver:orders.updateStatus', $order->id), ['status' => 'completed']);

        $response->assertRedirect();
        $this->assertSame(MerchantOrderStatuses::PREVIOUS->value, $order->fresh()->status->value);
        $this->assertSame(0, $this->paymentCount($order));
        $this->assertSame(0.0, app(DriverCustodyLedger::class)->balanceForOrder($order->id, $this->driver->id));
        $this->assertSame('pending', app(OrderPaymentLedger::class)->forOrder($order)['payment_status']);

        Auth::guard('driver')->setUser($this->driver);
        Livewire::test(DriverOrderShow::class)
            ->set('order', ['id' => $order->id])
            ->call('confirmCashCollection')
            ->assertHasNoErrors();

        $this->assertSame('paid', app(OrderPaymentLedger::class)->forOrder($order->fresh())['payment_status']);
        $this->assertSame(1, $this->paymentCount($order));
    }

    public function test_failed_report_history_write_rolls_back_collection_and_report_state(): void
    {
        $order = $this->createOrder($this->driver);
        $workflow = app(CollectionFailureWorkflow::class);
        $report = $workflow->report($order->id, $this->driver->id, 'تعذر الوصول للعميل', null, $this->driver);
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
        $report = $workflow->report($order->id, $this->driver->id, 'تعذر التحصيل', 'ملاحظة', $this->driver);
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
