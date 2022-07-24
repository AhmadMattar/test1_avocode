<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('Backend.roles.index', compact('permissions'));
    }

    public function indexTable()
    {
        return DataTables::of(Role::query())
            ->filter(function ($query) {
                if (request()->has('name')) {
                    $query->where('name', 'like', "%" . request()->name . "%");
                }
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="items_checkbox[]" class="items_checkbox" value="' . $row->id . '" />';
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $permissions = implode(',', Role::findById($row->id)->permissions->pluck('id')->toArray()) . ",";
                $actionBtn = '<button
                            class="editUser btn btn-success btn-sm"
                            id="editBtn"
                            type="button"
                            data-id="' . $row->id . '"
                            data-name="' . $row->name . '"
                            data-permission_id="' . $permissions . '" >
                <i class="fa fa-edit"></i>
            </button>
            <a id="deleteBtn" data-id="' . $row->id . '" class="deleteUser btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
                return $actionBtn;
            })
            ->rawColumns(['checkbox', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $rules = [
            'name'          => 'required',
        ];
        $request->validate($rules);

        $data['name'] = $request->name;

        $role = Role::create($data);

        $role->givePermissionTo($request->permission_id);

        return response()->json([$role, 'message' => __('general.add_successfully')]);
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
        $role = Role::find($request->id);

        $rules = [
            'name'          => 'required',
        ];
        $request->validate($rules);

        $data['name'] = $request->name;

        $role->update($data);

        $role->givePermissionTo($request->permission_id);

        return response()->json([$role, 'message' => __('general.add_successfully')]);
    }

    public function destroy(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $role->delete();
        return response()->json([$role, 'message' => __('general.delete_successfully')]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->id;
        $roles = Role::whereIn('id', $ids);
        $roles->delete();
        return response()->json([$roles, 'message' => __('general.delete_successfully')]);
    }
}
