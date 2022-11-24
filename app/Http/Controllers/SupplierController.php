<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'delete'          => route('supplier.destroy', $data->id),
                        'edit'          => route('supplier.edit', $data->id),
                        'confirm_message' =>  'Anda akan menghapus data "' . $data->name . '" ?',
                        'padding'         => '85px',
                    ]);
                })

                ->rawColumns(['action'])
                ->make(true);
        }
        return view('supplier');
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
    public function store(StoreUpdateSupplierRequest $request)
    {
        try {
            Supplier::create($request->all());
            return redirect()->route('supplier.index')->with('success', 'Data Supplier Berhasil Diinput!');
        } catch (\Throwable $th) {
            return redirect()->route('supplier.index')->with('error', 'Data Supplier Gagal Diinput!');
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
        $data = Supplier::where('id', $id)->first();
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
        $data = Supplier::where('id', $id)->first();
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
    public function update(StoreUpdateSupplierRequest $request, $id)
    {
        try {
            Supplier::where('id', $id)->update($request->except('_token', '_method'));
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
            Supplier::where('id', $id)->delete();
            return redirect()->route('supplier.index')->with('success', 'Data Supplier Berhasil Dihapus!');
        } catch (\Throwable $th) {
            return redirect()->route('supplier.index')->with('error', 'Data Supplier Gagal Dihapus!');
        }
    }
}
