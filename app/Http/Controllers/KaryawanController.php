<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->input('q');

        $karyawan = Karyawan::where('nama_lengkap', 'like', "%$data%")->get();

        return response()->json([
            'status' => true,
            'message' => 'List Data Karyawan',
            'data' => $karyawan
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
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama_lengkap' => 'required|string|max:255',
                'nik' => 'required|string|max:255',
                'alamat' => 'required|string',
                'tgl_lahir' => 'required|date',
                'tempat_lahir' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'nomor_telepon' => 'required|string|max:255',
                'avatar' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Buat data karyawan
            $karyawan = new Karyawan();
            $karyawan->nama_lengkap = $request->nama_lengkap;
            $karyawan->nik = $request->nik;
            $karyawan->alamat = $request->alamat;
            $karyawan->tgl_lahir = $request->tgl_lahir;
            $karyawan->tempat_lahir = $request->tempat_lahir;
            $karyawan->jenis_kelamin = $request->jenis_kelamin;
            $karyawan->nomor_telepon = $request->nomor_telepon;

            // Jika ada file avatar di-upload
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '.' . $avatar->getClientOriginalExtension();
                $avatar->storeAs('avatars', $filename);
                $karyawan->avatar = $filename;
            }

            $karyawan->save();

            return response()->json(['message' => 'Karyawan berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            // Tangani kesalahan
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan karyawan'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data Karyawan',
            'data' => $karyawan
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
    public function update(Request $request, Karyawan $karyawan)
    {
        $data = $request->validate([
            'nama_lengkap' => 'string',
            'nik' => 'string',
            'alamat' => 'string',
            'tgl_lahir' => 'string',
            'tempat_lahir' => 'string',
            'jenis_kelamin' => 'string',
            'nomor_telepon' => 'string',
            'avatar' => 'nullable',
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('avatars', $filename);
            $karyawan->avatar = $filename;
        }

        try {
            $karyawan->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Update Data Karyawan',
                'data' => $karyawan
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data Karyawan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Hapus Data Karyawan'
        ]);
    }
}
