<?php

namespace App\Http\Controllers\Lainnya;


use Illuminate\Http\Request;
use App\Tindakan;
use Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\FunctionHelper;

class TindakanMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function tindakanJSON() {
        $tindakans = DB::table('tindakan')
        ->select('tindakan.*','tindakan.id as id_tindakan')
        ->get();

        $data = [];
        foreach($tindakans as $tindakan) {
            $data[] = [
                'id' => $tindakan-> id_tindakan,
                'nama_tindakan' => $tindakan -> nama_tindakan
            ];
        }
    return Datatables::of($data)
    ->addColumn('action', function ($data){
        return'
        <button type="button" data-id="'.$data['id'].'" class="btn btn-success btn-labeled btn-labeled-left btn-sm edit-icd-data" id="editTindakanBtn"><b><i class="icon-pencil5"></i></b> Edit</button>
        <button type="button" id="'.$data['id'].'" class="btn btn-warning btn-labeled btn-labeled-left btn-sm delete-tindakan-data" data-toggle="modal" data-target="#delete-modal"><b><i class="icon-bin"></i></b> Delete</button>
    ';
    })
    ->rawColumns(['action'])
    ->addIndexColumn()
    ->make(true);
} 

    public function index()
    {
        $menus = FunctionHelper::callMenu();
        $tindakans = DB::table('tindakan')
        ->select('tindakan.*','tindakan.id as id_tindakan')
        ->get();

        return view('lainnya.tindakanMaster', ['tindakan' => $tindakans, 'menus' => $menus]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editDataTindakan(Request $req)
    {   
        return Tindakan::find($req['id']);
    }

    public function store(Request $request)
    {
        $tindakan = new Tindakan();
        $tindakan ->nama_tindakan = $request->formData[0]["value"];
        $tindakan->save();


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
        $tindakan = Tindakan::find($request->id);
        $tindakan->nama_tindakan =  $request->formData[0]["value"];
        $tindakan->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        if( $req->ajax()) {
            return Tindakan::destroy($req-> id);
        }
    }
}
