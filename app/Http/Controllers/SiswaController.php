<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Siswa;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return siswa::all();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "error" => 'Gagal mengambil data siwa'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:10',
            'umur' => 'required|integer',
        ]);
        try {
            $siswa = Siswa::create($validateData);
            return response()->json($siswa, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan data'
            ], 500);

        }
    }

    public function show($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $siswa
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching student: ' . $e->getMessage());
            return response()->json(['error' => 'Siswa tidak ditemukan.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);

            $validatedData = $request->validate([
                'nama' => 'sometimes|required|string|max:255',
                'kelas' => 'sometimes|required|string|max:10',
                'umur' => 'sometimes|required|integer',
            ]);

            $siswa->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Data siswa berhasil diperbarui',
                'data' => $siswa
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memperbarui data siswa.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data siswa berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting student: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus data siswa.'], 500);
        }
    }
}

