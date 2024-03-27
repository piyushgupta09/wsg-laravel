<?php

namespace Fpaipl\Shopy\Http\Coordinators;


use Illuminate\Http\Request;
use Fpaipl\Shopy\Models\Cart;
use Fpaipl\Prody\Models\Product;
use Illuminate\Support\Facades\DB;
use Fpaipl\Shopy\Http\Resources\CartResource;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Prody\Http\Resources\ProductResource;
use Fpaipl\Shopy\Http\Requests\AddToCartRequest;
use Fpaipl\Shopy\Http\Requests\CartUpdateRequest;

class CartCoordinator extends Coordinator
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->user()->id]);

        $cart = Cart::with([
            'cartProducts' => function ($query) {
                $query->/*where('draft', false)->*/with(
                    [
                        'cartItems.productOption',
                        'cartItems.productRange',
                        'product.productOptions',
                        'product.productRanges',
                    ]
                );
            },
        ])->find($cart->id);

        return new CartResource($cart);
    }

    /**
     * Adds or updates items in the cart based on the provided request. It ensures that
     * new items are added, existing items are updated with new quantities, and items
     * not included in the request are removed, reflecting the current user's intention.
     * This method also handles setting the total quantity for cart products and respects
     * the specified order type.
     *
     * @param AddToCartRequest $request The request containing the details for the cart update.
     * @param Cart $cart The user's cart to be updated.
     * @return \Illuminate\Http\JsonResponse Indicates the success or failure of the operation.
     */
    public function addToCart(AddToCartRequest $request, Cart $cart)
    {
        DB::beginTransaction();

        try {
            $product = Product::where('slug', $request->product_id)->firstOrFail();
            
            // Find or create the CartProduct with the provided order_type
            $cartProduct = $cart->cartProducts()->firstOrNew(
                ['product_id' => $product->id],
                ['order_type' => $request->order_type]
            );

            // Calculate total quantity for this product
            $totalQuantity = array_reduce($request->items, function ($carry, $item) {
                return $carry + $item['quantity'];
            }, 0);

            // Update CartProduct's quantity
            $cartProduct->quantity = $totalQuantity;
            $cartProduct->save();

            // Initialize an array to store the processed item IDs
            $processedItemIds = [];

            // Update or create CartItems for the CartProduct
            foreach ($request->items as $itemData) {
                $cartItem = $cartProduct->cartItems()->firstOrNew([
                    'product_option_id' => $itemData['option_id'],
                    'product_range_id' => $itemData['range_id'],
                ]);

                if ($itemData['quantity'] > 0) {
                    $cartItem->quantity = $itemData['quantity'];
                    $cartItem->save();
                    $processedItemIds[] = $cartItem->id;
                }
            }

            // Remove items not included in the update request
            $cartProduct->cartItems()->whereNotIn('id', $processedItemIds)->delete();

            // If no items left, delete the CartProduct itself
            if ($cartProduct->cartItems->isEmpty()) {
                $cartProduct->delete();
            }

            // find the user's checkout and remove coupon if applied
            $this->updateUserCheckout($cart);

            DB::commit();

            return response()->json(['success' => true, 'product' => new ProductResource($product)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    protected function updateUserCheckout(Cart $cart)
    {
        $checkout = auth()->user()->checkout;
        $checkout->coupon_id = null;
        $checkout->coupon_value = 0;
        $checkout->pay_mode = config('settings.default_pay_mode');
        $checkout->pay_amt = $cart->getTotal(config('settings.default_pay_mode'));
        $checkout->save();
    }

    public function saveForLater(CartUpdateRequest $request, Cart $cart)
    {
        $product = Product::where('slug', $request->product_id)->firstOrFail();
        $cartProduct = $cart->cartProducts()->where('product_id', $product->id)->first();
        $cartProduct->draft = true;
        $cartProduct->save();
        return json_encode(['success' => true]);
    }

    public function moveToCart(CartUpdateRequest $request, Cart $cart)
    {
        $product = Product::where('slug', $request->product_id)->firstOrFail();
        $cartProduct = $cart->cartProducts()->where('product_id', $product->id)->first();
        $cartProduct->draft = false;
        $cartProduct->save();
        return json_encode(['success' => true]);
    }

    public function removeFromCart(CartUpdateRequest $request, Cart $cart)
    {
        $product = Product::where('slug', $request->product_id)->firstOrFail();
        $status = $cart->cartProducts()->where('product_id', $product->id)->delete();
        if ($status) {
            return response()->json([
                'success' => true, 
                'product' => new ProductResource($product->refresh()) 
            ], 200);
        } else {
            return response()->json([
                'success' => false, 
                'message' => 'Product not found in cart'
            ], 422);
        }
    }
}
