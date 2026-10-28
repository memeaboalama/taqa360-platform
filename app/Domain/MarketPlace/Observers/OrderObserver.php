<?php

namespace App\Domain\MarketPlace\Observers;

use App\Domain\MarketPlace\Models\Cart;
use App\Domain\MarketPlace\Models\CartServiceAddon;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Domain\MarketPlace\Models\ElmOrder;

class OrderObserver
{

//    public function created(MerchantOrder $order): void
//    {
//        $cart = request()->user()->cart;
//        foreach ($cart->cart_services as $cart_service) {
//            $service_order = $order->services()->create([
//                'service_name' => $cart_service?->service?->getTranslations('title'),
//                'service_total' => $cart_service->subtotal,
//                'vat' => $cart_service->subtotal * (15 / 100),
//                'total_after_vat' => ($cart_service->subtotal * (15 / 100)) + ($cart_service->subtotal),
//                'merchant_id' => $order->merchant_id,
//                'user_id' => $order->merchant_id,
//                'merchant_branch_id' => $order->merchant_branch_id,
//                'merchant_service_id' => $cart_service->merchant_service_id,
//            ]);
//            if ($cart_service->addons()->get()->isNotEmpty()) {
//                foreach ($cart_service->addons as $addon){
//                    $service_order->addons()->create([
//                        'addon_name' => $addon?->addon?->getTranslations('title'),
//                        'addon_total' => $cart_service->totalAddons,
//                        'total_after_vat' =>($cart_service->totalAddons * (15 / 100)) + ($cart_service->totalAddons),
//                        'merchant_id' => $order->merchant_id,
//                        'user_id' => $order->merchant_id,
//                        'order_id' => $order->id,
//                        'merchant_branch_id' => $order->merchant_branch_id,
//                        'merchant_service_id' => $cart_service->merchant_service_id,
//                        'addon_id' => $addon->service_addon_id,
//
//                    ]);
//                }
//
//            }
//        }
//
//
//    }

    public function created(MerchantOrder $order): void
    {
        //  ($this->logOrderHistoryAction)(
        //         $order->id,
        //         MerchantOrderHistoryStatuses::CREATE_ORDER->value
        //     );
        $elmOrder = ElmOrder::create([
            'order_id' => $order->id,
            'deliveryTime' => $order->created_at,
        ]);

        $elmOrder ? $order->update(['elm_order_id' => $elmOrder->id]) : null;
    }
}
