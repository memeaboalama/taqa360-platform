<?php

namespace Tests\Feature\Cart;

use App\Domain\User\Models\User;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class CheckoutItemRemovalTest extends TestCase
{
    private User $user;
    private Merchant $merchant;
    private int $countryId;
    private int $currencyId;
    private Item $itemToRemove;
    private Item $remainingItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->currencyId = DB::table('currencies')->insertGetId([
            'title' => json_encode(['en' => 'Test Currency', 'ar' => 'عملة اختبار']),
            'iso' => 'tst',
        ]);
        $this->countryId = DB::table('countries')->insertGetId([
            'title' => json_encode(['en' => 'Test Country', 'ar' => 'بلد اختبار']),
            'iso' => 'tst',
            'uuid' => (string) Str::uuid(),
            'currency_id' => $this->currencyId,
            'vat' => 14,
            'status' => 'active',
            'priority' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->merchant = Merchant::create(['country_id' => $this->countryId, 'status' => 'active']);
        $this->itemToRemove = $this->createItem('Checkout item to remove', 15);
        $this->remainingItem = $this->createItem('Checkout remaining item', 30);
    }

    protected function tearDown(): void
    {
        if (isset($this->user)) {
            $cartIds = DB::table('web_carts')->where('user_id', $this->user->id)->pluck('id');
            if ($cartIds->isNotEmpty()) {
                DB::table('web_cart_items')->whereIn('cart_id', $cartIds)->delete();
                DB::table('web_carts')->whereIn('id', $cartIds)->delete();
            }
            $this->user->forceDelete();
        }

        if (isset($this->itemToRemove, $this->remainingItem)) {
            DB::table('items')->whereIn('id', [$this->itemToRemove->id, $this->remainingItem->id])->delete();
        }
        if (isset($this->merchant)) {
            DB::table('merchants')->where('id', $this->merchant->id)->delete();
        }
        if (isset($this->countryId)) {
            DB::table('countries')->where('id', $this->countryId)->delete();
        }
        if (isset($this->currencyId)) {
            DB::table('currencies')->where('id', $this->currencyId)->delete();
        }

        parent::tearDown();
    }

    public function test_checkout_item_can_be_removed_and_totals_are_recalculated(): void
    {
        $this->actingAs($this->user);
        $cart = $this->user->webCart()->create(['service_type_id' => 2]);
        $cart->cartItems()->create(['item_id' => $this->itemToRemove->id, 'qty' => 1]);
        $cart->cartItems()->create(['item_id' => $this->remainingItem->id, 'qty' => 2]);
        $this->assertDatabaseHas('web_cart_items', ['item_id' => $this->itemToRemove->id]);
        $this->assertDatabaseHas('web_cart_items', ['item_id' => $this->remainingItem->id]);

        $this->get(route('checkout'))
            ->assertOk()
            ->assertSee('checkout-back-link', false)
            ->assertSee(__('ecommerce::ecommerce.back_to_cart'))
            ->assertSee('checkout-item-remove', false)
            ->assertSee('form="remove-checkout-item-0"', false);

        $this->from(route('checkout'))
            ->post(route('cart.remove'), ['item_id' => $this->itemToRemove->id])
            ->assertRedirect(route('checkout'));

        $cart = $this->user->webCart()->with('cartItems.item.merchant.country')->firstOrFail();
        $this->assertCount(1, $cart->cartItems);
        $this->assertSame((int) $this->remainingItem->id, (int) $cart->cartItems->first()->item_id);
        $this->assertEqualsWithDelta(60, $cart->subtotal, 0.001);
        $this->assertEqualsWithDelta(60 * $cart->vat_rate / 100, $cart->tax, 0.001);
        $this->assertEqualsWithDelta(
            60 + (60 * $cart->vat_rate / 100),
            $cart->total_after_vat,
            0.001
        );

        $this->from(route('checkout'))
            ->post(route('cart.remove'), ['item_id' => $this->remainingItem->id])
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseMissing('web_carts', ['user_id' => $this->user->id]);
    }

    private function createItem(string $title, float $price): Item
    {
        return Item::create([
            'title' => $title,
            'description' => $title,
            'merchant_id' => $this->merchant->id,
            'default_price' => $price,
            'stock' => 5,
            'status' => 'active',
        ]);
    }
}
