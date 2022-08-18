<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function getOrders()
    {
        $user_id = auth()->user()->id;
        $orders = Order::query()->whereCustomerId($user_id)->get();
        if($orders->count() > 0){
            return $this->mainResponse(true, 'success', $orders);
        }else{
            return $this->mainResponse(false, 'You don\'t have any orders yet.', null, ['Order' => 'You don\'t have any orders yet.'], 404);
        }
    }

    public function getOrderDetails(Request $request)
    {
        $rules = [
            'order_id' => 'required|numeric|exists:orders,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }
        $order = Order::query()->find($request->order_id);


        if ($order->customer_id != auth()->user()->id)
        {
            return $this->mainResponse(false, 'You don\'t have any order with this id!', null, ['order_id' => 'You don\'t have any order with this id!'], 404);
        }else{
            $data = $order;
            $orderProducts = OrderProduct::whereOrderId($order->id)->get();
            for ($i=0; $i < $orderProducts->count() ; $i++) {

                $product = Product::query()->find($orderProducts[$i]->product_id);
                $data['product_'.$product->id] =
                    [
                        'product_name'  => $product->name,
                        'product_cover' => $product->cover,
                        'product_quantity' => $orderProducts[$i]->quantity,
                        'product_price' => $orderProducts[$i]->price,
                    ];
            }
            return $this->mainResponse(true, 'success', $data);
        }
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
