<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    public function index()
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => Siswa::all()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching students: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data siswa.'], 500);
        }
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'kelas' => [
                'required',
                'string',
                'max:10',
                'regex:/^(X|XI|XII)\s+(IPA|IPS|BAHASA)\s+[1-9][0-9]?$/'
            ],
            'umur' => 'required|integer|between:6,18',
        ], [
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi',
            'kelas.regex' => 'Format kelas tidak valid. Contoh format yang benar: "XII IPA 1"',
            'umur.between' => 'Umur harus berada dalam rentang 6 hingga 18 tahun'
        ]);

        try {
            // Simpan data siswa
            $siswa = Siswa::create($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Data siswa berhasil ditambahkan',
                'data' => $siswa
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating student: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan data siswa.'], 500);
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
            // Cari data siswa berdasarkan ID
            $siswa = Siswa::findOrFail($id);

            // Validasi data update
            $validatedData = $request->validate([
                'nama' => 'sometimes|required|string|max:255|regex:/^[\pL\s]+$/u',
                'kelas' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:10',
                    'regex:/^(X|XI|XII)\s+(IPA|IPS|BAHASA)\s+[1-9][0-9]?$/'
                ],
                'umur' => 'sometimes|required|integer|between:6,18',
            ]);

            // Update data siswa
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

    public function destroy($id)
    {
        try {
            // Cari dan hapus data siswa
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
