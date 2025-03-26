<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BalitaController extends Controller
{
    /**
     * mendapatkan daftar data balita
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $balita = [
            [
                'nama' => 'Aldi',
                'berat_badan' => 12,
                'tinggi_badan' => 85,
                'tanggal_ukur' => '2025-03-26'
            ],
            [
                'nama' => 'Aida',
                'berat_badan' => 10,
                'tinggi_badan' => 78,
                'tanggal_ukur' => '2025-03-25'
            ],
            [
                'nama' => 'Citra',
                'berat_badan' => 11,
                'tinggi_badan' => 82,
                'tanggal_ukur' => '2025-03-24'
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Daftar data balita berhasil diambil',
            'data' => $balita
        ], 200);
    }

    /**
     * Mendapatkan detail data balita berdasarkan indeks
     * 
     * @param int 
     * @return JsonResponse
     */
    public function show($id)
    {
        $balita = [
            1 => [
                'nama' => 'Aldi',
                'berat_badan' => 12,
                'tinggi_badan' => 85,
                'tanggal_ukur' => '2025-03-26'
            ],
            2 => [
                'nama' => 'Aida',
                'berat_badan' => 10,
                'tinggi_badan' => 78,
                'tanggal_ukur' => '2025-03-25'
            ],
            3 => [
                'nama' => 'Citra',
                'berat_badan' => 11,
                'tinggi_badan' => 82,
                'tanggal_ukur' => '2025-03-24'
            ]
        ];

        if (!isset($balita[$id])) {
            return response()->json([
                'success' => false,
                'message' => 'Data balita tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data balita berhasil diambil',
            'data' => $balita[$id]
        ], 200);
    }

}
