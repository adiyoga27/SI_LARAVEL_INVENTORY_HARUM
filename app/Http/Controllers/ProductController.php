<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\UnitProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $units = UnitProduct::all();
        $categories = CategoryProduct::all();
        $suppliers = Supplier::all();
        if ($request->ajax()) {
            $data = Product::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'delete'          => route('product.destroy', $data->id),
                        'edit'          => route('product.edit', $data->id),
                        'confirm_message' =>  'Anda akan menghapus data "' . $data->name . '" ?',
                        'padding'         => '85px',
                    ]);
                })
                ->addColumn('id', function ($data) {
                    return $data->id;
                })
                ->addColumn('category', function ($data) {
                    return $data->category->name;
                })
                ->addColumn('unit', function ($data) {
                    return $data->unit->name;
                })
                ->addColumn('supplier', function ($data) {
                    return $data->supplier->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('products.product', compact(['units', 'categories', 'suppliers']));
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
    public function store(StoreProductRequest $request)
    {
        try {
            $images = $request->file('images');
            $rand = rand() . '.' . $images->getClientOriginalExtension();
            $imageName = $request->images->storeAs('images', $rand);
            $form_data = array(
                'category_id' => $request->category_id,
                'unit_id' => $request->unit_id,
                'supplier_id' => $request->supplier_id,
                'images' => $imageName,
                'name' => $request->name,
                'price' => $request->price,
            );
            Product::create($form_data);
            return redirect()->route('product.index')->with('success', 'Data Product Berhasil Diinput!');
        } catch (\Throwable $th) {
            return redirect()->route('product.index')->with('error', 'Data Product Gagal Diinput!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Product::where('id', $id)->first();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Product::where('id', $id)->first();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $form_data = array(
                'category_id' => $request->category_id,
                'unit_id' => $request->unit_id,
                'supplier_id' => $request->supplier_id,
                'name' => $request->name,
                'price' => $request->price,
            );
            if (isset($request->images)) {
                $images = $request->file('images');
                $rand = rand() . '.' . $images->getClientOriginalExtension();
                $imageName = $request->images->storeAs('images', $rand);
                $form_data = array(
                    'category_id' => $request->category_id,
                    'unit_id' => $request->unit_id,
                    'supplier_id' => $request->supplier_id,
                    'images' => $imageName,
                    'name' => $request->name,
                    'price' => $request->price,
                );
            }

            Product::where('id', $id)->update($form_data);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $request->all()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data : ' . $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Product::where('id', $id)->delete();
            return redirect()->route('product.index')->with('success', 'Data Product Berhasil Dihapus!');
        } catch (\Throwable $th) {
            return redirect()->route('product.index')->with('error', 'Data Product Gagal Dihapus!');
        }
    }
}
