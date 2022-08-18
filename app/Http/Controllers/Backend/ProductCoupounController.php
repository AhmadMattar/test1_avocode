<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\ProductCoupoun;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductCoupounController extends Controller
{
    public function index()
    {
        return view('Backend.coupouns.index');
    }

    public function indexTable()
    {
        return DataTables::of(ProductCoupoun::query())
        ->filter(function ($query) {
            if (request()->has('code')){
                $query->where('code', 'like', "%" . request()->code . "%");
            }
            if (request()->has('status')) {
                $query->when(request()->status != null, function ($query) {
                    $query->whereStatus(request()->status);
                });
            }
        })
        ->addColumn('checkbox', function($row){
            return '<input type="checkbox" name="items_checkbox[]" class="items_checkbox" value="'.$row->id.'" />';
        })
        ->addColumn('status', function ($row) {
            return $row->status ? __('general.Activated') : __('general.Inactivated');
        })
        ->addColumn('type', function ($row) {
            return $row->type == 'fixed' ? __('general.fixed') : __('general.percentage');
        })
        ->addColumn('value', function ($row) {
            return $row->type == 'fixed' ? $row->value.'$' : $row->value.'%' ;
        })
        ->addColumn('use_times', function ($row) {
            $orders = Order::query()->whereProductCoupounId($row->id)->get();
            return $orders->count() ;
        })
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $actionBtn = '<button
                            class="editCoupoun btn btn-success btn-sm"
                            id="editBtn"
                            type="button"
                            data-id="'.$row->id.'"
                            data-code="'.$row->code.'"
                            data-type="'.$row->type.'"
                            data-value="'.$row->value.'"
                            data-status="'.$row->status.'" ';
            if (Auth::user()->can('edit_coupoun') || Auth::user()->can('admin_permission')) {
                $actionBtn .= '>
                            <i class="fa fa-edit"></i>';
            }
            if (Auth::user()->can('delete_coupoun') || Auth::user()->can('admin_permission')) {
                $actionBtn .= '
                            </button>
                            <a id="deleteBtn" data-id="' . $row->id . '" class="deleteCoupoun btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
            }
            return $actionBtn;
        })
        ->rawColumns(['checkbox', 'action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'code'      => 'required',
            'type'      => 'required',
            'value'     => 'required|numeric',
            'status'    => 'required',
        ];

        $request->validate($rules);

        $data['code'] = $request->code;
        $data['type'] = $request->type;
        $data['value'] = $request->value;
        $data['status'] = $request->status;

        $coupoun = ProductCoupoun::create($data);
        return response()->json([$coupoun, 'message' => __('general.add_successfully')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $coupoun = ProductCoupoun::find($request->id);
        $rules = [
            'code'      => 'required',
            'type'      => 'required',
            'value'     => 'required|numeric',
            'status'    => 'required',
        ];

        $request->validate($rules);

        $data['code'] = $request->code;
        $data['type'] = $request->type;
        $data['value'] = $request->value;
        $data['status'] = $request->status;

        $coupoun->update($data);

        return response()->json([$coupoun, 'message' => __('general.updated_successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $coupoun = ProductCoupoun::find($request->id);
        $coupoun->delete();
        return response()->json([$coupoun, 'message' => __('general.delete_successfully')]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->id;
        $coupouns = ProductCoupoun::whereIn('id', $ids);
        $coupouns->delete();
        return response()->json([$coupouns, 'message' => __('general.delete_successfully')]);
    }

    public function activeAll(Request $request)
    {
        $ids = $request->id;
        $coupouns = ProductCoupoun::whereIn('id', $ids);
        $coupouns->update([
            'status' => 1,
        ]);
        return response()->json([$coupouns, 'message' => __('general.active_successfully')]);
    }

    public function disactiveAll(Request $request)
    {
        $ids = $request->id;
        $coupouns = ProductCoupoun::whereIn('id', $ids);
        $coupouns->update([
            'status' => 0,
        ]);
        return response()->json([$coupouns, 'message' => __('general.disactive_successfully')]);
    }
}
