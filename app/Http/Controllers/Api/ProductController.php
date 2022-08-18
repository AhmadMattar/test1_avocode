<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductCoupoun;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('quantity', '>', 0)->get();
        return $this->mainResponse(true, 'success', $products);
    }

    public function addToCart(Request $request)
    {
        $user_id = auth()->user()->id;

        $rules = [
            'product_id'    => 'required|numeric|exists:products,id',
            'quantity'      => 'required|numeric|min:1|max:10',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        $product = Product::query()->find($request->product_id);
        if ($request->quantity > $product->quantity)
        {
            return $this->mainResponse(false, 'Available '.$product->quantity.' items', null, ['quantity' => 'Available '.$product->quantity.' items'], 422);
        }

        $cart = Cart::whereProductId($request->product_id)->first();

        //if the product was exists in cart table
        if($cart != null)
        {
            $user = Customer::query()->find($user_id);

            $cart->update([
                'quantity' => $cart->quantity + $request->quantity,
            ]);

            $product->update([
               'quantity' => $product->quantity - $request->quantity,
            ]);

            $data = $cart;
            $data['amount'] = $user->carts->sum('price');

            return $this->mainResponse(true, 'success', $data);
        }else {

            $user = Customer::query()->find($user_id);

            $new_cart = Cart::create([
                'user_id'       => $user_id,
                'product_id'    => $request->product_id,
                'quantity'      => $request->quantity,
            ]);

            $new_product_quantity = $product->quantity - $request->quantity;

            $product->update([
                'quantity' => $new_product_quantity,
            ]);

            $data = $new_cart;
            $data['amount'] = $request->quantity * $product->price;

            return $this->mainResponse(true, 'success', $new_cart);
        }

    }

    public function getCart()
    {
        $user_id = auth()->user()->id;

        $cart = Cart::whereUserId($user_id)->get();
        $user = Customer::query()->find($user_id);

        $data = $cart;
        $data['total'] = $user->carts->sum('price');
        return $this->mainResponse(true, 'success', $data);
    }

    public function updateCart(Request $request)
    {
        $user_id = auth()->user()->id;
        $rules = [
            'product_id'    => 'required|numeric|exists:products,id',
            'quantity'      => 'required|numeric|min:1|max:10',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        $product = Product::query()->find($request->product_id);
        $cart = Cart::whereProductId($request->product_id)->first();
        $user = Customer::query()->find($user_id);

        if($cart == null)
        {
            return $this->mainResponse(false, 'This product does not found inside this cart', null, ['product_id' => 'This product does not found inside this cart'], 422);
        }

        if ($request->quantity > $product->quantity)
        {
            return $this->mainResponse(false, 'Available '.$product->quantity.' items', null, ['quantity' => 'Available '.$product->quantity.' items'], 422);
        }

        $old_cart_quantity = $cart->quantity;

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        //update product quantity
        if ($old_cart_quantity > $cart->quantity)
        {
            $product->update([
                'quantity' => $product->quantity + ($old_cart_quantity - $cart->quantity),
            ]);
        }elseif($old_cart_quantity < $cart->quantity){
            $product->update([
                'quantity' => $product->quantity - ($cart->quantity - $old_cart_quantity),
            ]);
        }else{
            $product->update([
                'quantity' => $product->quantity,
            ]);
        }

        $data = $cart;
        $data['total'] = $user->carts->sum('price');

        return $this->mainResponse(true, 'success', $data);

    }

    public function deleteProductFromCart(Request $request)
    {
        $rules = [
            'cart_id'   => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        $cart = Cart::query()->find($request->cart_id);
        $product = Product::query()->find($cart->product_id);
        $product->update([
            'quantity' => $product->quantity + $cart->quantity
        ]);
        $cart->delete();

        return $this->mainResponse(true, 'success', $cart);
    }

    public function clearCart()
    {
        $user = Customer::query()->find(auth()->user()->id);
        foreach ($user->carts as $cart) {
            $product = Product::query()->find($cart->product_id);
            $product->update([
                'quantity' => $product->quantity + $cart->quantity
            ]);
            $cart->delete();
        }
        return $this->mainResponse(true, 'success', $user->carts);
    }

    public function checkout(Request $request)
    {
        // return $request->all();
        $rules = [
            'coupoun' => 'nullable|exists:product_coupouns,code',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        $carts = Cart::whereUserId(auth()->user()->id)->get();

        if($carts->count() == 0)
        {
            return $this->mainResponse(false, 'Yours cart is empty', null, ['cart' => 'Yours cart is empty'], 422);
        }
        
        $user = Customer::query()->find(auth()->user()->id);

        $order = Order::create([
            'customer_id'   => auth()->user()->id,
            'total'         => $user->carts->sum('price'),
        ]);

        if($request->has('coupoun'))
        {
            $coupoun = ProductCoupoun::query()->whereCode($request->coupoun)->first();
            if($coupoun->type == 'fixed')
            {
                if ($order->total > $coupoun->value)
                {
                    $discount = $order->total - $coupoun->value;
                    $order->update([
                        'total' => $discount,
                        'product_coupoun_id' => $coupoun->id,
                        'coupoun_type' => $coupoun->type,
                        'coupoun_value' => $coupoun->value,
                    ]);
                }else{
                    return $this->mainResponse(false, 'Your coupoun value is bigger than your order value', null, ['coupoun' => 'Your coupoun value is bigger than your order value'], 422);
                }
            }else{
                $discount = $order->total - ($coupoun->value / 100 * $order->total);
                $order->update([
                    'total' => $discount,
                    'product_coupoun_id' => $coupoun->id,
                    'coupoun_type' => $coupoun->type,
                    'coupoun_value' => $coupoun->value,
                ]);
            }
        }

        foreach ($user->carts as $cart) {
            $product = Product::query()->find($cart->product_id);
            $order->products()->attach($cart->product_id, [
                'quantity' => $cart->quantity,
                'price'     => $product->price,
            ]);
        }

        foreach ($carts as $cart) {
            $cart->delete();
        }

        $data = $order;
        $data['products'] = $order->products;
        return $this->mainResponse(true, 'success', $data);
    }

    public function mainResponse($status, $message, $data, $error = [], $code = 200)
    {
        $errors = [];
        foreach($error as $key => $value)
        {
            $errors[] = ['filed_name' => $key, 'message' => $value];
        }
        return response()->json(compact('code', 'status', 'message', 'data', 'errors'), $code);
    }
}
