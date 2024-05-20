<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PosisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->input('q');

        $posisi = Position::where('name', 'like', "%$data%")->get();

        return response()->json([
            'status' => true,
            'message' => 'List Data Posisi',
            'data' => $posisi
        ], 200);
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
        $data = $request->validate([
            'name' => 'required',
        ]);

        try {
            $posisi = Position::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menambahkan Data Posisi',
                'data' => $posisi
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data Posisi'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $posisi)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data Posisi',
            'data' => $posisi
        ]);
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
    public function update(Request $request, Position $posisi)
    {
        $data = $request->validate([
            'name' => 'string',
        ]);

        try {
            $posisi->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Update Data Posisi',
                'data' => $posisi
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data Posisi'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $posisi)
    {
        $posisi->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Hapus Data Posisi'
        ]);
    }
}
