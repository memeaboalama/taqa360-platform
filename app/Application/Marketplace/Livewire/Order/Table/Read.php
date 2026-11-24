<?php

namespace Application\Marketplace\Livewire\Order\Table;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\Driver\Jobs\SearchForDriverJob;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\Notification\Jobs\User\Order\NotifyAcceptedOrder;
use App\Support\Enum\Elm\ElmOrderStatuses;
use App\Domain\ElmApp\Actions\Order\ManuallyAssignDriverAction;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Facades\DB;

class Read extends Component
{
    use LivewireAlert;

    public $order;
    public $orderUser;
    public $orderServices;
    public $address;
    public $selected_driver_id;
    public string $adminNoteDraft = '';

    protected $listeners = ['refreshDatatable' => '$refresh', 'refreshOrder' => '$refresh'];

    public function mount($order, $orderServices, $orderUser, $address)
    {
        $this->order = $order;
        $this->orderServices = $orderServices;
        $this->orderUser = $orderUser;
        $this->address = $address;
        $this->adminNoteDraft = (string) ($order->admin_note ?? '');
    }

    public function acceptOrder(): void
    {
        $user = $this->actionUser();
        if (!$user) {
            $this->alert('error', __('marketplace::home.order_action_not_authorized'));
            return;
        }

        $acceptedOrder = DB::transaction(function () use ($user) {
            $order = $this->merchantOrderQuery()
                ->lockForUpdate()
                ->whereKey($this->order->id)
                ->firstOrFail();
            $status = $order->status instanceof MerchantOrderStatuses
                ? $order->status->value
                : (string) $order->status;

            if ($status !== MerchantOrderStatuses::PENDING->value) {
                return null;
            }

            $order->status = MerchantOrderStatuses::UPCOMING;
            $order->save();

            app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class)
                ->handle(
                    $order->id,
                    MerchantOrderHistoryStatuses::EMPLOYEE_ACCEPT->value,
                    'Order accepted via web',
                    $user
                );

            $order->loadMissing(['user', 'elmOrder']);
            if ($order->elmOrder) {
                $order->elmOrder->update([
                    'elm_upload_status' => ElmOrderStatuses::ACCEPTED_BY_MERCHANT->value,
                ]);
            }

            return $order;
        });

        if (!$acceptedOrder) {
            $this->order->refresh();
            $this->alert('warning', __('marketplace::home.order_status_changed_try_again'));
            return;
        }

        if ((string) $acceptedOrder->service_type_id !== '2') {
            dispatch(new SearchForDriverJob($acceptedOrder));
        }
        if ($acceptedOrder->user) {
            dispatch(new NotifyAcceptedOrder($acceptedOrder, $acceptedOrder->user));
        }

        $this->order->refresh();
        $this->emit('refreshDatatable');
        $this->alert('success', __('marketplace::home.order_accepted_successfully'));
    }

    public function startOrder(): void
    {
        $user = $this->actionUser();
        if (!$user) {
            $this->alert('error', __('marketplace::home.order_action_not_authorized'));
            return;
        }

        $startedOrder = DB::transaction(function () use ($user) {
            $order = $this->merchantOrderQuery()
                ->lockForUpdate()
                ->whereKey($this->order->id)
                ->firstOrFail();
            $status = $order->status instanceof MerchantOrderStatuses
                ? $order->status->value
                : (string) $order->status;

            if (
                $status !== MerchantOrderStatuses::UPCOMING->value
                || (string) $order->service_type_id !== '2'
            ) {
                return null;
            }

            $order->status = MerchantOrderStatuses::CURRENT;
            $order->save();

            app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class)
                ->handle(
                    $order->id,
                    MerchantOrderHistoryStatuses::START_COOKING->value,
                    'Pickup order preparation started via web',
                    $user
                );

            return $order;
        });

        if (!$startedOrder) {
            $this->order->refresh();
            $this->alert('warning', __('marketplace::home.order_status_changed_try_again'));
            return;
        }

        $this->order->refresh();
        $this->emit('refreshDatatable');
        $this->alert('success', __('marketplace::home.order_started_successfully'));
    }

    public function markAsReady(): void
    {
        $user = $this->actionUser();
        if (!$user) {
            $this->alert('error', __('marketplace::home.order_action_not_authorized'));
            return;
        }

        $readyOrder = DB::transaction(function () use ($user) {
            $order = $this->merchantOrderQuery()
                ->lockForUpdate()
                ->whereKey($this->order->id)
                ->firstOrFail();
            $status = $order->status instanceof MerchantOrderStatuses
                ? $order->status->value
                : (string) $order->status;

            if (
                $status !== MerchantOrderStatuses::CURRENT->value
                || ((string) $order->service_type_id !== '2' && !$order->driver_id)
            ) {
                return null;
            }

            $order->status = MerchantOrderStatuses::READY;
            $order->save();

            app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class)
                ->handle(
                    $order->id,
                    MerchantOrderHistoryStatuses::MARKED_READY->value,
                    'Order marked as ready via web',
                    $user
                );

            return $order;
        });

        if (!$readyOrder) {
            $this->order->refresh();
            $this->alert('warning', __('marketplace::home.order_status_changed_try_again'));
            return;
        }

        $this->order->refresh();
        $this->emit('refreshDatatable');
        $this->alert('success', __('marketplace::home.order_marked_ready_successfully'));
    }

    public function assignDriver(): void
    {
        $user = $this->actionUser();
        if (!$user) {
            $this->alert('error', __('marketplace::home.order_action_not_authorized'));
            return;
        }

        if (!$this->selected_driver_id) {
            $this->alert('warning', __('marketplace::home.please_select_driver'));
            return;
        }

        $result = DB::transaction(function () use ($user) {
            $order = $this->merchantOrderQuery()
                ->lockForUpdate()
                ->whereKey($this->order->id)
                ->firstOrFail();
            $status = $order->status instanceof MerchantOrderStatuses
                ? $order->status->value
                : (string) $order->status;

            if (
                !$order->driver_id
                && (string) $order->service_type_id !== '2'
                && in_array($status, [
                    MerchantOrderStatuses::UPCOMING->value,
                    MerchantOrderStatuses::READY->value,
                ], true)
            ) {
                $driver = Driver::query()
                    ->whereKey($this->selected_driver_id)
                    ->where('status', 'active')
                    ->first();

                if (!$driver) {
                    return 'driver_unavailable';
                }

                $assigned = app(ManuallyAssignDriverAction::class)->handle(
                    $order->id,
                    [
                        'driver_id' => $driver->id,
                        'commission_from_driver' => $driver->taken_commission ?? 0,
                    ],
                    $user,
                    'Driver manually assigned via web'
                );

                return $assigned ? 'assigned' : 'assignment_failed';
            }

            return 'order_changed';
        });

        if ($result !== 'assigned') {
            $this->order->refresh();
            if ($result === 'driver_unavailable') {
                $this->alert('warning', __('marketplace::home.driver_not_available'));
            } elseif ($result === 'assignment_failed') {
                $this->alert('error', __('marketplace::home.driver_assignment_failed'));
            } else {
                $this->alert('warning', __('marketplace::home.order_status_changed_try_again'));
            }
            return;
        }

        $this->selected_driver_id = null;
        $this->order->refresh();
        $this->emit('refreshDatatable');
        $this->alert('success', __('marketplace::home.driver_assigned_successfully'));
    }

    protected function actionUser()
    {
        return auth()->user() ?? auth('merchant')->user() ?? auth('merchant_employee')->user();
    }

    protected function merchantOrderQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $merchantId = app()->bound('_InitialMerchant')
            ? app('_InitialMerchant')
            : (auth('merchant')->id() ?? auth('merchant_employee')->user()?->merchant_id);

        abort_unless($merchantId, 403);

        $query = MerchantOrder::query()
            ->where('merchant_id', $merchantId);

        if (auth('merchant_employee')->check()) {
            $query->forMerchantEmployee(auth('merchant_employee')->user());
        }

        return $query;
    }


    public function cancelOrder(): void
    {
        $cancelAction = app(\App\Domain\MarketPlace\Actions\Dashboard\Order\CancelOrderAction::class);
        $cancelAction->handle($this->order->id);

        $logHistory = app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class);
        $user = $this->actionUser();
        $logHistory->handle(
            $this->order->id,
            \App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses::USER_CANCELLED->value,
            'Merchant cancelled order via web',
            $user
        );

        $this->order->refresh();
        $this->emit('refreshDatatable');
        $this->alert('success', __('marketplace::home.order_cancelled_successfully'));
    }

    public function render()
    {
        $this->order->loadMissing([
            'orderItems.item',
            'orderItems.orderChoices.choice',
            'orderItems.orderChoices.group',
            'driver',
            'type_service',
        ]);

        $orderHistory = $this->order->orders_history()
            ->orderBy('created_at')
            ->get();
        $availableDrivers = Driver::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'phone']);

        $paymentLedger = app(\App\Domain\MarketPlace\Services\OrderPaymentLedger::class)
            ->forOrder($this->order);

        return view('marketplace::livewire.order.table.read', [
            'order' => $this->order,
            'orderServices' => $this->orderServices,
            'orderUser' => $this->orderUser,
            'address' => $this->address,
            'orderHistory' => $orderHistory,
            'availableDrivers' => $availableDrivers,
            'paymentLedger' => $paymentLedger,
        ]);
    }
}
