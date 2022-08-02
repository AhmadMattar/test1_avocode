<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Country;
use App\Models\Customer;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function profile()
    {
        $customer = Customer::query()->find(auth()->user()->id);

        return $this->mainResponse(true, 'success', $customer);
    }

    public function updateProfile(Request $request)
    {
        $customer = Customer::query()->find(auth()->user()->id);

        $rules = [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'country_id'        => 'required',
            'city_id'           => 'required',
            'district_id'       => 'required',
            'cover'             => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['country_id'] = $request->country_id;
        $data['city_id'] = $request->city_id;
        $data['district_id'] = $request->district_id;

        if($request->file('cover'))
        {
            $image_name = "apiTest_".time().".".$request->file('cover')->getClientOriginalExtension();
            $request->file('cover')->move(public_path('/Backend/uploads/users/'), $image_name);
            $data['cover'] = $image_name;
            $customer->cover = $data['cover'];
        }

        $customer->update($data);

        return $this->mainResponse(true, 'Updated successfully', $customer);

    }

    public function getLocations()
    {
        $countries = Country::with('cities', 'cities.districts')->get();
        return $this->mainResponse(true, 'success', $countries);
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
