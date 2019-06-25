<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Vehicle;

class VehicleController extends Controller {
    public function index() {
        try {

            $key = 'data-vehicle';
            $checkCache = getCache($key);

            if ($checkCache != null) {
                return responses($checkCache, null);
            }
            $data = Vehicle::select([
                'id',
                'jenis_kendaraan',
                'kapasitas_kendaraan',
                'plat_nomor',
                'tahun_pembuatan',
                'kepemilikan',
                'dibuat_oleh',
                'diubah_oleh',
                'created_at',
                'updated_at',
                'status'
            ])->get();

            setCache($key, $data);
            return responses($data, null);

        } catch (QueryException $th) {
            //throw $th;
            return \errorCustomStatus(500, $th);
        }
    }

    public function show($id) {
        try {

            $key = 'data-vehicle-'.$id;
            $checkCache = getCache($key);

            if ($checkCache != null) {
                return responses($checkCache, 200);
            }

            $data = Vehicle::select([
               'jenis_kendaraan',
               'kapasitas_kendaraan',
               'plat_nomor',
               'tahun_pembuatan',
               'nomor_rangka',
               'nomor_lambung',
               'nomor_mesin',
               'stnk',
               'masa_berlaku_stnk',
               'kir',
               'masa_berlaku_kir',
               'base_dc',
               'nomor_kp',
               'masa_berlaku_kp',
               'foto_kendaraan',
               'kepemilikan',
               'nomor_kontrak',
               'is_approved',
               'tanggal_mulai_kontrak',
               'tanggal_berakhir_kontrak'
            ])
            ->where('id', $id)
            ->where('status', 1)
            ->firstOrFail();

            setCache($key, $data);
            return responses($data, 201);
        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

    public function store(Request $request) {
        try {
            $key = 'data-vehicle';
            $this->validate($request, [
                'jenis_kendaraan' => 'required',
                'kapasitas_kendaraan' => 'required',
                'plat_nomor' => 'required',
                'tahun_pembuatan' => 'required',
                'nomor_rangka' => 'required',
                'nomor_lambung' => 'required',
                'nomor_mesin' => 'required',
                'stnk' => 'required',
                'masa_berlaku_stnk' => 'required',
                'kir' => 'required',
                'masa_berlaku_kir' => 'required',
                'base_dc' => 'required',
                'nomor_kp' => 'required',
                'masa_berlaku_kp' => 'required',
                'kepemilikan' => 'required',
            ]);

            Vehicle::insert([
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'kapasitas_kendaraan' => $request->kapasitas_kendaraan,
                'plat_nomor' => $request->plat_nomor,
                'tahun_pembuatan' => $request->tahun_pembuatan,
                'nomor_rangka' => $request->nomor_rangka,
                'nomor_lambung' => $request->nomor_lambung,
                'nomor_mesin' => $request->nomor_mesin,
                'stnk' => $request->stnk,
                'masa_berlaku_stnk' => $request->masa_berlaku_stnk,
                'kir' => $request->kir,
                'masa_berlaku_kir' => $request->masa_berlaku_kir,
                'base_dc' => $request->base_dc,
                'nomor_kp' => $request->nomor_kp,
                'masa_berlaku_kp' => $request->masa_berlaku_kp,
                'foto_kendaraan' => 'https://cdn.jdpower.com/Models/640x480/2018-Toyota-Corolla-LE.jpg',
                'kepemilikan' => $request->kepemilikan,
                'status' => 1,
                'is_approved' => 0,
                'dibuat_oleh' => 1,
                'created_at' => date("Y-m-d",time()),
                'updated_at' => date("Y-m-d",time())
            ]);

            deleteCache($key);
            return responses(['message' => 'Data berhasil disimpan'], null);

        } catch (QueryException $th) {
            return errorCustomStatus(500, $th);
        }
    }

    public function search(Request $request) {
        try {
            $name = $request->name;
            $jenis_kendaraan = $request->jenis_kendaraan;
            $kepemilikan = $request->kepemilikan;

            $query = "";

            if ($jenis_kendaraan != null) {
                $query = $query." AND jenis_kendaraan = ".$jenis_kendaraan;
            }
            if ($kepemilikan != null) {
                $query = $query." AND kepemilikan = '".$kepemilikan."'";
            }

            // print_r($query); die;
            // $data = DB::select("SELECT id, jenis_kendaraan, kapasitas_kendaraan, plat_nomor, tahun_pembuatan, kepemilikan, dibuat_oleh, diubah_oleh, created_at, updated_at, status FROM tm_vehicle WHERE (kapasitas_kendaraan LIKE '%".$name."%' OR plat_nomor LIKE '%".$name."%' OR tahun_pembuatan LIKE '%".$name."%' OR dibuat_oleh LIKE '%".$name."%' OR created_at LIKE '%..$name%') ".$query);
            $datas = Vehicle::select([
                    'id',
                    'jenis_kendaraan',
                    'kapasitas_kendaraan',
                    'plat_nomor',
                    'tahun_pembuatan',
                    'kepemilikan',
                    'dibuat_oleh',
                    'diubah_oleh',
                    'created_at',
                    'updated_at'
                ])
                ->whereRaw("(kapasitas_kendaraan LIKE '%".$name."%' OR plat_nomor LIKE '%".$name."%' OR tahun_pembuatan LIKE '%".$name."%' OR dibuat_oleh LIKE '%".$name."%' OR created_at LIKE '%.$name.%') ".$query)
                ->get();
            return responses($datas, 200);
        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

    public function update($id, Request $request) {
        try {
            $key = 'data-vehicle-'.$id;
            $check = Vehicle::where('id', $id)
                            ->where('status', 1)
                            ->firstOrFail();

            $check->updated_at = date("Y-m-d",time());
            $input = $request->all();
            $check->fill($input)->save();

            deleteCache($key);
            return responses(['message' => 'Data berhasil diupdate'], 200);
        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

    public function delete(Request $request) {
        try {
            $id = $request->id;
            $key = 'data-vehicle-'.$id;

            $check = Vehicle::where('id', $id)
                            ->where('status', 1)
                            ->firstOrFail();

            $check->updated_at = date("Y-m-d",time());
            $check->deleted_at = date("Y-m-d",time());
            $check->deleted_by = $request->deleted_by;
            $input = $request->all();
            $check->fill($input)->save();

            deleteCache($key);
            return responses(['message' => 'Data berhasil didelete'], 200);
        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

    public function approve($id, Request $request) {
        try {
            $key = 'data-vehicle-'.$id;
            $check = Vehicle::where('id', $id)
                            ->where('status', 1)
                            ->firstOrFail();
            $check->is_approved = 1;
            $check->updated_at = date("Y-m-d",time());
            $input = $request->all();
            $check->fill($input)->save();

            deleteCache($key);
            return responses(['message' => 'Data telah diapprove'], null);
        } catch (QueryException $th) {
            return errorCustomStaus(500, $th);
        }
    }

    public function dashboard() {
        try {
            $total_data = Vehicle:: count();
            $total_data_baru = Vehicle::where('status', 1)
                                    ->where('created_at', date("Y-m-d",time()))
                                    ->count();

            $data = [
                'total_data' => $total_data,
                'total_data_baru' => $total_data_baru
            ];
            return responses($data, null);

        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }
}
