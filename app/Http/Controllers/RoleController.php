<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->input('q');

        $role = Role::where('name', 'like', "%$data%")->get();

        return response()->json([
            'status' => true,
            'message' => 'List Data Role',
            'data' => $role
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
            $role = Role::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menambahkan Data Role',
                'data' => $role
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data Role'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data Role',
            'data' => $role
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
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'string',
        ]);

        try {
            $role->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Update Data Role',
                'data' => $role
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data Role'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Hapus Data Role'
        ]);
    }
}
