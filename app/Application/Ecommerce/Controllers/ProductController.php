<?php

namespace App\Application\Ecommerce\Controllers;

use App\Application\Ecommerce\Requests\FetchProductRequest;
use App\Domain\Ecommerce\Resources\Product\ProductResource;
use App\Domain\MarketPlace\Actions\Api\Item\FetchItemsAction;
use App\Domain\MarketPlace\Actions\Api\Item\FetchSingleItemAction;
use App\Domain\MarketPlace\Repositories\Catalog\CategoryRepository;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Domain\MarketPlace\Models\Favorite;
use Domain\MarketPlace\Models\Item;

class ProductController extends Controller
{
    use ApiResponseTraitV2;

    public function __construct(
        protected ItemRepository $itemRepository,
        protected CategoryRepository $categoryRepository,
        protected FetchItemsAction $fetchItemsAction,
        protected FetchSingleItemAction $fetchSingleItemAction
    ){}

    public function fetch(FetchProductRequest $request, FetchItemsAction $fetchItemsAction): JsonResponse
    {
        try {
            $data = $fetchItemsAction->handle($request->validated());

            if (isset($data["code"]) && $data["code"] == 400) {
                return $this->errorResponse(400, $data["message"]);
            }

            if (!$data) {
                return $this->errorResponse(400, 'Oops! provide any address');
            }

            return $this->responseData([
                'products' => ProductResource::collection($data)
            ]);
        } catch (\Exception $e) {
            Log::error('Product fetch error: ' . $e->getMessage());
            return $this->errorResponse(500, 'Failed to fetch products');
        }
    }

    public function show(Request $request, $item_id)
    {
        try {
            $product = $this->getProductWithDetails($item_id);

            if (!$product) {
                return $this->handleProductNotFound($request);
            }

            $relatedProducts = $this->getRelatedProducts($product);
            $relatedCategories = $this->getRelatedCategories($product);

            $view = 'ecommerce::pages.product.product-details';

            if ($this->isUnpolyRequest($request)) {
                $target = $request->header('X-Up-Target');
                if ($target && $target !== '.main-body') {
                    return $this->handleUnpolyProductRequest($request, $product, $relatedProducts, $relatedCategories);
                }
            }

            return view($view, compact('product', 'relatedCategories', 'relatedProducts'));
        } catch (\Exception $e) {
            Log::error('Product show error: ' . $e->getMessage());
            return $this->handleError($request, 'Failed to load product details');
        }
    }

    public function showItemsByCategory(int $category_id, Request $request, FetchItemsAction $fetchItemsAction): JsonResponse
    {
        try {
            $items = $fetchItemsAction->getByCategory($category_id, $request->all());

            if (!$items) {
                return $this->errorResponse(400, 'Data not found');
            }

            return $this->responseData([
                'items' => ProductResource::collection($items)
            ]);
        } catch (\Exception $e) {
            Log::error('Category products error: ' . $e->getMessage());
            return $this->errorResponse(500, 'Failed to load category products');
        }
    }

    public function quickView(Request $request, $id)
    {
        try {
            $product = $this->getProductWithDetails($id);

            if (!$product) {
                return response('<div class="alert alert-warning">Product not found</div>');
            }

            return view('ecommerce::partials.quick-view-content', [
                'product' => $product
            ]);
        } catch (\Exception $e) {
            Log::error('Quick view error: ' . $e->getMessage());
            return response('<div class="alert alert-danger">Failed to load product details</div>');
        }
    }

    private function getProductWithDetails($itemId)
    {
        return Cache::remember("product_details_{$itemId}", 3600, function () use ($itemId) {
            $product = $this->itemRepository->find(['*'], ['id' => $itemId, 'status' => 'active']);

            if ($product && method_exists($product, 'images')) {
                $product->images;
            }

            return $product;
        });
    }

    private function getRelatedProducts($product)
    {
        return Cache::remember("related_products_{$product->id}", 1800, function () use ($product) {
            return $this->itemRepository->builder(['*'], [], ['main_category_id' => $product->main_category_id])->limit(5)->get();
        });
    }

    private function getRelatedCategories($product)
    {
        return Cache::remember("related_categories_{$product->main_category_id}", 3600, function () use ($product) {
            return $this->categoryRepository->all(['*'], [], ['id' => $product->main_category_id, 'is_active' => 1]);
        });
    }

    private function isUnpolyRequest(Request $request): bool
    {
        return $request->hasHeader('X-Up-Target');
    }

    private function handleUnpolyProductRequest(Request $request, $product, $relatedProducts, $relatedCategories)
    {
        $target = $request->header('X-Up-Target');

        switch ($target) {
            case '#quick-view-content':
                return view('ecommerce::partials.quick-view-content', [
                    'product' => $product
                ]);

            case '#product-images':
                return view('ecommerce::partials.product-images', [
                    'product' => $product
                ]);

            case '#related-products':
                return view('ecommerce::partials.related-products', [
                    'relatedProducts' => $relatedProducts
                ]);

            default:
                return view('ecommerce::pages.product.product-details', compact('product', 'relatedCategories', 'relatedProducts'));
        }
    }

    private function handleProductNotFound(Request $request)
    {
        if ($this->isUnpolyRequest($request)) {
            return response('<div class="alert alert-danger text-center">Product not found</div>');
        }

        abort(404, 'Product not found');
    }

    private function handleError(Request $request, $message)
    {
        if ($this->isUnpolyRequest($request)) {
            return response('<div class="alert alert-danger">' . $message . '</div>');
        }

        return back()->with('error', $message);
    }
    public function addToWishlist(Request $request)
    {
        if (!Auth::check()) {
             if ($this->isUnpolyRequest($request)) {
                 return response('<script>$("#loginRequiredModal").modal("show");</script>');
             }
             if ($request->ajax()) {
                 return response()->json(['login' => true, 'redirect' => route('auth.login', ['redirect' => url()->current()])], 401);
             }
             return redirect()->route('auth.login');
        }

        $request->validate([
            'product_id' => 'required|exists:items,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $favorite = Favorite::where('user_id', $user->id)
            ->where('item_id', $productId)
            ->first();

        $status = 'added';
        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'item_id' => $productId
            ]);
        }

        if ($this->isUnpolyRequest($request)) {
            if ($request->input('from_page') === 'wishlist') {
                 return redirect()->route('wishlist.index');
            }

            $count = Favorite::where('user_id', $user->id)->count();
            $message = $status === 'added' ? __('ecommerce::ecommerce.product_added_to_wishlist') : __('ecommerce::ecommerce.product_removed_from_wishlist');


            $countHtml = '<span class="badge badge-warning" id="wishlist-count">' . $count . '</span>';

            $product = Item::find($productId);
            $style = $request->input('style', 'link');
            $displayText = $request->input('displayText', false);

            $buttonHtml = view('ecommerce::partials.product.wishlist-btn', [
                'product' => $product,
                'style' => $style,
                'displayText' => filter_var($displayText, FILTER_VALIDATE_BOOLEAN)
            ])->render();

            $response = response($countHtml . $buttonHtml);

            $notificationData = json_encode(['type' => 'success', 'message' => $message]);
            $notification = base64_encode($notificationData);
            $response->header('X-App-Notification', $notification);

            return $response;
        }

        if ($request->ajax()) {
            $count = Favorite::where('user_id', $user->id)->count();
            $message = $status === 'added' ? __('ecommerce::ecommerce.product_added_to_wishlist') : __('ecommerce::ecommerce.product_removed_from_wishlist');

            return response()->json([
                'success' => true,
                'status'  => $status,
                'count'   => $count,
                'message' => $message
            ]);
        }

        return back();
    }
}
