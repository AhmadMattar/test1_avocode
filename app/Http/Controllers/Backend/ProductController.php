<?php

namespace App\Http\Controllers\Backend;

use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Backend.products.index');
    }

    public function indexTable()
    {
        return DataTables::of(Product::query())
            ->filter(function ($query) {
                if (request()->has('name')) {
                    $query->whereTranslationLike('name', '%' . request()->name . '%');
                }
                if (request()->has('price')) {
                    $query->where('price', 'like', "%" . request()->price . "%");
                }
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="items_checkbox[]" class="items_checkbox" value="' . $row->id . '" />';
            })
            ->addColumn('cover', function ($row) {
                $url = $row->cover;
                return '<img src="' . $url . '"  width="40" class="img-rounded" align="center" />';
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $productImages = implode(',',  $row->medias->pluck('image')->toArray()) . ",";
                $actionBtn = '<button
                            class="editProduct btn btn-success btn-sm"
                            id="editBtn"
                            type="button"
                            data-id="' . $row->id . '"
                            data-price="' . $row->price . '"
                            data-quantity="' . $row->quantity . '"
                            data-cover="' . $row->cover . '"
                            data-images="' . $productImages . '" ';

                foreach (config('app.langauges') as $key => $value) {
                    $actionBtn .= "data-name_$key = " . $row->translate($key)->name . " ";
                }
                if (Auth::user()->can('edit_product') || Auth::user()->can('admin_permission')) {
                    $actionBtn .= '>
                            <i class="fa fa-edit"></i>';
                }
                if (Auth::user()->can('delete_product') || Auth::user()->can('admin_permission')) {
                    $actionBtn .= '
                            </button>
                            <a id="deleteBtn" data-id="' . $row->id . '" class="deleteProduct btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
                }
                return $actionBtn;
            })
            ->rawColumns(['checkbox', 'cover', 'action'])->make(true);
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
            'price'         => 'required|numeric',
            'quantity'      => 'required|numeric',
            'cover'         => 'required|mimes:jpg,jpeg,png,gif|max:4000',
            'images'        => 'required',
            'images.*'      => 'mimes:jpg,jpeg,png,gif|max:3000',
        ];
        foreach (config('app.langauges') as $key => $locale) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $request->validate($rules);

        $data['price'] = $request->price;
        $data['quantity'] = $request->quantity;

        foreach (config('app.langauges') as $key => $locale) {
            $data[$key] = ['name' => $request->get('name_' . $key)];
        }

        if ($request->file('cover')) {
            $image_name = time() . "." . $request->file('cover')->getClientOriginalExtension();
            $path = public_path('Backend/uploads/products/' . $image_name);
            Image::make($request->cover->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $image_name;
        }
        $product = Product::create($data);

        if ($request->file('images') && count($request->images) > 0) {
            $i = 1;
            foreach ($request->images as $image) {
                $image_name = time() . '_' . $i . '.' . $image->getClientOriginalExtension();
                $path = public_path('Backend/uploads/products/' . $image_name);
                Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                Media::create([
                    'product_id'    => $product->id,
                    'image'         => $image_name,
                ]);
                $i++;
            }
        }
        return response()->json([$product, 'message' => __('general.add_successfully')]);
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
        $removed_images = [];
        foreach ($request->removed_images as $image) {
            if ($image != null) {
                $removed_images[] = $image;
            }
        }

        $product = Product::find($request->id);
        $cover = explode("/", $product->cover)[6];

        $rules = [
            'price'         => 'required|numeric',
            'quantity'      => 'required|numeric',
            'cover'         => 'nullable',
            'images'        => 'nullable',
            'images.*'      => 'mimes:jpg,jpeg,png,gif|max:4000',
        ];

        if ($product->medias()->count() == count($removed_images)) {
            $rules['images'] = 'required';
            $rules['images.*'] = 'mimes:jpg,jpeg,png,gif|max:4000';
        } else {
            $rules['images'] = 'nullable';
            $rules['images.*'] = 'mimes:jpg,jpeg,png,gif|max:4000';
        }

        foreach (config('app.langauges') as $key => $locale) {
            $rules['name_' . $key] = 'required|string|max:255';
        }

        $request->validate($rules);

        if (count($removed_images) > 0) {
            foreach ($removed_images as $image) {
                $media = Media::whereImage($image)->first();
                if (File::exists('Backend/uploads/products/' . $image)) {
                    unlink('Backend/uploads/products/' . $image);
                }
                $media->delete();
            }
        }


        foreach (config('app.langauges') as $key => $locale) {
            $data[$key] = ['name' => $request->get('name_' . $key)];
        }

        $data['price'] = $request->price;
        $data['quantity'] = $request->quantity;

        if ($request->file('cover')) {
            if ($product->cover != null && File::exists('Backend/uploads/products/' . $cover)) {
                unlink('Backend/uploads/products/' . $cover);
            }
            $image_name = time() . "." . $request->file('cover')->getClientOriginalExtension();
            $path = public_path('Backend/uploads/products/' . $image_name);
            Image::make($request->file('cover')->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['cover'] = $image_name;
        }
        $product->update($data);

        if ($request->images && count($request->images) > 0) {
            $i = $product->medias()->count() + 1;
            foreach ($request->images as $image) {
                $file_name = time() . '_' . $i . '.' . $image->getClientOriginalExtension();
                $path = public_path('Backend/uploads/products/' . $file_name);
                Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $product->medias()->create([
                    'product_id'    => $product->id,
                    'image'         => $file_name,
                ]);
                $i++;
            }
        }

        return response()->json([$product, 'message' => __('general.updated_successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $image_name = explode("/", $product->cover)[6];
        if (File::exists('Backend/uploads/products/' . $image_name)) {
            unlink('Backend/uploads/products/' . $image_name);
        }

        if ($product->medias()->count() > 0) {

            foreach ($product->medias as $media) {
                $image = explode("/", $media->image)[6];
                if (File::exists('Backend/uploads/products/' . $image)) {
                    unlink('Backend/uploads/products/' . $image);
                }
            }
        }
        $product->delete();
        return response()->json([$product, 'message' => __('general.delete_successfully')]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->id;
        $product = Product::whereIn('id', $ids)->get();

        foreach ($product as $pro) {
            $image_name = explode("/", $pro->cover)[6];
            if (File::exists('Backend/uploads/products/' . $image_name)) {
                unlink('Backend/uploads/products/' . $image_name);
            }
            if ($pro->medias()->count() > 0) {
                foreach ($pro->medias as $media) {
                    $image = explode("/", $media->image)[6];
                    if (File::exists('Backend/uploads/products/' . $image)) {
                        unlink('Backend/uploads/products/' . $image);
                    }
                    $media->delete();
                }
            }
            $pro->delete();
        }
        return response()->json([$product, 'message' => __('general.delete_successfully')]);
    }
}
