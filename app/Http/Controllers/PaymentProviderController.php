<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentProviderController extends Controller
{
    public function showForm(Request $request)
    {
        $order = Order::query()->whereOrderToken($request->token)->first();
        return view('payment.payment', compact('order'));

    }

    public function getCartCheckout(Request $request)
    {
        // return $request->all();
        $url = "https://eu-test.oppwa.com/v1/checkouts";
        $data = "entityId=8a8294174b7ecb28014b9699220015ca" .
        "&amount=".$request->total .
        "&currency=EUR" .
        "&paymentType=DB";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        $res = json_decode($responseData, true);
        $view = view('payment.paymentForm')->with(['responseData' => $res, 'order_token' => $request->order_token])
        ->renderSections();

        return response()->json([
            'status' => true,
            'content' => $view['main']
        ]);
    }
}
