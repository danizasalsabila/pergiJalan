<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AdminTransaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admintransaction = AdminTransaction::with('eticket')->orderBy('id', 'desc')->get();
        if ($admintransaction->count() > 0) {
            return response([
                'status' => 'success',
                'message' => 'Biaya Admin Berhasil ditampilkan',
                'data' => $admintransaction
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'Biaya Admin gagal ditampilkan'
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
