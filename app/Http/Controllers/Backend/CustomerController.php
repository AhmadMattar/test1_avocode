<?php

namespace App\Http\Controllers\Backend;

use App\Models\City;
use App\Models\Country;
use App\Models\Customer;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('Backend.customers.index', compact('countries'));
    }

    public function indexTable()
    {
        return DataTables::of(Customer::query())
        ->filter(function ($query) {
            if(request()->has('first_name')){
                $query->where('first_name', 'like', "%". request()->first_name . "%");
            }
            if(request()->has('last_name')){
                $query->where('last_name', 'like', "%". request()->last_name . "%");
            }
            if (request()->has('email')) {
                $query->where('email', 'like', "%" . request()->email . "%");
            }
            if (request()->has('phone')) {
                $query->where('phone', 'like', "%" . request()->phone . "%");
            }
            if (request()->has('country_id')) {
                $query->when(request()->country_id != null, function ($query) {
                    $query->whereCountryId(request()->country_id);
                });
            }
            if (request()->has('city_id')) {
                $query->when(request()->city_id != null, function ($query) {
                    $query->whereCityId(request()->city_id);
                });
            }
            if (request()->has('district_id')) {
                $query->when(request()->district_id != null, function ($query) {
                    $query->whereDistrictId(request()->district_id);
                });
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
        ->addColumn('cover', function ($row) {
            $url = $row->cover;
            return !empty($row->cover) ? '<img src="'.$url.'"  width="40" class="img-rounded" align="center" />' : '<img src="'.asset('Backend/uploads/no-image.jpg').'"  width="40" class="img-rounded" align="center" />';
        })
        ->addColumn('country', function($row){
            return !empty($row->country->name) ? $row->country->name : __('general.not_exist') ;
        })
        ->addColumn('city', function($row){
            return !empty($row->city->name) ? $row->city->name : __('general.not_exist');
        })
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $actionBtn = '<button
                            class="editCustomer btn btn-success btn-sm"
                            id="editBtn"
                            type="button"
                            data-id="'.$row->id.'"
                            data-first_name="'.$row->first_name.'"
                            data-last_name="'.$row->last_name.'"
                            data-email="'.$row->email.'"
                            data-phone="'.$row->phone.'"
                            data-country_id="'.$row->country_id.'"
                            data-city_id="'.$row->city_id.'"
                            data-district_id="'.$row->district_id.'"
                            data-status="'.$row->status.'"
                            data-cover="'.$row->cover.'" >';

            if (Auth::user()->can('edit_customer') || Auth::user()->can('admin_permission')) {
                $actionBtn .= '
                            <i class="fa fa-edit"></i>';
            }
            if (Auth::user()->can('delete_customer') || Auth::user()->can('admin_permission')) {
                $actionBtn .= '
                            </button>
                            <a id="deleteBtn" data-id="' . $row->id . '" class="deleteCustomer btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
            }
            return $actionBtn;
        })
        ->rawColumns(['checkbox', 'cover', 'action', 'country', 'city'])->make(true);
    }


    public function store(Request $request)
    {
        $rules = [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email',
            'password'          => 'required',
            'phone'             => 'required|numeric',
            'country_id'        => 'required',
            'city_id'           => 'required',
            'district_id'       => 'required',
            'cover'             => 'required',
        ];
        $request->validate($rules);

        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['phone'] = $request->phone;
        $data['country_id'] = $request->country_id;
        $data['city_id'] = $request->city_id;
        $data['district_id'] = $request->district_id;
        $data['status'] = $request->status;

        if($request->file('cover'))
        {
            $image_name = $request->phone.".".$request->file('cover')->getClientOriginalExtension();
            $path = public_path('/Backend/uploads/users/' . $image_name);
            Image::make($request->cover->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $image_name;
        }
        $customer = Customer::create($data);
        return response()->json([$customer, 'message' => __('general.add_successfully')]);
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
        $customer = Customer::find($request->id);

        $rules = [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email',
            'password'          => 'nullable',
            'phone'             => 'required|numeric',
            'country_id'        => 'required',
            'city_id'           => 'required',
            'district_id'       => 'required',
            'cover'             => 'nullable',
        ];
        $request->validate($rules);

        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['country_id'] = $request->country_id;
        $data['city_id'] = $request->city_id;
        $data['district_id'] = $request->district_id;
        $data['status'] = $request->status;

        if(trim($request->password) != ''){
            $data['password'] = bcrypt($request->password);
        }

        if($request->file('cover'))
        {
            $image_old_name = $request->cover->getClientOriginalName();
            if($customer->cover != null && File::exists('Backend/uploads/users/'. $image_old_name)){
                unlink('Backend/uploads/users/'. $image_old_name);
            }
            $image_name = $request->phone.".".$request->file('cover')->getClientOriginalExtension();
            $path = public_path('/Backend/uploads/users/' . $image_name);
            Image::make($request->file('cover')->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $image_name;
        }

        $customer->update($data);
        return response()->json([$customer, 'message' => __('general.updated_successfully')]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $customer = Customer::findOrFail($request->id);
        $image_name = explode("/",$customer->cover)[6];
        if(File::exists('Backend/uploads/users/'. $image_name)){
            unlink('Backend/uploads/users/'. $image_name);
        }
        $customer->delete();
        return response()->json([$customer, 'message' => __('general.delete_successfully')]);
    }

    public function get_cities(Request $request)
    {
        $cities = City::whereCountryId($request->country_id)->get()->toArray();

        return response()->json($cities);
    }

    public function get_districts(Request $request)
    {
        $districts = District::whereCityId($request->city_id)->get()->toArray();

        return response()->json($districts);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->id;
        $customers = Customer::whereIn('id', $ids);
        for($i = 0; $i < count($ids); $i++){
            $customer = Customer::findOrFail($ids[$i]);
            $image_name = explode("/",$customer->cover)[6];
            if(File::exists('Backend/uploads/users/'. $image_name)){
                unlink('Backend/uploads/users/'. $image_name);
            }
        }
        $customers->delete();
        return response()->json([$customers, 'message' => __('general.delete_successfully')]);
    }

    public function activeAll(Request $request)
    {
        $ids = $request->id;
        $customers = Customer::whereIn('id', $ids);
        $customers->update([
            'status' => 1,
        ]);
        return response()->json([$customers, 'message' => __('general.active_successfully')]);
    }

    public function disactiveAll(Request $request)
    {
        $ids = $request->id;
        $customers = Customer::whereIn('id', $ids);
        $customers->update([
            'status' => 0,
        ]);
        return response()->json([$customers, 'message' => __('general.disactive_successfully')]);
    }
}
