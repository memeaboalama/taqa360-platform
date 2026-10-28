<?php

namespace Application\Driver\Controllers;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
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

        $perPage = (int) request('perPage', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $recentOrders = (clone $orders)->with('user')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'page')
            ->withQueryString();

        return view('driver::pages.home.index', compact('driver', 'stats', 'recentOrders'));
    }

    public function acceptOrder($id)
    {
        $driver = Auth::guard('driver')->user();
        if (!$driver) {
            return redirect(RouteServiceProvider::DRIVER_LOGIN);
        }

        $order = MerchantOrder::where('id', $id)->where('driver_id', $driver->id)->firstOrFail();

        $order->update([
            'status' => \App\Application\Marketplace\Enums\MerchantOrderStatuses::CURRENT->value,
            'commission_from_driver' => $driver->taken_commission ?? 0
        ]);

        $logOrderHistoryAction = app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class);
        $logOrderHistoryAction->handle(
            $order->id,
            \App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses::DRIVER_ACCEPT->value,
            'Driver accepted order via web',
            $driver
        );

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

        if ($status === 'on-way') {
            $order->update(['status' => \App\Application\Marketplace\Enums\MerchantOrderStatuses::ON_WAY->value]);
            $historyStatus = \App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses::DRIVER_ON_WAY->value;
        } elseif ($status === 'completed' || $status === 'previous') {
            $order->update(['status' => \App\Application\Marketplace\Enums\MerchantOrderStatuses::PREVIOUS->value]);
            $historyStatus = \App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses::COMPLETED->value;
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
