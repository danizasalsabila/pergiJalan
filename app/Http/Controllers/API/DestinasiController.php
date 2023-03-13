<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destinasi;
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
                    'data' => $destinasi
                ], 200);
            } else {
                return response ([
                    'status' => 'failed',
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
     */
    public function store(Request $request)
    {
        //
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
