<?php

namespace Tests\Unit;

use App\Domain\MarketPlace\Models\Cart;
use App\Domain\Ecommerce\Models\WebCart;
use Tests\TestCase;

class WebCartVatCalculationTest extends TestCase
{
    public function test_both_cart_flows_add_the_configured_country_rate_to_the_subtotal(): void
    {
        $country = (object) ['vat' => 14];
        $merchant = (object) ['country' => $country];
        $item = (object) ['merchant' => $merchant];
        $cartItem = (object) ['subtotal' => 120, 'item' => $item];

        $webCart = new class extends WebCart {
            public function validCoupon()
            {
                return false;
            }
        };
        $webCart->service_type_id = 2;
        $webCart->setRelation('cartItems', collect([$cartItem]));

        $apiCart = new class extends Cart {
            public function validCoupon()
            {
                return false;
            }
        };
        $apiCart->service_type_id = 2;
        $apiCart->setRelation('cartItems', collect([$cartItem]));

        foreach ([$webCart, $apiCart] as $cart) {
            $this->assertSame(14.0, $cart->vat_rate);
            $this->assertEqualsWithDelta(16.80, $cart->tax, 0.001);
        }

        $this->assertEqualsWithDelta(136.80, $webCart->total_after_vat, 0.001);
        $this->assertEqualsWithDelta(136.80, $apiCart->total, 0.001);
    }

    public function test_vat_rate_defaults_to_zero_when_cart_has_no_country(): void
    {
        $cart = new WebCart();
        $cart->setRelation('cartItems', collect([(object) ['subtotal' => 120, 'item' => null]]));

        $this->assertSame(0.0, $cart->vat_rate);
        $this->assertSame(0.0, $cart->tax);
    }
}
