<?php

namespace App\Http\Controllers\Backend;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        return view('Backend.cities.index', compact('countries'));
    }

    public function indexTable()
    {
        return DataTables::of(City::query())
        ->filter(function ($query) {
            if (request()->has('name')){
                $query->whereTranslationLike('name', '%'. request()->name .'%');
            }
            if (request()->has('country_id')) {
                $query->when(request()->country_id != null, function ($query) {
                    $query->whereCountryId(request()->country_id);
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
        ->addColumn('country_id', function($row){
            return $row->country->name;
        })
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $actionBtn = '<button
                            class="editCity btn btn-success btn-sm"
                            id="editBtn"
                            type="button"
                            data-id="'.$row->id.'"
                            data-country_id="'.$row->country_id.'"
                            data-country_name="'.$row->country->name.'"
                            data-status="'.$row->status.'" ';

            foreach(config('app.langauges') as $key => $value ){
                $actionBtn .= "data-name_$key = ". $row->translate($key)->name ." ";
            }
            $actionBtn .= '>
                <i class="fa fa-edit"></i>
            </button>
            <a id="deleteBtn" data-id="' . $row->id . '" class="deleteCity btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
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
        $rules = [
            'country_id' => 'required',
        ];
        foreach (config('app.langauges') as $key => $value) {
            $rules['name_'.$key] = 'required|string|max:255|unique:city_translations,name';
        }

        $request->validate($rules);

        $data['country_id'] = $request->country_id;
        $data['status'] = $request->status;

        foreach (config('app.langauges') as $key => $value) {
            $data[$key] = ['name' => $request->get('name_'.$key)];
        }

        $city = City::create($data);
        return response()->json([$city, 'message' => __('general.add_successfully')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $city = City::find($request->id);
        $rules = [
            'country_id' => 'required',
        ];
        foreach (config('app.langauges') as $key => $value) {
            $rules['name_'.$key] = 'required|string|max:255';
        }

        $request->validate($rules);

        $data['country_id'] = $request->country_id;
        $data['status'] = $request->status;

        foreach (config('app.langauges') as $key => $value) {
            $data[$key] = ['name' => $request->get('name_'.$key)];
        }

        $city->update($data);
        return response()->json([$city, 'message' => __('general.updated_successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $city = City::find($request->id);
        $city->delete();
        return response()->json([$city, 'message' => __('general.delete_successfully')]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->id;
        $cities = City::whereIn('id', $ids);
        $cities->delete();
        return response()->json([$cities, 'message' => __('general.delete_successfully')]);
    }

    public function activeAll(Request $request)
    {
        $ids = $request->id;
        $cities = City::whereIn('id', $ids);
        $cities->update([
            'status' => 1,
        ]);
        return response()->json([$cities, 'message' => __('general.active_successfully')]);
    }

    public function disactiveAll(Request $request)
    {
        $ids = $request->id;
        $cities = City::whereIn('id', $ids);
        $cities->update([
            'status' => 0,
        ]);
        return response()->json([$cities, 'message' => __('general.disactive_successfully')]);
    }
}
