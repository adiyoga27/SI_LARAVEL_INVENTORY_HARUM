<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateCategoryProductRequest;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CategoryProduct::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'delete'          => route('category-product.destroy', $data->id),
                        'edit'          => route('category-product.edit', $data->id),
                        'confirm_message' =>  'Anda akan menghapus data "' . $data->name . '" ?',
                        'padding'         => '85px',
                    ]);
                })

                ->rawColumns(['action'])
                ->make(true);
        }
        return view('products.category');
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
    public function store(StoreUpdateCategoryProductRequest $request)
    {
        try {
            CategoryProduct::create($request->all());
            return redirect()->route('category-product.index')->with('success', 'Kategori Produk Berhasil Diinput!');
        } catch (\Throwable $th) {
            return redirect()->route('category-product.index')->with('error', 'Kategori Produk Gagal Diinput!');
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
        $data = CategoryProduct::where('id', $id)->first();
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
        $data = CategoryProduct::where('id', $id)->first();
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
    public function update(StoreUpdateCategoryProductRequest $request, $id)
    {
        try {
            CategoryProduct::where('id', $id)->update($request->except('_token', '_method'));
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
            CategoryProduct::where('id', $id)->delete();
            return redirect()->route('category-product.index')->with('success', 'Kategori Produk Berhasil Dihapus!');
        } catch (\Throwable $th) {
            return redirect()->route('category-product.index')->with('error', 'Kategori Produk Gagal Dihapus!');
        }
    }
}
