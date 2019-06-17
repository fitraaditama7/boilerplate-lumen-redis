<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\CRO;

class CROController extends Controller {
    public function index() {
        try {
            //code...
            $data = CRO::select([
                    'nik',
                    'nama',
                    'alamat',
                    'umur',
                    'tgl_lahir',
                    'tgl_gabung',
                    'status'
                ])->get();
            return responses($data, null);
        } catch (QueryException $th) {
            return errorCustomStatus(500, $th);
        }
    }

    public function show($id) {
        try {
            $data = CRO::select([
                'nik',
                'nama',
                'alamat',
                'umur',
                'tgl_lahir',
                'tgl_gabung',
                'status'
            ])
            ->where('id', $id)
            ->where('deleted_At', null)
            ->get();

            return responses($data, null);
        } catch (QueryException $th) {
            return errorCustomStatus(500, $th);
        }
    }

    public function store(Request $request) {
        try {

            $this->validate($request,[
                'nik' => 'required',
                'nama' => 'required',
                'alamat' => 'required',
                'tgl_gabung' => 'required'
            ]);

            CRO::insert([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'umur' => $request->umur,
                'tgl_lahir' => $request->tgl_lahir,
                'tgl_gabung' => $request->tgl_gabung,
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s",time()),
                'updated_at' => date("Y-m-d H:i:s",time()),
            ]);
            return responses(['message' => 'Data berhasil disimpan'], 201);

        } catch (QueryException $th) {
            return errorCustomStatus(500, $th);
        }
    }

    public function update($id, Request $request) {
        try {
            $check = CRO::where('deleted_at', null)
                        ->where('id', $id)
                        ->firstOrFail();
            $check->updated_at = date("Y-m-d H:i:s",time());
            $input = $request->all();
            $check->fill($input)->save();

            return responses(['messsage' => 'Data Berhasil diupdate'], null);

        } catch (QueryException $th) {
            return errorCustomStatus(500, $th);
        }
    }

    public function softDelete($id) {
        try {
            $check = CRO::where('deleted_at', null)
                        ->where('id', $id)
                        ->firstOrFail();
            $check->deleted_at = date("Y-m-d H:i:s",time());
            $check->updated_at = date("Y-m-d H:i:s",time());
            $check->save();

            return responses(['message' => 'Data Berhasil dihapus'], null);

        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

    public function restore($id) {
        try {
            $check = CRO::where('deleted_at', '!=', null)
                        ->where('id', $id)
                        ->firstOrFail();
            $check->deleted_at = null;
            $check->updated_at = date("Y-m-d H:i:s",time());
            $check->save();

            return \responses(['message' => 'Data Berhasil direstore'], null);
        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

    public function destroy($id) {
        try {
            $check = CRO::where('deleted_at', '!=', null)
                        ->where('id', $id)
                        ->firstOrFail();
            $check->delete();

            return \responses(['message' => 'Data Berhasil dihapus'], null);
        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

}
