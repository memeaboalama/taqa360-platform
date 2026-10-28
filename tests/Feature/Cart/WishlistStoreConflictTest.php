<?php

namespace Tests\Feature\Cart;

use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\User\Models\User;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Tests\TestCase;

class WishlistStoreConflictTest extends TestCase
{
    private User $user;
    private Item $itemA;
    private Item $itemB;
    private Merchant $merchantA;
    private Merchant $merchantB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->merchantA = Merchant::create(['country_id' => 1, 'status' => 'active']);
        $this->merchantB = Merchant::create(['country_id' => 1, 'status' => 'active']);

        $this->itemA = Item::create([
            'title' => 'Wishlist conflict item A',
            'description' => 'Wishlist conflict item A',
            'merchant_id' => $this->merchantA->id,
            'default_price' => 10,
            'stock' => 5,
            'status' => 'active',
        ]);

        $this->itemB = Item::create([
            'title' => 'Wishlist conflict item B',
            'description' => 'Wishlist conflict item B',
            'merchant_id' => $this->merchantB->id,
            'default_price' => 20,
            'stock' => 5,
            'status' => 'active',
        ]);
    }

    protected function tearDown(): void
    {
        if (isset($this->user)) {
            $cartIds = \DB::table('web_carts')->where('user_id', $this->user->id)->pluck('id');
            if ($cartIds->isNotEmpty()) {
                \DB::table('web_cart_items')->whereIn('cart_id', $cartIds)->delete();
                \DB::table('web_carts')->whereIn('id', $cartIds)->delete();
            }
            \DB::table('favorites')->where('user_id', $this->user->id)->delete();
            $this->user->forceDelete();
        }
        if (isset($this->itemA)) {
            \DB::table('items')->whereIn('id', [$this->itemA->id, $this->itemB->id])->delete();
        }
        if (isset($this->merchantA)) {
            \DB::table('merchants')->whereIn('id', [$this->merchantA->id, $this->merchantB->id])->delete();
        }

        parent::tearDown();
    }

    public function test_conflict_returns_json_payload_for_ajax_requests(): void
    {
        $this->actingAs($this->user);

        $this->postJson(route('cart.add'), ['item_id' => $this->itemA->id, 'qty' => 1])
            ->assertOk()
            ->assertJson(['success' => true]);

        $response = $this->postJson(route('cart.add'), ['item_id' => $this->itemB->id, 'qty' => 1]);

        $response->assertStatus(409);
        $response->assertJson([
            'success' => false,
            'conflict' => true,
            'item_id' => $this->itemB->id,
            'qty' => 1,
        ]);
        $this->assertSame(
            __('ecommerce::ecommerce.cart.store_conflict'),
            $response->json('message')
        );
    }

    public function test_conflict_never_leaks_raw_json_to_a_plain_browser_post(): void
    {
        $this->actingAs($this->user);

        $this->post(route('cart.add'), ['item_id' => $this->itemA->id, 'qty' => 1]);

        $response = $this->from(route('wishlist.index'))
            ->post(route('cart.add'), ['item_id' => $this->itemB->id, 'qty' => 1]);

        $response->assertRedirect(route('wishlist.index'));
        $response->assertSessionHas('error');
        $this->assertStringNotContainsString('"conflict":true', $response->getContent());
        $this->assertStringNotContainsString('success', $response->getContent());
        $this->assertStringContainsString(
            __('ecommerce::ecommerce.cart.store_conflict'),
            session('error')
        );

        $this->get(route('wishlist.index'))
            ->assertOk()
            ->assertSee(__('ecommerce::ecommerce.cart.store_conflict'), false);
    }

    public function test_wishlist_page_renders_the_add_to_cart_interceptor(): void
    {
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->itemA->id,
            'merchant_id' => $this->merchantA->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('wishlist.index'));

        $response->assertOk();
        $response->assertSee('wishlist-add-cart-form', false);
        $response->assertSee('submitCartForm', false);
        $response->assertSee('Wishlist conflict item A');
    }

    public function test_wishlist_add_from_another_store_shows_readable_message(): void
    {
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->itemB->id,
            'merchant_id' => $this->merchantB->id,
        ]);

        $this->actingAs($this->user);

        $this->post(route('cart.add'), ['item_id' => $this->itemA->id, 'qty' => 1]);

        // The wishlist page is fetched again (normal flow after redirect/back)
        // and its script posts the same AJAX request the browser would send.
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])->post(route('cart.add'), ['item_id' => $this->itemB->id, 'qty' => 1]);

        $response->assertStatus(409);
        $response->assertJson(['conflict' => true, 'success' => false]);
        $this->assertNotEmpty($response->json('message'));
        $this->assertStringNotContainsString('{&quot;', $response->json('message'));
        $this->assertStringContainsString(
            __('ecommerce::ecommerce.cart.store_conflict'),
            $response->json('message')
        );
    }
}
