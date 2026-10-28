<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\Ecommerce\Actions\Cart\DeleteItemCartAction;
use App\Domain\Ecommerce\Actions\Cart\FetchCartAction;
use App\Domain\Ecommerce\Actions\Cart\SaveItemCartAction;
use App\Domain\Ecommerce\Actions\Cart\UpdateQtyAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index(FetchCartAction $cartAction)
    {
        return $this->staticIndex($cartAction);
    }

    public function staticIndex(FetchCartAction $cartAction)
    {
        try {
            $cart = $cartAction->handle();

            return view('ecommerce::pages.cart.cart', compact('cart'));
        } catch (\Exception $e) {
            Log::error('Static cart page error: ' . $e->getMessage());

            return view('ecommerce::pages.cart.cart', ['cart' => null]);
        }
    }

    public function addItem(Request $request, SaveItemCartAction $saveItemCartAction, FetchCartAction $cartAction)
    {
        try {
            $userId = auth()->id() ?? auth()->guard('user-api')->id();
            if (!$userId) {
                if ($request->hasHeader('X-Up-Target') || $request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'login_required' => true,
                        'message' => __('ecommerce::ecommerce.login_to_add_cart')
                    ], 401);
                }

                return redirect()->route('auth.login')->with('error', __('ecommerce::ecommerce.login_to_add_cart'));
            }

            $validated = $request->validate([
                'item_id' => ['required', 'exists:items,id'],
                'qty' => ['required', 'numeric', 'min:1'],
            ]);

            $result = $saveItemCartAction->addItem($validated);

            if (is_array($result) && ($result["code"] ?? null) == 409) {
                return $this->storeConflictResponse($request, $result, $validated, $cartAction);
            }

            if (isset($result["code"]) && $result["code"] == 400) {
                if ($request->hasHeader('X-Up-Target')) {
                    return response('<script>showNotification("' . $result["message"] . '", "danger");</script>');
                }
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $result["message"]], 400);
                }
                return redirect()->back()->with('error', $result["message"]);
            }

            if ($request->input('redirect_to_checkout')) {
                return redirect()->route('checkout');
            }

            if ($request->hasHeader('X-Up-Target')) {
                $cart = $cartAction->handle();
                $html = view('ecommerce::pages.cart.partials.cart-header', compact('cart'))->render();

                $notificationData = json_encode(['type' => 'success', 'message' => __('ecommerce::ecommerce.cart.item_added_success')], JSON_UNESCAPED_UNICODE);
                $notification = base64_encode($notificationData);

                return response($html)
                    ->header('X-App-Notification', $notification);
            }

            if ($request->ajax() || $request->wantsJson()) {
                $cart = $cartAction->handle();
                return response()->json([
                    'success' => true,
                    'message' => __('ecommerce::ecommerce.cart.item_added_success'),
                    'cart_count' => $cart?->cartItems?->sum('qty') ?? 0,
                    'cart_total' => number_format((float) ($cart?->total ?? 0), 2),
                    'cart_empty' => !$cart || $cart->cartItems->isEmpty(),
                ]);
            }

            return redirect()->back()->with('success', __('ecommerce::ecommerce.cart.item_added_success'));
        } catch (\Exception $e) {
            Log::error('Add to cart failed: ' . $e->getMessage());
            if ($request->hasHeader('X-Up-Target')) {
                return response('<script>showNotification(' . json_encode(__('ecommerce::ecommerce.cart.add_error'), JSON_UNESCAPED_UNICODE) . ', "danger");</script>', 500);
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('ecommerce::ecommerce.cart.add_error')], 500);
            }
            return redirect()->back()->with('error', __('ecommerce::ecommerce.cart.add_error'));
        }
    }

    public function removeItem(Request $request, DeleteItemCartAction $deleteItemCartAction, FetchCartAction $cartAction)
    {
        try {
            $validated = $request->validate([
                'item_id' => ['required', 'exists:items,id'],
            ]);

            $deleteItemCartAction->removeV2($validated);

            if ($request->ajax() || $request->wantsJson()) {
                $cart = $cartAction->handle();
                return response()->json([
                    'success' => true,
                    'message' => __('ecommerce::ecommerce.cart.item_removed_success'),
                    'cart_count' => $cart?->cartItems?->sum('qty') ?? 0,
                    'cart_total' => number_format((float) ($cart?->total ?? 0), 2),
                    'cart_empty' => !$cart || $cart->cartItems->isEmpty(),
                ]);
            }

            return redirect()->back()->with('success', __('ecommerce::ecommerce.cart.item_removed_success'));
        } catch (\Exception $e) {
            Log::error('Remove item error: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('ecommerce::ecommerce.unexpected_error')], 500);
            }
            return back()->with('error', __('ecommerce::ecommerce.unexpected_error'));
        }
    }



    public function clearItems(Request $request, DeleteItemCartAction $deleteItemCartAction, FetchCartAction $cartAction)
    {
        try {
            $deleteItemCartAction->clear();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('ecommerce::ecommerce.cart.cart_cleared_success'),
                    'cart_empty' => true,
                    'cart_count' => 0,
                    'cart_total' => '0.00',
                ]);
            }
            return redirect()->back()->with('success', __('ecommerce::ecommerce.cart.cart_cleared_success'));
        } catch (\Exception $e) {
            Log::error('Clear cart failed: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('ecommerce::ecommerce.cart.clear_error')], 500);
            }
            return redirect()->back()->with('error', __('ecommerce::ecommerce.cart.clear_error'));
        }
    }

    public function updateQty(Request $request, UpdateQtyAction $updateQtyAction, DeleteItemCartAction $deleteItemCartAction, FetchCartAction $cartAction)
    {
        try {
            $validated = $request->validate([
                'item_id' => ['required', 'exists:items,id'],
                'qty' => ['required', 'numeric', 'min:0'],
            ]);

        if ($validated['qty'] == 0) {
            $deleteItemCartAction->removeV2(['item_id' => $validated['item_id']]);
            $message = __('ecommerce::ecommerce.cart.item_removed_success');
        } else {
            $updateResult = $updateQtyAction->handle($validated);

            if (is_array($updateResult) && ($updateResult['code'] ?? null) === 400) {
                if ($request->ajax() || $request->wantsJson()) {
                    $cart = $cartAction->handle();
                    return response()->json([
                        'success' => false,
                        'message' => $updateResult['message'],
                        'cart_count' => $cart?->cartItems?->sum('qty') ?? 0,
                        'cart_total' => number_format((float) ($cart?->total ?? 0), 2),
                    ], 400);
                }

                return redirect()->back()->with('error', $updateResult['message']);
            }

            $message = __('ecommerce::ecommerce.cart.quantity_updated_success');
        }

            if ($request->ajax() || $request->wantsJson()) {
                $cart = $cartAction->handle();
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => $cart?->cartItems?->sum('qty') ?? 0,
                    'cart_total' => number_format((float) ($cart?->total ?? 0), 2),
                    'cart_empty' => !$cart || $cart->cartItems->isEmpty(),
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Update quantity failed: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('ecommerce::ecommerce.cart.update_error')], 500);
            }
            return redirect()->back()->with('error', __('ecommerce::ecommerce.cart.update_error'));
        }
    }

    public function getCartHeader(FetchCartAction $cartAction)
    {
        try {
            $cart = $cartAction->handle();
            return view('ecommerce::pages.cart.partials.cart-header', compact('cart'));
        } catch (\Exception $e) {
            return response('<div class="cart-header">Cart</div>');
        }
    }

    public function getCartSidebar(FetchCartAction $cartAction)
    {
        try {
            $cart = $cartAction->handle();
            $cartItems = $cart?->cartItems ?? collect();
            $cartCount = (int) $cartItems->sum('qty');
            $cartTotal = number_format((float) ($cart?->total ?? 0), 2);

            $items = $cartItems->map(function ($cartItem) {
                $fallbackTitle = __('ecommerce::ecommerce.product_label');
                $item = $cartItem->item;
                $title = $item?->title ?? $fallbackTitle;
                if (is_array($title)) {
                    $title = $title[app()->getLocale()] ?? $title['en'] ?? reset($title) ?? $fallbackTitle;
                }
                if (is_object($title)) {
                    $title = $fallbackTitle;
                }
                $image = !empty($item?->image_path) ? $item->image_path : asset('assets/ecommerce/images/Product/1.jpg');

return [
            'item_id' => (int) $cartItem->item_id,
            'title' => $title,
            'image' => $image,
            'price' => (float) ($item?->default_price ?? 0),
            'qty' => (int) $cartItem->qty,
            'subtotal' => (float) ($cartItem->subtotal ?? 0),
            'available' => $item ? $item->isInStock() : false,
            'url' => route('product.show', $cartItem->item_id),
        ];
            })->values();

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'items_count' => $cartItems->count(),
                'cart_total' => $cartTotal,
                'cart_empty' => !$cart || $cartItems->isEmpty(),
                'items' => $items,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'cart_count' => 0,
                'items_count' => 0,
                'cart_total' => '0.00',
                'cart_empty' => true,
                'items' => [],
            ]);
        }
    }

    public function switchToStore(Request $request, SaveItemCartAction $saveItemCartAction, DeleteItemCartAction $deleteItemCartAction, FetchCartAction $cartAction)
    {
        try {
            $validated = $request->validate([
                'item_id' => ['required', 'exists:items,id'],
                'qty' => ['required', 'numeric', 'min:1'],
            ]);

            $switchItem = \Domain\MarketPlace\Models\Item::query()->where('id', $validated['item_id'])->first();
            if (!$switchItem || !$switchItem->isInStock()) {
                $message = __('ecommerce::ecommerce.product_out_of_stock');
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return back()->with('error', $message);
            }

            if ($switchItem->stock !== null && (int) $validated['qty'] > (int) $switchItem->stock) {
                $message = __('ecommerce::ecommerce.remaining_stock', ['count' => (int) $switchItem->stock]);
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return back()->with('error', $message);
            }

            $deleteItemCartAction->clearV2();

            $saveItemCartAction->addItem($validated);

            if ($request->ajax() || $request->wantsJson()) {
                $cart = $cartAction->handle();
                return response()->json([
                    'success' => true,
                    'message' => __('ecommerce::ecommerce.cart_switched_success'),
                    'cart_count' => $cart?->cartItems?->sum('qty') ?? 0,
                    'cart_total' => number_format((float) ($cart?->total ?? 0), 2),
                    'cart_empty' => !$cart || $cart->cartItems->isEmpty(),
                ]);
            }

            $notificationData = json_encode(['type' => 'success', 'message' => __('ecommerce::ecommerce.cart.item_added_success')], JSON_UNESCAPED_UNICODE);
            $notification = base64_encode($notificationData);

            if ($request->boolean('redirect_to_checkout')) {
                return redirect()->route('checkout')->header('X-App-Notification', $notification);
            }

            return back()->header('X-App-Notification', $notification)->with('success', __('ecommerce::ecommerce.cart_switched_success'));
        } catch (\Exception $e) {
            Log::error('Switch store failed: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('ecommerce::ecommerce.cart.add_error')], 500);
            }
            return back()->with('error', __('ecommerce::ecommerce.cart.add_error'));
        }
    }

    private function storeConflictResponse(Request $request, array $result, array $validated, FetchCartAction $cartAction)
    {
        if ($request->hasHeader('X-Up-Target')) {
            $pending = [
                'item_id' => $validated['item_id'],
                'qty' => $validated['qty'],
                'redirect_to_checkout' => (int) $request->boolean('redirect_to_checkout'),
            ];

            $script = '<script>
                window.__pendingCartAdd = ' . json_encode($pending, JSON_UNESCAPED_UNICODE) . ';
                showConfirmationModal(
                    ' . json_encode(route('cart.switch-to-store'), JSON_UNESCAPED_SLASHES) . ',
                    ' . json_encode($result['message'], JSON_UNESCAPED_UNICODE) . ',
                    ' . json_encode(__('ecommerce::ecommerce.cart.store_conflict_title'), JSON_UNESCAPED_UNICODE) . ',
                    ' . json_encode(__('ecommerce::ecommerce.cart.empty_and_continue'), JSON_UNESCAPED_UNICODE) . ',
                    "POST",
                    window.__pendingCartAdd
                );
            </script>';

            $target = $request->header('X-Up-Target');

            $modalPayload = json_encode([
                'url' => route('cart.switch-to-store'),
                'message' => $result['message'],
                'title' => __('ecommerce::ecommerce.cart.store_conflict_title'),
                'buttonText' => __('ecommerce::ecommerce.cart.empty_and_continue'),
                'method' => 'POST',
                'extraFields' => $pending,
            ], JSON_UNESCAPED_SLASHES);

            try {
                $cart = $cartAction->handle();

                // "Add to cart" flow targets the cart dropdown (.cart-header):
                // update the dropdown and show the modal.
                if ($target && str_contains($target, '.cart-header')) {
                    return response(
                        view('ecommerce::pages.cart.partials.cart-header', compact('cart') + ['conflictScript' => $script])->render()
                    );
                }

                // Other flows (e.g. "Buy Now" targeting .main-body): show the modal
                // without replacing the page content with the cart dropdown HTML.
                return response()->noContent()
                    ->header('X-App-Conflict-Modal', base64_encode($modalPayload));
            } catch (\Exception $e) {
                Log::error('Conflict response cart error: ' . $e->getMessage());

                return response()->noContent()
                    ->header('X-App-Conflict-Modal', base64_encode($modalPayload));
            }
        }

        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->back()->with('error', $result['message']);
        }

        return response()->json([
            'success' => false,
            'conflict' => true,
            'message' => $result['message'],
            'item_id' => $validated['item_id'],
            'qty' => $validated['qty'],
        ], 409);
    }

    private function handleJsonResponse(FetchCartAction $cartAction, $type, $message)
    {
        try {
            $cart = $cartAction->handle();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => $type !== 'error',
                    'message' => $message,
                    'cart_count' => $cart?->cartItems?->sum('qty') ?? 0
                ]);
            }

            return response()->view('ecommerce::pages.cart.cart', compact('cart'));
        } catch (\Exception $e) {
            Log::error('Handle response error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $message
            ], 500);
        }
    }
}
