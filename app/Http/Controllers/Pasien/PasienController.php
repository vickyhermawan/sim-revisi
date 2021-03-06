<?php

namespace App\Http\Controllers\Pasien;

use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Icd;
use App\Poli;
use App\Dokter;
use App\DaftarRawatJalan;
use App\Pasien;
use Redirect;
use Yajra\Datatables\Datatables;
use App\Helpers\FunctionHelper;
use Illuminate\Support\Facades\DB;
class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function pasienJSON() {
        $pasien = Pasien::orderBy('id', 'asc')->get();
        $data = [];
        foreach($pasien as $pasiens) {
            $data[] = [
                'id' => $pasiens->id,
                'nama_pasien' => $pasiens->nama_pasien,
                'jenis_kelamin' => $pasiens->jenis_kelamin,
                'tanggal_lahir' => $pasiens->tanggal_lahir,
                'status' => $pasiens->status,
                'alamat' => $pasiens->alamat,
            ];
        }
        return Datatables::of($data)
        ->addColumn('action', function ($data){
            return'
                <a href="'.route('pasien.rekamMedisPasien', $data['id']).'" ><button type="button" id="'.$data['id'].'" class="btn btn-success btn-labeled btn-labeled-left btn-sm daftar-rawatjalan"><b><i class="icon-database2"></i></b>Rekam Medis</button></a>
                <a href="'.route('pasien.detailPasien', $data['id']).'" ><button type="button" id="'.$data['id'].'" class="btn btn-success btn-labeled btn-labeled-left btn-sm daftar-rawatjalan"><b><i class="icon-pencil5"></i></b>Detail</button></a>
                <button type="button" id="'.$data['id'].'" class="btn btn-warning btn-labeled btn-labeled-left btn-sm delete-modal" data-toggle="modal" data-target="#delete-modal"><b><i class="icon-bin"></i></b> Delete</button>
            ';
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function index()
    {
        $menus = FunctionHelper::callMenu();

        $pasien = Pasien::orderBy('id', 'asc')->get();

        $pasiens = DB::table('pasien')
                ->select('pasien.*')
                ->get();
                
        return view('pasien.pasien', ['pasien' => $pasien, 'menus' => $menus, 'pasiens' => $pasiens]);
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

    public function detailPasien(Request $req) {
        $pasien = Pasien::find($req->id);

        $rawatJalan = DB::table('pasien')
                        ->select('pasien.id as id_pasien')
                        ->where('pasien.id','=' ,$req['id'])
                        ->first(); 
        $menus = FunctionHelper::callMenu();
        return view('pasien.edit', ['menus' => $menus, 'pasien' => $pasien,]);
    }

    public function rekamMedisPasien(Request $req){
        $pasien = Pasien::find($req->id);
        $menus = FunctionHelper::callMenu();
        return view('pasien.rekammedis', [
                                'menus' => $menus,
                                'pasien' => $pasien,
                            ]);
    }

    public function rekmedTransaksiInapJSON(Request $req) {
        $pasieninap = DB::table('transaksi_rawat_inap')
                    ->join('daftar_rawat_inap','transaksi_rawat_inap.id_daftar_rawat_inap','=','daftar_rawat_inap.id')
                    ->join('ruang','daftar_rawat_inap.id_ruang','=','ruang.id')
                    ->join('transaksi_rawat_jalan','daftar_rawat_inap.id_transaksi_rawat_jalan','=','transaksi_rawat_jalan.id')
                    ->join('daftar_rawat_jalan','transaksi_rawat_jalan.id_daftar_rawat_jalan','=','daftar_rawat_jalan.id')
                    ->join('pasien','daftar_rawat_jalan.id_pasien','=','pasien.id')
                    ->join('poli','daftar_rawat_jalan.id_poli', '=', 'poli.id')
                    ->join('dokter','daftar_rawat_jalan.id_dokter','=','dokter.id')
                    ->join('diagnosa','daftar_rawat_jalan.id_diagnosa','=','diagnosa.id')
                    ->join('icd','daftar_rawat_jalan.id_icd','=','icd.id')
                    ->select('transaksi_rawat_inap.*','transaksi_rawat_inap.id as id_transaksi_rawat_inap','daftar_rawat_inap.*','transaksi_rawat_jalan.*','daftar_rawat_jalan.*','pasien.*','pasien.id as id_pasien','poli.*','icd.*','dokter.*','diagnosa.*','ruang.*')
                    ->where('id_pasien','=',$req["id"])
                    ->get(); 

        $data = [];
        foreach($pasieninap as $info) {
            $data[] = [
                'id_transaksi_rawat_inap' => $info->id_transaksi_rawat_inap,
                'tanggal_mutasi' => $info->tanggal_mutasi,
                'status_rawat_inap' => $info->status_rawat_inap,
                'nama_ruang' => $info->nama_ruang,
                'tanggal_lahir' => $info->tanggal_lahir,
            ];
        }
        return Datatables::of($data)
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function rekmedTransaksiJalanJSON(Request $req) {
        $pasienjalan = DB::table('daftar_rawat_jalan')
                        ->join('pasien','daftar_rawat_jalan.id_pasien','=','pasien.id')
                        ->join('poli','daftar_rawat_jalan.id_poli', '=', 'poli.id')
                        ->join('dokter','daftar_rawat_jalan.id_dokter','=','dokter.id')
                        ->join('diagnosa','daftar_rawat_jalan.id_diagnosa','=','diagnosa.id')
                        ->join('icd','daftar_rawat_jalan.id_icd','=','icd.id')
                        ->select('daftar_rawat_jalan.*', 'daftar_rawat_jalan.id as id_rawat_jalan', 'pasien.*', 'pasien.id as id_pasien','poli.*','diagnosa.*')
                        ->where('daftar_rawat_jalan.id','=' ,$req['id'])
                        ->get();

        $data = [];
        foreach($pasienjalan as $info) {
            $data[] = [
                'tanggal_kunjungan' => $info->tanggal_kunjungan,
                // 'status_rawat_inap' => $info->status_rawat_inap,
                'nama_poli' => $info->nama_poli,
                'alasan_diagnosa' => $info->alasan_diagnosa,
            ];
        }
        return Datatables::of($data)
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function rekmedPasienJSON(Request $req) {
        $rekamMedis = DB::table('rekam_medis')
                    ->join('pasien','rekam_medis.id_pasien','=','pasien.id')
                    ->join('dokter','rekam_medis.id_dokter','=','dokter.id')
                    ->join('icd','rekam_medis.id_icd','=','icd.id')
                    ->select('rekam_medis.*', 'rekam_medis.id as id_rekam_medis', 'pasien.*', 'dokter.*', 'icd.*')
                    ->where('rekam_medis.id_pasien','=' ,$req['id'])
                    ->get(); 
    
        $data = [];
        foreach($rekamMedis as $rm) {
        $data[] = [
            'tanggal' => $rm->tanggal,
            'nama_dokter' => $rm->nama_dokter,
            'nama_icd' => $rm->nama_icd,
            'jenis_diagnosa' => $rm->jenis_diagnosa,
            'anamesa' => $rm->anamesa,
            'pemeriksaan_fisik' => $rm->pemeriksaan_fisik,
            'pemeriksaan_penunjang' => $rm->pemeriksaan_penunjang,
            'status_rawat' => $rm->status_rawat,
            ];
        }
        return Datatables::of($data)       
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function TindakanMedisInapJSON(Request $req) {
        $tindakanInap = DB::table('transaksi_tindakan_medis_inap')
                    ->join('tindakan','transaksi_tindakan_medis_inap.id_tindakan','=','tindakan.id')
                    ->join('ruang','transaksi_tindakan_medis_inap.id_dokter','=','ruang.id')
                    ->join('dokter','transaksi_tindakan_medis_inap.id_dokter','=','dokter.id')
                    ->join('pasien','transaksi_tindakan_medis_inap.id_pasien','=','pasien.id')
                    ->select('transaksi_tindakan_medis_inap.*', 'transaksi_tindakan_medis_inap.id as id_transaksi_tindakan_medis_inap', 'pasien.*', 'dokter.*', 'ruang.*','tindakan.*')
                    ->where('transaksi_tindakan_medis_inap.id_pasien','=' ,$req['id'])
                    ->get(); 
    
        $data = [];
        foreach($tindakanInap as $rm) {
        $data[] = [
            'tanggal_permintaan' => $rm->tanggal_permintaan,
            'nama_dokter' => $rm->nama_dokter,
            'nama_tindakan' => $rm->nama_tindakan,
            'nama_ruang' => $rm->nama_ruang,
            'status_proses' => $rm->status_proses,
            'jumlah' => $rm->jumlah,
            ];
        }
        return Datatables::of($data)       
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }
    
    public function TindakanMedisJalanJSON(Request $req) {
        $tindakanJalan = DB::table('transaksi_tindakan_medis_jalan')
                    ->join('tindakan','transaksi_tindakan_medis_jalan.id_tindakan','=','tindakan.id')
                    ->join('poli','transaksi_tindakan_medis_jalan.id_dokter','=','poli.id')
                    ->join('dokter','transaksi_tindakan_medis_jalan.id_dokter','=','dokter.id')
                    ->join('pasien','transaksi_tindakan_medis_jalan.id_pasien','=','pasien.id')
                    ->select('transaksi_tindakan_medis_jalan.*', 'transaksi_tindakan_medis_jalan.id as id_transaksi_tindakan_medis_jalan', 'pasien.*', 'dokter.*', 'poli.*','tindakan.*')
                    ->where('transaksi_tindakan_medis_jalan.id_pasien','=' ,$req['id'])
                    ->get(); 
    
        $data = [];
        foreach($tindakanJalan as $rm) {
        $data[] = [
            'tanggal_permintaan' => $rm->tanggal_permintaan,
            'nama_dokter' => $rm->nama_dokter,
            'nama_tindakan' => $rm->nama_tindakan,
            'nama_poli' => $rm->nama_poli,
            'status_proses' => $rm->status_proses,
            'jumlah' => $rm->jumlah,
            ];
        }
        return Datatables::of($data)       
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
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
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $pasien                     = Pasien::find($req->formData[0]["value"]);
        $pasien->nama_pasien        = $req->formData[1]["value"];
        $pasien->no_identitas       = $req->formData[2]["value"];
        $pasien->jenis_kelamin      = $req->formData[3]["value"];
        $pasien->alamat             = $req->formData[4]["value"];
        $pasien->tanggal_kunjungan  = $req->formData[5]["value"];
        $pasien->propinsi           = $req->formData[6]["value"];
        $pasien->kabupaten          = $req->formData[7]["value"];
        $pasien->kecamatan          = $req->formData[8]["value"];
        $pasien->kelurahan          = $req->formData[9]["value"];
        $pasien->golongan_darah     = $req->formData[10]["value"];
        $pasien->status             = $req->formData[11]["value"];
        $pasien->tempat_lahir       = $req->formData[12]["value"];
        $pasien->umur               = $req->formData[13]["value"];
        $pasien->tanggal_lahir      = $req->formData[14]["value"];
        $pasien->pekerjaan          = $req->formData[13]["value"];
        $pasien->pendidikan         = $req->formData[16]["value"];
        $pasien->agama              = $req->formData[17]["value"];
        $pasien->nama_wali          = $req->formData[18]["value"];
      
        $pasien->save();
        
        return $req;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        if ($req->ajax()) {
            return Pasien::destroy($req->id);
         }
    }
}
