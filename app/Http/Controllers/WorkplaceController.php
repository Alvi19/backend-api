<?php

namespace App\Http\Controllers;

use App\Models\Workplace;
use Illuminate\Http\Request;

class WorkplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->input('q');

        $posisi = Workplace::where('name', 'like', "%$data%")->get();

        return response()->json([
            'status' => true,
            'message' => 'List Data Workplace',
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
            'alamat' => 'required',
            'kota' => 'required',
            'negara' => 'required',
        ]);

        try {
            $workplace = Workplace::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menambahkan Data Tempat Bekerja',
                'data' => $workplace
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data Tempat Bekerja'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Workplace $workplace)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data Workplace',
            'data' => $workplace
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
    public function update(Request $request, Workplace $workplace)
    {
        $data = $request->validate([
            'name' => 'string',
            'alamat' => 'string',
            'kota' => 'string',
            'negara' => 'string',
        ]);

        try {
            $workplace->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Update Data Workplace',
                'data' => $workplace
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data Workplace'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workplace $workplace)
    {
        $workplace->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Hapus Data Workplace'
        ]);
    }
}
