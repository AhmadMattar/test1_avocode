<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\ProductCoupoun;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\returnSelf;

class OrderController extends Controller
{
    public function index()
    {
        return view('Backend.orders.index');
    }

    public function indexTable()
    {
        return DataTables::of(Order::query())
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="items_checkbox[]" class="items_checkbox" value="' . $row->id . '" />';
            })
            ->addColumn('name', function ($row) {
                return $row->customer->first_name . ' ' . $row->customer->last_name;
            })
            ->addColumn('coupoun', function ($row) {
                $coupoun = ProductCoupoun::query()->find($row->product_coupoun_id);
                return $coupoun  ? $coupoun->code : '---';
            })
            ->addColumn('type', function ($row) {
                if ($row->coupoun_type == null) {
                    return '---';
                } else {
                    return $row->coupoun_type == 'fixed' ? __('general.fixed') : __('general.percentage');
                }
            })
            ->addColumn('value', function ($row) {
                if ($row->coupoun_type == null) {
                    return 0;
                } else {
                    return $row->coupoun_type == 'fixed' ? $row->coupoun_value . '$' : $row->coupoun_value . '%';
                }
            })
            ->addColumn('payment_status', function($row){
                if($row->payment_status != null) {
                    return '<span class="text-success">'. __('general.complete') .'</span>';
                }else{
                    return '<span class="text-danger">'. __('general.not_complete') .'</span>';
                }
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $coupoun = ProductCoupoun::query()->find($row->product_coupoun_id);
                $productIds = OrderProduct::query()->whereOrderId($row->id)->get('product_id');
                $products = Product::query()->whereIn('id', $productIds)->get();
                $productNames = implode(',',  $products->pluck('name')->toArray()) . ",";
                $productCovers = implode(',',  $products->pluck('cover')->toArray()) . ",";
                $productPrices = implode(',',  OrderProduct::whereOrderId($row->id)->pluck('price')->toArray()) . ",";
                $productQuantities = implode(',',  OrderProduct::whereOrderId($row->id)->pluck('quantity')->toArray()) . ",";

                $actionBtn = '';
                if (Auth::user()->can('delete_order') || Auth::user()->can('admin_permission')) {
                    $actionBtn .= '
                            </button>
                            <a id="deleteBtn" data-id="' . $row->id . '" class="deleteOrder btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
                }
                $actionBtn .= ' <button
                                class="showOrder btn btn-info btn-sm" id="showBtn"
                                data-id="' . $row->id . '"
                                data-name="' . $row->customer->first_name . ' ' . $row->customer->last_name . '" ';

                if ($coupoun) {
                    $type = $row->coupoun_type == 'fixed' ? __('general.fixed') : __('general.percentage');
                    if ($row->coupoun_type == 'fixed') {
                        $actionBtn .= '
                                    data-coupoun_code="' . $coupoun->code . '"
                                    data-coupoun_type="' . $type . '"
                                    data-coupoun_value="' . $row->coupoun_value . '$' . '" ';
                    } else {
                        $actionBtn .= '
                                    data-coupoun_code="' . $coupoun->code . '"
                                    data-coupoun_type="' . $type . '"
                                    data-coupoun_value="' . $row->coupoun_value . '%' . '" ';
                            }
                } else {
                    $actionBtn .= '
                                data-coupoun_code="' . __('general.not_exist') . '"
                                data-coupoun_type="--"
                                data-coupoun_value="0" ';
                }

                $actionBtn .= '
                                data-created_at="' . $row->created_at . '"
                                data-payment_status="' . $row->payment_status . '"
                                data-product_prices="' . $productPrices . '"
                                data-product_quantities="' . $productQuantities . '"
                                data-product_names="' . $productNames . '"
                                data-product_covers="' . $productCovers . '"
                                data-total="' . $row->total . '$' . '" ';

                $actionBtn .= ' type="button" data-toggle="modal" data-target="#showModal"><i class="fa fa-eye"></i></button>';
                return $actionBtn;
            })
            ->rawColumns(['checkbox', 'payment_status', 'action'])->make(true);
    }
    public function destroy(Request $request)
    {
        $productIds = OrderProduct::query()->whereOrderId($request->id)->get('product_id');
        $order_products = OrderProduct::query()->whereOrderId($request->id)->get();
        $order = Order::query()->find($request->id);
        $products = Product::query()->whereIn('id', $productIds)->get();

        foreach ($order_products as $products) {
            $product = Product::query()->find($products->product_id);
            $product->update([
                'quantity' => $product->quantity + $products->quantity,
            ]);
            DB::table('order_product')->where('order_id', $request->id)->delete();
        }

        $order->delete();
        return response()->json([$order, 'message' => __('general.delete_successfully')]);
    }

    public function updatePaymentStatus($order_token)
    {
        $order = Order::query()->whereOrderToken($order_token)->first();

        if (request()->has('id') && request()->has('resourcePath')) {
            $payment_status = $this->getPaymentStatus(request()->id, request()->resourcePath);
            if (isset($payment_status['id'])) {
                $showSuccessPaymentMessage = true;
                $order->update([
                    'payment_status'    => $payment_status['id'],
                ]);
                return view('payment.payment', compact('order'))->with(['success' =>  'تم الدفغ بنجاح']);
            } else {
                $showFailPaymentMessage = true;
                return view('payment.payment', compact('order'))->with(['fail'  => 'فشلت عملية الدفع']);
            }
        }
        return view('payment.payment', compact('order'));
    }

    private function getPaymentStatus($id, $resourcepath)
    {
        $url = "https://eu-test.oppwa.com/v1/checkouts/".$id."/payment";
        $url .= "?entityId=8a8294174b7ecb28014b9699220015ca";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return json_decode($responseData, true);
    }
}
