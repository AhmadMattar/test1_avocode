<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('Backend.permissions.index', compact('roles'));
    }

    public function indexTable()
    {
        return DataTables::of(Permission::query())
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
                $roles = implode(',', Permission::findById($row->id)->roles->pluck('id')->toArray()) . ",";
                $actionBtn = '<button
                            class="editUser btn btn-success btn-sm"
                            id="editBtn"
                            type="button"
                            data-id="' . $row->id . '"
                            data-name="' . $row->name . '"
                            data-roles="' . $roles . '" >
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

        $permission = Permission::create($data);

        $permission->assignRole($request->role_id);

        return response()->json([$permission, 'message' => __('general.add_successfully')]);
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
        dd($request->all());
    }
}
