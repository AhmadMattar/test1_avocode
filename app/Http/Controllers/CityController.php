<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
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
        return view('cities.index', compact('countries'));
    }

    public function getCities()
    {
        return DataTables::of(City::query())
        ->addColumn('checkbox', function($row){
            return '<input type="checkbox" name="cities_checkbox[]" class="cities_checkbox" value="'.$row->id.'" />';
        })
        ->addColumn('status', function ($row) {
                    return $row->status ? 'Active' : 'Inactive';
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
        return response()->json($city);
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
        return response()->json($city);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return City::find($request->id)->delete();
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->id;
        $cities = City::whereIn('id', $ids);
        $cities->delete();
        return true;
    }

    public function activeAll(Request $request)
    {
        $ids = $request->id;
        $cities = City::whereIn('id', $ids);
        $cities->update([
            'status' => 1,
        ]);
        return true;
    }

    public function disactiveAll(Request $request)
    {
        $ids = $request->id;
        $cities = City::whereIn('id', $ids);
        $cities->update([
            'status' => 0,
        ]);
        return true;
    }
}
