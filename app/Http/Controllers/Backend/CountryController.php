<?php

namespace App\Http\Controllers\Backend;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('Backend.countries.index');
    }

    public function indexTable()
    {
        return DataTables::of(Country::query())
        ->filter(function ($query) {
            if (request()->has('name')){
                $query->whereTranslationLike('name', '%'. request()->name .'%');
            }
            if (request()->has('code')) {
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
        ->addColumn('cover', function ($row) {
            $url = $row->cover;
            return '<img src="'.$url.'"  width="40" class="img-rounded" align="center" />';
        })
        ->addColumn('status', function ($row) {
            return $row->status ? __('general.Activated') : __('general.Inactivated');
        })
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $actionBtn = '<button
                            class="editCountry btn btn-success btn-sm"
                            id="editBtn"
                            type="button"
                            data-id="'.$row->id.'"
                            data-code="'.$row->code.'"
                            data-status="'.$row->status.'"
                            data-cover="'.$row->cover.'" ';

            foreach(config('app.langauges') as $key => $value ){
                $actionBtn .= "data-name_$key = ". $row->translate($key)->name ." ";
            }
            if (Auth::user()->can('edit_country') || Auth::user()->can('admin_permission')) {
                $actionBtn .= '>
                            <i class="fa fa-edit"></i>';
            }
            if (Auth::user()->can('delete_country') || Auth::user()->can('admin_permission')) {
                $actionBtn .= '
                            </button>
                            <a id="deleteBtn" data-id="' . $row->id . '" class="deleteCountry btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
            }
            return $actionBtn;
        })
        ->rawColumns(['checkbox', 'cover', 'action'])->make(true);
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
            'code'      => 'required|numeric|unique:countries',
            'cover'     => 'required',
        ];
        foreach (config('app.langauges') as $key => $locale) {
            $rules['name_' . $key] = 'required|string|max:255|unique:country_translations,name';
        }
        $request->validate($rules);

        $data = $request->only('code');
        $data['status'] = $request->status;

        foreach (config('app.langauges') as $key => $locale) {
            $data[$key] = ['name' => $request->get('name_' . $key)];
        }

        if($request->file('cover'))
        {
            $image_name = $request->code.".".$request->file('cover')->getClientOriginalExtension();
            $path = public_path('Backend/uploads/countries/' . $image_name);
            Image::make($request->cover->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $image_name;
        }
        $country = Country::create($data);
        return response()->json([$country, 'message' => __('general.add_successfully')]);
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
        $country = Country::find($request->id);
        $rules = [
            'code'      => 'required|numeric|unique:countries,code,'.$request->id,
            'cover'     => 'nullable',
        ];
        foreach (config('app.langauges') as $key => $locale) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $request->validate($rules);

        foreach (config('app.langauges') as $key => $locale) {
            $data[$key] = ['name' => $request->get('name_' . $key)];
        }

        $data['code'] = $request->code;
        $data['status'] = $request->status;

        if($request->file('cover'))
        {
            $image_old_name = $request->cover->getClientOriginalName();
            if($country->cover != null && File::exists('Backend/uploads/countries/'. $image_old_name)){
                unlink('Backend/uploads/countries/'. $image_old_name);
            }
            $image_name = $request->code.".".$request->file('cover')->getClientOriginalExtension();
            $path = public_path('Backend/uploads/countries/' . $image_name);
            Image::make($request->file('cover')->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $image_name;
        }

        $country->update($data);
        return response()->json([$country, 'message' => __('general.updated_successfully')]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $country = Country::findOrFail($request->id);
        $image_name = explode("/",$country->cover)[6];
        if(File::exists('Backend/uploads/countries/'. $image_name)){
            unlink('Backend/uploads/countries/'. $image_name);
        }
        $country->delete();
        return response()->json([$country, 'message' => __('general.delete_successfully')]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->id;
        $countries = Country::whereIn('id', $ids);
        $countries->delete();
        return response()->json([$countries, 'message' => __('general.delete_successfully')]);
    }

    public function activeAll(Request $request)
    {
        $ids = $request->id;
        $countries = Country::whereIn('id', $ids);
        $countries->update([
            'status' => 1,
        ]);
        return response()->json([$countries, 'message' => __('general.active_successfully')]);
    }

    public function disactiveAll(Request $request)
    {
        $ids = $request->id;
        $countries = Country::whereIn('id', $ids);
        $countries->update([
            'status' => 0,
        ]);
        return response()->json([$countries, 'message' => __('general.disactive_successfully')]);
    }
}

