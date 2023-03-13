<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destinasi;
use DateTime;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\DB;


class DestinasiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     * 
     */
    public function index()
    {
        //Destinasi
        // $data = Destinasi::all();
        // if($data) {
        //     return ApiFormatter::createApi(200, 'Success', $data);
        // } else {
        //     return ApiFormatter::createApi(400, 'Failed');
        // }
            $destinasi = DB::table('destinasi')->orderBy('id','desc')->get();

            if($destinasi != null) {
                return response ([
                    'status' => 'Data destinasi berhasil ditampilkan',
                    'code' => 200,
                    'data' => $destinasi
                ], 200);
            } else {
                return response ([
                    'status' => 'failed',
                    'code' => 404,
                    'message' => 'Data destinasi tidak ditemukan'
                ], 404);
            }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $dt = new DateTime();
        $tahun = $dt->format('y');
        $bulan = $dt->format('m');

        $requestDestinasi = [
            'name_destinasi' => $request->name_destinasi,
            'address' => $request->address,
            'city' => $request->city,
            'category' => $request->category,
            'created_at' => $dt

        ];
        

        $createDestinasi = DB::table('destinasi')->insert($requestDestinasi);

        if($createDestinasi != null){
            return response([
                'status' => 'success',
                'message' => 'Destinasi Berhasil Ditambahkan',
                'data' => $requestDestinasi
            ], 200);
        } else {
            return response ([
                'status' => 'failed',
                'message' => 'Destinasi gagal Ditambahkan'
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
