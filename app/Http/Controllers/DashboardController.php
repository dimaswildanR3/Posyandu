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
    public function index(Request $request)
    {
        $jumlahMasuk = Keuangan::sum('pemasukan');
        $jumlahKeluar = Keuangan::sum('pengeluaran');
        $saldo = $jumlahMasuk - $jumlahKeluar;
        $jumlahBalita = Balita::count();
    
        // Ambil filter dari request untuk grafik Gizi dan Stunting
        $genderGizi = $request->get('gender_gizi');       // 'Laki-laki', 'Perempuan', or null
        $genderStunting = $request->get('gender_stunting'); // 'Laki-laki', 'Perempuan', or null
    
        // Query untuk Grafik Gizi
        $queryGizi = DB::table('penimbangans')
            ->join('balitas', 'penimbangans.balita_id', '=', 'balitas.id')
            ->selectRaw("DATE_FORMAT(tanggal_timbang, '%Y-%m') as bulan, status_gizi, jenis_kelamin, COUNT(*) as jumlah")
            ->whereNotNull('status_gizi');
    
        if ($genderGizi && in_array($genderGizi, ['Laki-laki', 'Perempuan'])) {
            $queryGizi->where('jenis_kelamin', $genderGizi);
        }
    
        $dataGizi = $queryGizi
            ->groupBy('bulan', 'status_gizi', 'jenis_kelamin')
            ->orderBy('bulan')
            ->get();
    
        // Query untuk Grafik Stunting
        $queryStunting = DB::table('penimbangans')
            ->join('balitas', 'penimbangans.balita_id', '=', 'balitas.id')
            ->selectRaw("DATE_FORMAT(tanggal_timbang, '%Y-%m') as bulan, status_stunting, jenis_kelamin, COUNT(*) as jumlah")
            ->whereNotNull('status_stunting');
    
        if ($genderStunting && in_array($genderStunting, ['Laki-laki', 'Perempuan'])) {
            $queryStunting->where('jenis_kelamin', $genderStunting);
        }
    
        $dataStunting = $queryStunting
            ->groupBy('bulan', 'status_stunting', 'jenis_kelamin')
            ->orderBy('bulan')
            ->get();
    
        // Prepare chart data for Gizi
        $all_bulan_gizi = collect($dataGizi)->pluck('bulan')->unique()->sort()->values()->toArray();
        $all_status_gizi = ['Gizi Buruk', 'Gizi Kurang', 'Gizi Normal', 'Gizi Lebih']; // urut sesuai permintaan
        $all_gender_gizi = collect($dataGizi)->pluck('jenis_kelamin')->unique()->values()->toArray();
    
        $groupedGizi = [];
        foreach ($dataGizi as $item) {
            $groupedGizi[$item->status_gizi][$item->jenis_kelamin][$item->bulan] = $item->jumlah;
        }
    
        $datasetsGizi = [];
        $colorsGizi = [
            'Gizi Buruk' => '#e74a3b',  // merah
            'Gizi Kurang' => '#f6c23e', // kuning
            'Gizi Normal' => '#1cc88a', // hijau
            'Gizi Lebih' => '#000000'   // hitam
        ];
    
        foreach ($all_status_gizi as $status) {
            foreach ($all_gender_gizi as $jk) {
                $data = [];
                foreach ($all_bulan_gizi as $bulan) {
                    $data[] = $groupedGizi[$status][$jk][$bulan] ?? 0;
                }
                $datasetsGizi[] = [
                    'label' => $status . ' - ' . $jk,
                    'data' => $data,
                    'backgroundColor' => $colorsGizi[$status],
                ];
            }
        }
    
        $chartGizi = [
            'labels' => $all_bulan_gizi,
            'datasets' => $datasetsGizi,
        ];
    
        // Prepare chart data for Stunting
        $all_bulan_stunting = collect($dataStunting)->pluck('bulan')->unique()->sort()->values()->toArray();
        $all_status_stunting = ['Stunting Berat', 'Risiko Stunting', 'Normal', 'Tinggi'];
        $all_gender_stunting = collect($dataStunting)->pluck('jenis_kelamin')->unique()->values()->toArray();
    
        $groupedStunting = [];
        foreach ($dataStunting as $item) {
            $groupedStunting[$item->status_stunting][$item->jenis_kelamin][$item->bulan] = $item->jumlah;
        }
    
        $datasetsStunting = [];
        $colorsStunting = [
            'Stunting Berat' => '#e74a3b',      // merah
            'Risiko Stunting' => '#f6c23e',    // kuning
            'Normal' => '#1cc88a',              // hijau
            'Tinggi' => '#000000'               // hitam
        ];
    
        foreach ($all_status_stunting as $status) {
            foreach ($all_gender_stunting as $jk) {
                $data = [];
                foreach ($all_bulan_stunting as $bulan) {
                    $data[] = $groupedStunting[$status][$jk][$bulan] ?? 0;
                }
                $datasetsStunting[] = [
                    'label' => $status . ' - ' . $jk,
                    'data' => $data,
                    'backgroundColor' => $colorsStunting[$status],
                ];
            }
        }
    
        $chartStunting = [
            'labels' => $all_bulan_stunting,
            'datasets' => $datasetsStunting,
        ];
    
        return view('dashboard', compact(
            'jumlahBalita', 'jumlahMasuk', 'jumlahKeluar', 'saldo',
            'chartGizi', 'chartStunting',
            'genderGizi', 'genderStunting'
        ));
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
