<?php

namespace Application\Driver\Controllers;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Services\OrderPaymentLedger;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function index()
    {
         return view('driver::welcome');
    }

    public function getHomePage()
    {
        $driver = Auth::guard('driver')->user();
        if (!$driver) {
            return redirect(RouteServiceProvider::DRIVER_LOGIN);
        }

        $orders = MerchantOrder::where('driver_id', $driver->id);

        $stats = [
            'ordersCount' => (clone $orders)->count(),
            'pendingOrdersCount' => (clone $orders)->where('status', 'pending')->count(),
            'readyOrdersCount' => (clone $orders)->where('status', 'ready')->count(),
            'completedOrdersCount' => (clone $orders)->where('status', 'completed')->count(),
            'cancelledOrdersCount' => (clone $orders)->where('status', 'cancelled')->count(),
            'expiredOrdersCount' => (clone $orders)->where('status', 'expired')->count(),
        ];

        return view('driver::pages.home.index', compact('driver', 'stats'));
    }

    public function myCustody()
    {
        $driver = Auth::guard('driver')->user();
        if (!$driver) {
            return redirect(RouteServiceProvider::DRIVER_LOGIN);
        }

        return view('driver::pages.custody.index');
    }

    public function mySettlements()
    {
        if (!Auth::guard('driver')->user()) {
            return redirect(RouteServiceProvider::DRIVER_LOGIN);
        }

        return view('driver::pages.settlement.index');
    }

    public function acceptOrder($id)
    {
        $driver = Auth::guard('driver')->user();
        if (!$driver) {
            return redirect(RouteServiceProvider::DRIVER_LOGIN);
        }

        $order = MerchantOrder::where('id', $id)->where('driver_id', $driver->id)->firstOrFail();
        $status = $order->status instanceof MerchantOrderStatuses
            ? $order->status
            : MerchantOrderStatuses::tryFrom((string) $order->status);

        if (!in_array($status, [MerchantOrderStatuses::PENDING, MerchantOrderStatuses::UPCOMING, MerchantOrderStatuses::READY], true)) {
            return redirect()->back()->with('error', 'لا يمكن قبول الطلب في حالته الحالية.');
        }

        if ($status !== MerchantOrderStatuses::READY || !$order->isAcceptedByDriver($driver->id)) {
            $order->update([
                'status' => $status === MerchantOrderStatuses::READY
                    ? MerchantOrderStatuses::READY->value
                    : MerchantOrderStatuses::CURRENT->value,
                'commission_from_driver' => $driver->taken_commission ?? 0,
            ]);

            $logOrderHistoryAction = app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class);
            $logOrderHistoryAction->handle(
                $order->id,
                MerchantOrderHistoryStatuses::DRIVER_ACCEPT->value,
                'Driver accepted order via web',
                $driver
            );
        }

        return redirect()->back()->with('success', __('admin::home.Order Accepted Successfully'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $driver = Auth::guard('driver')->user();
        if (!$driver) {
            return redirect(RouteServiceProvider::DRIVER_LOGIN);
        }

        $order = MerchantOrder::where('id', $id)->where('driver_id', $driver->id)->firstOrFail();

        $status = $request->input('status');
        $historyStatus = null;
        $currentStatus = $order->status instanceof MerchantOrderStatuses
            ? $order->status
            : MerchantOrderStatuses::tryFrom((string) $order->status);

        if ($status === 'on-way') {
            if (!in_array($currentStatus, [MerchantOrderStatuses::CURRENT, MerchantOrderStatuses::READY], true)
                || !$order->isAcceptedByDriver($driver->id)) {
                return redirect()->back()->with('error', 'يجب قبول الطلب قبل بدء التوصيل.');
            }

            $order->update(['status' => MerchantOrderStatuses::ON_WAY->value]);
            $historyStatus = MerchantOrderHistoryStatuses::DRIVER_ON_WAY->value;
        } elseif ($status === 'completed' || $status === 'previous') {
            if ($currentStatus !== MerchantOrderStatuses::ON_WAY) {
                return redirect()->back()->with('error', 'لا يمكن إكمال الطلب قبل بدء التوصيل.');
            }
            if (MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
                return redirect()->back()->with('error', 'أكد التسليم والتحصيل من نافذة تأكيد الطلب.');
            }
            if (app(OrderPaymentLedger::class)->forOrder($order)['payment_status'] === 'paid') {
                return redirect()->back()->with('error', 'أكد تسليم الطلب المدفوع مسبقًا من نافذة تفاصيل الطلب.');
            }

            $order->update(['status' => MerchantOrderStatuses::PREVIOUS->value]);
            $historyStatus = MerchantOrderHistoryStatuses::COMPLETED->value;
        } else {
            return redirect()->back()->with('error', 'حالة الطلب غير صالحة.');
        }

        if ($historyStatus) {
            $logOrderHistoryAction = app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class);
            $logOrderHistoryAction->handle(
                $order->id,
                $historyStatus,
                'Driver updated status via web',
                $driver
            );
        }

        return redirect()->back()->with('success', __('admin::home.Order status updated successfully'));
    }
}
