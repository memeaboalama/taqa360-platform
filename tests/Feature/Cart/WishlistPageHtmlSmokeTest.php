<?php

namespace Tests\Feature\Cart;

use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\User\Models\User;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Tests\TestCase;

class WishlistPageHtmlSmokeTest extends TestCase
{
    public function test_wishlist_page_scripts_are_valid_javascript(): void
    {
        $user = User::factory()->create();
        $merchant = Merchant::create(['country_id' => 1, 'status' => 'active']);
        $item = Item::create([
            'title' => 'JS smoke item',
            'description' => 'JS smoke item',
            'merchant_id' => $merchant->id,
            'default_price' => 5,
            'stock' => 3,
            'status' => 'active',
        ]);
        Favorite::create(['user_id' => $user->id, 'item_id' => $item->id, 'merchant_id' => $merchant->id]);

        $html = $this->actingAs($user)->get(route('wishlist.index'))->assertOk()->getContent();

        preg_match_all('#<script(?![^>]*\bsrc=)[^>]*>(.*?)</script>#s', $html, $matches);
        $this->assertNotEmpty($matches[1], 'no inline scripts found');

        $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'wishlist_js_check';
        if (!is_dir($tmp)) {
            mkdir($tmp, 0777, true);
        }

        foreach ($matches[1] as $i => $js) {
            if (trim($js) === '') {
                continue;
            }
            $file = $tmp . DIRECTORY_SEPARATOR . 'block_' . $i . '.js';
            file_put_contents($file, $js);
            exec('node --check ' . escapeshellarg($file) . ' 2>&1', $out, $code);
            $this->assertSame(0, $code, 'script block #' . $i . " failed syntax check:\n" . implode("\n", $out));
        }

        // cleanup
        \DB::table('favorites')->where('user_id', $user->id)->delete();
        $user->forceDelete();
        \DB::table('items')->where('id', $item->id)->delete();
        \DB::table('merchants')->where('id', $merchant->id)->delete();
    }
}
