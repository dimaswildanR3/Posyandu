<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jumlahMasuk = Keuangan::sum('pemasukan');
        $jumlahKeluar = Keuangan::sum('pengeluaran');
        $saldo = $jumlahMasuk - $jumlahKeluar;
        $jumlahBalita = Balita::count();
    
        $data = DB::table('penimbangans')
            ->selectRaw("DATE_FORMAT(tanggal_timbang, '%Y-%m') as bulan, status_gizi, COUNT(*) as jumlah")
            ->whereNotNull('status_gizi')
            ->groupBy('bulan', 'status_gizi')
            ->orderBy('bulan')
            ->get();
    
        $all_bulan = collect($data)->pluck('bulan')->unique()->sort()->values()->toArray();
        $all_kategori = collect($data)->pluck('status_gizi')->unique()->values()->toArray();
    
        // Susun data indexed: status_gizi -> bulan -> jumlah
        $grouped = [];
        foreach ($data as $item) {
            $grouped[$item->status_gizi][$item->bulan] = $item->jumlah;
        }
    
        $colorList = [
            'Gizi Baik' => '#1cc88a',
            'Gizi Kurang' => '#e74a3b',
            'Risiko Gizi Lebih' => '#f6c23e',
            'Stunting' => '#4e73df'
        ];
    
        // Buat dataset per bulan, dengan data array jumlah anak per kategori status_gizi
        $datasets = [];
        foreach ($all_bulan as $bulan) {
            $dataPerKategori = [];
            foreach ($all_kategori as $kategori) {
                $dataPerKategori[] = $grouped[$kategori][$bulan] ?? 0;
            }
            $datasets[] = [
                'label' => $bulan,
                'data' => $dataPerKategori,
                'backgroundColor' => '#' . substr(md5($bulan), 0, 6) // warna unik per bulan
            ];
        }
    
        $chartData = [
            'labels' => $all_kategori, // ini sumbu Y (kategori status gizi)
            'datasets' => $datasets
        ];
    
        return view('dashboard', compact('jumlahBalita', 'jumlahMasuk', 'jumlahKeluar', 'saldo', 'chartData', 'colorList'));
    }
    
        
    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
