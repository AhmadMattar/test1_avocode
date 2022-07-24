<?php

namespace App\Http\Controllers\Backend;

use App\Models\City;
use App\Models\User;
use App\Models\Country;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('Backend.users.index', compact('permissions'));
    }

    public function indexTable()
    {
        return DataTables::of(User::query())
            ->filter(function ($query) {
                if (request()->has('name')) {
                    $query->where('name', 'like', "%" . request()->name . "%");
                }
                if (request()->has('email')) {
                    $query->where('email', 'like', "%" . request()->email . "%");
                }
                if (request()->has('status')) {
                    $query->when(request()->status != null, function ($query) {
                        $query->whereStatus(request()->status);
                    });
                }
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="items_checkbox[]" class="items_checkbox" value="' . $row->id . '" />';
            })
            ->addColumn('status', function ($row) {
                return $row->status ? __('general.Activated') : __('general.Inactivated');
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $permissions = implode(',', User::find($row->id)->permissions->pluck('id')->toArray()) . ",";
                $actionBtn = '<button
                            class="editUser btn btn-success btn-sm"
                            id="editBtn"
                            type="button"
                            data-id="' . $row->id . '"
                            data-name="' . $row->name . '"
                            data-email="' . $row->email . '"
                            data-status="' . $row->status . '"
                            data-permissions="' . $permissions . '" >
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
            'name'          => 'required',
            'email'         => 'required|email|unique:users',
            'password'      => 'required',
            'permission'    => 'required',
        ];
        $request->validate($rules);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);
        $data['status'] = $request->status;

        $user = User::create($data);

        $user->givePermissionTo($request->permission);

        return response()->json([$user, 'message' => __('general.add_successfully')]);
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
            'name'              => 'required',
            'email'             => 'required|email|unique:users,email,' . $request->id,
            'password'          => 'nullable',
            'permission_id'     => 'nullable',
        ];
        $request->validate($rules);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['status'] = $request->status;
        if (request()->has('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        if (request()->has('permission_id')) {
            $user->syncPermissions($request->permission_id);
        }

        return response()->json([$user, 'message' => __('general.updated_successfully')]);
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
        $permissions = User::find($user->id)->permissions->pluck('id');
        $user->revokePermissionTo($permissions);
        $user->delete();
        return response()->json([$user, 'message' => __('general.delete_successfully')]);
    }

    public function deleteAll(Request $request)
    {
        
        DB::table('model_has_permissions')->whereIn('model_id', $request->id)->delete();
        User::whereIn('id', $request->id)->delete();

        return response()->json(['status'=> true, 'message' => __('general.delete_successfully')]);
    }

    public function activeAll(Request $request)
    {
        // dd($request->all());
        $ids = $request->id;
        $users = User::whereIn('id', $ids);
        $users->update([
            'status' => 1,
        ]);
        return response()->json([$users, 'message' => __('general.active_successfully')]);
    }

    public function disactiveAll(Request $request)
    {
        // dd($request->all());
        $ids = $request->id;
        $users = User::whereIn('id', $ids);
        $users->update([
            'status' => 0,
        ]);
        return response()->json([$users, 'message' => __('general.disactive_successfully')]);
    }
}
