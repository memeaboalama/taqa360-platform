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
            'ordersCount'          => (clone $orders)->count(),
            'preparingOrdersCount' => (clone $orders)->where('status', MerchantOrderStatuses::CURRENT->value)->count(),   // started-work: السائق قبل والتاجر يجهّز
            'readyOrdersCount'     => (clone $orders)->where('status', MerchantOrderStatuses::READY->value)->count(),     // ready: جاهز للاستلام
            'onWayOrdersCount'     => (clone $orders)->where('status', MerchantOrderStatuses::ON_WAY->value)->count(),    // on-way: في الطريق للعميل
            'completedOrdersCount' => (clone $orders)->where('status', MerchantOrderStatuses::PREVIOUS->value)->count(),  // completed: مكتمل
            'cancelledOrdersCount' => (clone $orders)->where('status', MerchantOrderStatuses::CANCELLED->value)->count(), // cancelled: ملغي
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

        $order = MerchantOrder::where('id', $id)
            ->where(function ($q) use ($driver) {
                $q->where('driver_id', $driver->id)
                    ->orWhere(function ($sub) use ($driver) {
                        $sub->whereNull('driver_id')
                            ->whereHas('temp_orders', function ($temp) use ($driver) {
                                $temp->where('driver_id', $driver->id)->where('has_accept', false);
                            });
                    });
            })
            ->firstOrFail();
        $status = $order->status instanceof MerchantOrderStatuses
            ? $order->status
            : MerchantOrderStatuses::tryFrom((string) $order->status);

        // يجب أن يكون الطلب بحالة UPCOMING (confirmed) أو READY فقط
        // PENDING يعني أن التاجر لم يقبله بعد، فلا يحق للسائق قبوله
        if (!in_array($status, [MerchantOrderStatuses::UPCOMING, MerchantOrderStatuses::READY], true)) {
            return redirect()->back()->with('error', 'لا يمكن قبول الطلب في حالته الحالية. يجب انتظار قبول التاجر أولاً.');
        }

        if ($status !== MerchantOrderStatuses::READY || !$order->isAcceptedByDriver($driver->id)) {
            $order->update([
                'driver_id' => $driver->id,
                'status' => $status === MerchantOrderStatuses::READY
                    ? MerchantOrderStatuses::READY->value
                    : MerchantOrderStatuses::CURRENT->value,
                'commission_from_driver' => $driver->taken_commission ?? 0,
            ]);

            \Domain\MarketPlace\Models\TempOrder::query()
                ->where('order_id', $order->id)
                ->where('driver_id', $driver->id)
                ->update(['has_accept' => true]);

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
