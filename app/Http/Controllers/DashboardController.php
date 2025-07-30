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
        ->join('balitas', 'penimbangans.balita_id', '=', 'balitas.id')
        ->selectRaw("DATE_FORMAT(tanggal_timbang, '%Y-%m') as bulan, status_gizi, status_stunting, jenis_kelamin, COUNT(*) as jumlah")
        ->whereNotNull('status_gizi')
        ->whereNotNull('status_stunting')
        ->groupBy('bulan', 'status_gizi', 'status_stunting', 'jenis_kelamin')
        ->orderBy('bulan')
        ->get();

    $all_bulan = collect($data)->pluck('bulan')->unique()->sort()->values()->toArray();
    $all_gender = collect($data)->pluck('jenis_kelamin')->unique()->values()->toArray();

    $groupedGizi = [];
    $groupedStunting = [];

    foreach ($data as $item) {
        $groupedGizi[$item->status_gizi][$item->jenis_kelamin][$item->bulan] = $item->jumlah;
        $groupedStunting[$item->status_stunting][$item->jenis_kelamin][$item->bulan] = $item->jumlah;
    }

    $datasetsGizi = [];
    $datasetsStunting = [];

    foreach ($all_gender as $jk) {
        $dataGizi = [];
        $dataStunting = [];

        foreach ($all_bulan as $bulan) {
            $dataGizi[] = ($groupedGizi['Gizi Baik'][$jk][$bulan] ?? 0)
                + ($groupedGizi['Gizi Kurang'][$jk][$bulan] ?? 0)
                + ($groupedGizi['Risiko Gizi Lebih'][$jk][$bulan] ?? 0);

            $dataStunting[] = ($groupedStunting['Pendek'][$jk][$bulan] ?? 0)
                + ($groupedStunting['Sangat Pendek'][$jk][$bulan] ?? 0);
        }

        $datasetsGizi[] = [
            'label' => 'Gizi - ' . $jk,
            'data' => $dataGizi,
            'backgroundColor' => $jk == 'Laki-laki' ? '#4e73df' : '#f6c23e'
        ];

        $datasetsStunting[] = [
            'label' => 'Stunting - ' . $jk,
            'data' => $dataStunting,
            'backgroundColor' => $jk == 'Laki-laki' ? '#e74a3b' : '#1cc88a'
        ];
    }

    $chartGizi = [
        'labels' => $all_bulan,
        'datasets' => $datasetsGizi
    ];

    $chartStunting = [
        'labels' => $all_bulan,
        'datasets' => $datasetsStunting
    ];

    return view('dashboard', compact('jumlahBalita', 'jumlahMasuk', 'jumlahKeluar', 'saldo', 'chartGizi', 'chartStunting'));
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
