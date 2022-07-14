<?php

namespace App\Http\Controllers;

use COM;
use App\Models\City;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('users.index', compact('countries'));
    }

    public function getUsers()
    {
        return DataTables::of(User::query())
        ->addColumn('checkbox', function($row){
            return '<input type="checkbox" name="users_checkbox[]" class="users_checkbox" value="'.$row->id.'" />';
        })
        ->addColumn('status', function ($row) {
                    return $row->status ? 'Active' : 'Inactive';
        })
        ->addColumn('cover', function ($row) {
            $url = $row->cover;
            return '<img src="'.$url.'"  width="40" class="img-rounded" align="center" />';
        })
        ->addColumn('country', function($row){
            return $row->country->name;
        })
        ->addColumn('city', function($row){
            return $row->city->name;
        })
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $actionBtn = '<button
                            class="editUser btn btn-success btn-sm"
                            id="editBtn"
                            type="button"
                            data-id="'.$row->id.'"
                            data-first_name="'.$row->first_name.'"
                            data-last_name="'.$row->last_name.'"
                            data-email="'.$row->email.'"
                            data-phone="'.$row->phone.'"
                            data-country_id="'.$row->country_id.'"
                            data-city_id="'.$row->city_id.'"
                            data-city_name="'.$row->city->name.'"
                            data-status="'.$row->status.'"
                            data-cover="'.$row->cover.'" >
                <i class="fa fa-edit"></i>
            </button>
            <a id="deleteBtn" data-id="' . $row->id . '" class="deleteUser btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
            return $actionBtn;
        })
        ->rawColumns(['checkbox', 'cover', 'action', 'country', 'city'])->make(true);
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email',
            'phone'             => 'required|numeric',
            'country_id'        => 'required',
            'city_id'           => 'required',
            'cover'             => 'required',
        ];
        $request->validate($rules);

        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['country_id'] = $request->country_id;
        $data['city_id'] = $request->city_id;
        $data['status'] = $request->status;

        if($request->file('cover'))
        {
            $image_name = $request->phone.".".$request->file('cover')->getClientOriginalExtension();
            $path = public_path('/uploads/users/' . $image_name);
            Image::make($request->cover->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $image_name;
        }
        $user = User::create($data);
        return response()->json($user);
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
        $user = User::find($request->id);

        $rules = [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email',
            'phone'             => 'required|numeric',
            'country_id'        => 'required',
            'city_id'           => 'required',
            'cover'             => 'nullable',
        ];
        $request->validate($rules);

        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['country_id'] = $request->country_id;
        $data['city_id'] = $request->city_id;
        $data['status'] = $request->status;

        if($request->file('cover'))
        {
            $image_old_name = $request->cover->getClientOriginalName();
            if($user->cover != null && File::exists('uploads/users/'. $image_old_name)){
                unlink('uploads/users/'. $image_old_name);
            }
            $image_name = $request->phone.".".$request->file('cover')->getClientOriginalExtension();
            $path = public_path('/uploads/users/' . $image_name);
            Image::make($request->file('cover')->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $image_name;
        }

        $user->update($data);
        return response()->json($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id);
        $image_name = explode("/",$user->cover)[5];
        if(File::exists('uploads/users/'. $image_name)){
            unlink('uploads/users/'. $image_name);
        }
        $user->delete();
        return true;
    }

    public function get_cities(Request $request)
    {
        $cities = City::whereCountryId($request->country_id)->get()->toArray();

        return response()->json($cities);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->id;
        $users = User::whereIn('id', $ids);
        $users->delete();
        return true;
    }

    public function activeAll(Request $request)
    {
        $ids = $request->id;
        $users = User::whereIn('id', $ids);
        $users->update([
            'status' => 1,
        ]);
        return true;
    }

    public function disactiveAll(Request $request)
    {
        $ids = $request->id;
        $users = User::whereIn('id', $ids);
        $users->update([
            'status' => 0,
        ]);
        return true;
    }
}
