<?php

namespace App\Http\Controllers\RawatJalan;
use DB;
use App\Icd;
use App\Poli;
use App\Ruang;
use App\Dokter;
use App\Pasien;
use App\Tindakan;
use App\DaftarRawatInap;
use App\DaftarRawatJalan;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Helpers\FunctionHelper;
use App\Http\Controllers\Controller;

class TransaksiRawatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function transaksiJSON(){
        $daftar = DB::table('daftar_rawat_jalan')
                        ->join('pasien','daftar_rawat_jalan.id_pasien','=','pasien.id')
                        ->join('poli','daftar_rawat_jalan.id_poli', '=', 'poli.id')
                        ->join('dokter','daftar_rawat_jalan.id_dokter','=','dokter.id')
                        ->join('diagnosa','daftar_rawat_jalan.id_diagnosa','=','diagnosa.id')
                        ->join('icd','daftar_rawat_jalan.id_icd','=','icd.id')
                        ->select('pasien.*','pasien.id as id_pasien','daftar_rawat_jalan.*','daftar_rawat_jalan.id as id_rawat_jalan','poli.*','diagnosa.*', 'dokter.*')
                        ->where('daftar_rawat_jalan.status','=',0)
                        ->get(); 
        $data = [];
        foreach($daftar as $rawatJalan) {
            $data[] = [
                'id' => $rawatJalan->id_rawat_jalan,
                'id_pasien' => $rawatJalan->id_pasien,
                'nama_pasien' => $rawatJalan->nama_pasien,
                'tanggal_lahir' => $rawatJalan->tanggal_lahir,
                'nama_dokter' => $rawatJalan->nama_dokter,
                'tanggal_kunjungan' => $rawatJalan->tanggal_kunjungan,
            ];
        }

  
        return Datatables::of($data)
        ->addColumn('tindakan', function ($data){
            return'
            <a href="'.route('rawatJalan.detailPasien', $data['id']).'" ><button type="button" id="'.$data['id'].'" class="btn btn-success btn-labeled btn-labeled-left btn-sm detail-rawatJalan"><b><i class="icon-pencil5"></i></b>Detail</button></a>
            <a href="#" ><button type="button" id="'.$data['id'].'" data-idPasien="'.$data['id_pasien'].'" class="btn btn-warning btn-labeled btn-labeled-left btn-sm mutasi-pasien"><b><i class="icon-pencil5"></i></b>Mutasi</button></a>
            <button type="button" id="'.$data['id'].'" class="btn btn-primary btn-labeled btn-labeled-left btn-sm rajal-invoice"><b><i class="icon-pencil5"></i></b>Invoice</button>
          
            ';

        })
        ->rawColumns(['tindakan'])
        ->addIndexColumn()
        ->make(true);
    }

    public function index()
    {
        $menus = FunctionHelper::callMenu();
        return view('rawatjalan.transaksi', ['menus' => $menus]);
    }

    public function detailPasien(Request $req) {
        $pasien = Pasien::find($req->id);

        $rawatJalan = DB::table('daftar_rawat_jalan')
                        ->join('pasien','daftar_rawat_jalan.id_pasien','=','pasien.id')
                        ->join('poli','daftar_rawat_jalan.id_poli', '=', 'poli.id')
                        ->join('dokter','daftar_rawat_jalan.id_dokter','=','dokter.id')
                        ->join('diagnosa','daftar_rawat_jalan.id_diagnosa','=','diagnosa.id')
                        ->join('icd','daftar_rawat_jalan.id_icd','=','icd.id')
                        ->select('daftar_rawat_jalan.*', 'daftar_rawat_jalan.id as id_rawat_jalan', 'pasien.*', 'pasien.id as id_pasien')
                        ->where('daftar_rawat_jalan.id','=' ,$req['id'])
                        ->first(); 

        $poli     = Poli::all();
        $dokter   = Dokter::all();
        $icd      = Icd::all();
        $tindakan = Tindakan::all();
        $menus = FunctionHelper::callMenu();
        return view('rawatjalan.detail', [
                                'icd' => $icd,
                                'poli' => $poli,
                                'dokter'=> $dokter,
                                'menus' => $menus,
                                'pasien' => $pasien,
                                'rawatJalan' => $rawatJalan,
                                'tindakan' => $tindakan
                            ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function simpan(Request $req) {

        $daftar = DaftarRawatJalan::find($req['id']);
        $daftar->id_poli = $req->formData[5]["value"];
        $daftar->id_dokter = $req->formData[4]["value"];
        $daftar->catatan = $req->formData[6]["value"];
        $daftar->alergi = $req->formData[7]["value"];
        //TODO: auth
        $daftar->id_user =  1;
        $daftar->save();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $data = DaftarRawatJalan::find($req['id']);
        $data->delete();

    }
    public function MutasiPasien(Request $req) {
        $daftar = new DaftarRawatInap();
        $daftar->id_transaksi_rawat_jalan = $req['idRawatJalan'];
        $daftar->id_ruang = $req['idRuang'];
        $daftar->id_pasien = $req['id_pasien'];
        $daftar->tanggal_mutasi = $req['tanggal'];
        $daftar->save();

        DB::table('daftar_rawat_jalan')
            ->where('id', $req['idRawatJalan'])
            ->update(['status' => 1]);
        
        DB::table('ruang')
            ->where('id', $req['idRuang'])
            ->update(['status_ruang' => 1]);

    }
    public function mutasiRuang() {
        $getRuang = DB::table('ruang')->where('status_ruang','=','0')->get(); 
        $data = [];
        foreach($getRuang as $ruang) {
            $data[] = [
                'id' => $ruang->id,
                'koderuang' => $ruang->kode_ruang,
                'namaruang' => $ruang->nama_ruang,
                'tanggal_lahir' => $ruang->status_ruang
            ];
        }
        return Datatables::of($data)
        ->addColumn('tindakan', function ($data){
            return'
            <a href="#" ><button type="button" id="'.$data['id'].'" class="btn btn-success btn-labeled btn-labeled-left btn-sm mutasi-proses"><b><i class="icon-pencil5"></i></b>Proses</button></a>
            ';
        })
        ->rawColumns(['tindakan'])
        ->addIndexColumn()
        ->make(true);
    }
    
    public function invoice(Request $req) {
        DB::table('daftar_rawat_jalan')
            ->where('id', $req['id'])
            ->update(['status' => 2]);
    }
}
