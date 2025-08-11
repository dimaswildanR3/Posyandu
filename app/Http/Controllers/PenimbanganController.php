<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\Balita;
use App\Models\Jadwal;
use App\Models\Imunisasi;
use App\Models\Penimbangan;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenimbanganController extends Controller
{
    public function index()
    {
        $dari = '';
        $sampai = '';
        $chart = [];
        $tinggiBadan = [];
        $beratBadan = [];
    
        $user = Auth::user();
        // $orangTua = OrangTua::where('user_id', $user->id)->first();
    
        if ($user->role === 'ortu') {
            $balita = Balita::where('user_id', $user->id)->get();
            $balitaIds = $balita->pluck('id');
    
            $timbangan = Penimbangan::with('balita', 'user')
                ->whereIn('balita_id', $balitaIds)
                ->orderBy('tanggal_timbang', 'DESC')
                ->paginate(10);
    
            $jenisKelaminLaki = $balita->where('jenis_kelamin', 'Laki-laki')->count();
            $jenisKelaminPerem = $balita->where('jenis_kelamin', 'Perempuan')->count();
        } else {
            $balita = Balita::all();
            $timbangan = Penimbangan::with('balita', 'user')->orderBy('tanggal_timbang', 'DESC')->paginate(10);
            $jenisKelaminLaki = Balita::where('jenis_kelamin', 'Laki-laki')->count();
            $jenisKelaminPerem = Balita::where('jenis_kelamin', 'Perempuan')->count();
        }
    
        // Tambahan: augmentasi data per item dengan perhitungan WHO BB/U dan TB/U
        foreach ($timbangan as $item) {
            $jenisKelaminSingkat = $item->balita->jenis_kelamin;
            $umur = $item->umur;
    
            // BB/U
            $refBBU = $this->getBbuReference($umur, $jenisKelaminSingkat);
            $item->median_bbu = $refBBU['median'];
            $item->sd_bbu = $refBBU['sd'];
            $item->z_score_bbu = ($item->bb - $item->median_bbu) / $item->sd_bbu;
    
            // Kategori status gizi BB/U
            if ($item->z_score_bbu < -3) {
                $item->status_gizi_bbu = 'Gizi Buruk';
            } elseif ($item->z_score_bbu >= -3 && $item->z_score_bbu < -2) {
                $item->status_gizi_bbu = 'Gizi Kurang';
            } elseif ($item->z_score_bbu >= -2 && $item->z_score_bbu <= 1) {
                $item->status_gizi_bbu = 'Gizi Normal / Baik';
            } elseif ($item->z_score_bbu > 1 && $item->z_score_bbu <= 2) {
                $item->status_gizi_bbu = 'Gizi Lebih';
            } else {
                $item->status_gizi_bbu = 'Gizi Lebih';
            }
    
            // TB/U
            $refTBU = $this->getTbuReference($umur, $jenisKelaminSingkat);
            $item->median_tbu = $refTBU['median'];
            $item->sd_tbu = $refTBU['sd'];
            $item->z_score_tbu = ($item->tb - $item->median_tbu) / $item->sd_tbu;
    
            // Kategori status stunting TB/U
            if ($item->z_score_tbu < -3) {
                $item->status_stunting = 'Stunting Berat';
            } elseif ($item->z_score_tbu >= -3 && $item->z_score_tbu < -2) {
                $item->status_stunting = 'Risiko Stunting';
            } elseif ($item->z_score_tbu >= -2 && $item->z_score_tbu <= 1) {
                $item->status_stunting = 'Normal';
            } elseif ($item->z_score_tbu > 1 && $item->z_score_tbu <= 2) {
                $item->status_stunting = 'Risiko Tinggi';
            } else {
                $item->status_stunting = 'Tinggi';
            }
        }
    
        foreach ($timbangan as $mp) {
            $chart[] = $mp->balita->nama_balita;
            $beratBadan[] = $mp->bb;
            $tinggiBadan[] = $mp->tb;
        }
    
        $laki[] = $jenisKelaminLaki;
        $perem[] = $jenisKelaminPerem;
    
        $tanggalPelayanan = Jadwal::all();
        // Ambil penimbangan dengan eager loading imunisasi terakhir per balita
$timbangan = Penimbangan::with(['balita.imunisasis' => function ($query) {
    $query->orderBy('tanggal_imunisasi', 'desc')->limit(1);
}, 'user'])
->orderBy('tanggal_timbang', 'DESC')
->paginate(10);

    
        return view('timbangan.index', compact(
            'timbangan',
            'balita',
            'chart',
            'tinggiBadan',
            'beratBadan',
            'laki',
            'perem',
            'tanggalPelayanan',
            'dari',
            'sampai'
        ));
    }
    


    public function periodeTimbang(Request $request){

        $balita = Balita::all();
        $filterTanggal = Penimbangan::all();
        $dari = $request->dari;
        $sampai = $request->sampai;
        // $keuangan = Keuangan::paginate(10);
        $timbangan =Penimbangan::with('balita','user')->whereDate('tanggal_timbang','>=',$dari)->whereDate('tanggal_timbang','<=',$sampai)->orderBy('tanggal_timbang','desc')->paginate(10);
        $tanggalPelayanan = Jadwal::all();
        $chart = [];
        $tinggiBadan = [];
        $beratBadan = [];
        foreach($timbangan as $mp){
            $chart[]= $mp->balita->nama_balita;
            $beratBadan[]= $mp->bb;
            $tinggiBadan[]= $mp->tb;
        }

        $jenisKelaminLaki = Balita::where('jenis_kelamin','Laki-laki')->get();
        $laki[] = count($jenisKelaminLaki);
        
        $jenisKelaminPerem = Balita::where('jenis_kelamin','Perempuan')->get();
        $perem[] = count($jenisKelaminPerem);
        $tanggalPelayanan = Jadwal::all();
        return view('timbangan.index',compact(
            'timbangan',
            'balita',
            'chart',
            'tinggiBadan',
            'beratBadan',
            'laki',
            'perem',
            'tanggalPelayanan',
            'dari',
            'sampai'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
{
    $tanggalPelayanan = Jadwal::all();
    $balita = Balita::all();

    return view('timbangan.create', compact('tanggalPelayanan', 'balita'));
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'user_id' => 'required|integer',
            'tanggal_timbang' => 'required|date',
        ]);
    
        $balita = Balita::findOrFail($request->balita_id);
        $tanggalLahir = Carbon::parse($balita->tgl_lahir);
        $tanggalTimbang = Carbon::parse($request->tanggal_timbang);
    
        // Hitung umur dalam bulan
        $umur = $tanggalLahir->diffInMonths($tanggalTimbang);
    
        $jenisKelamin = $balita->jenis_kelamin; // 'L' atau 'P'

        $zScoreBBU = $this->hitungZScoreBBU($umur, $request->bb, $jenisKelamin);
        $statusGizi = $this->hitungStatusGizi($zScoreBBU);
        
        $zScoreTBU = $this->hitungZScoreTBU($umur, $request->tb, $jenisKelamin);
        $statusStunting = $this->hitungStatusStunting($zScoreTBU);
        
        Penimbangan::create([
            'balita_id' => $request->balita_id,
            'bb' => $request->bb,
            'tb' => $request->tb,
            'user_id' => $request->user_id,
            'tanggal_timbang' => $request->tanggal_timbang,
            'umur' => $umur,
            'status_gizi' => $statusGizi,
            'z_score' => $zScoreBBU,
            'status_stunting' => $statusStunting,
            'z_score_stunting' => $zScoreTBU,
            'catatan' => $request->catatan,
            'acara_kegiatan' => $request->acara_kegiatan,
        ]);
        
    
        return redirect('/penimbangan')->with('status', 'Data Penimbangan berhasil ditambahkan!');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function show($id)
{
    // Ambil semua penimbangan untuk 1 balita ini (bukan hanya 1 ID penimbangan)
    $dataPenimbangan = Penimbangan::with('balita')
        ->where('balita_id', Penimbangan::find($id)->balita_id)
        ->orderBy('tanggal_timbang', 'asc')
        ->get();

    $balita = $dataPenimbangan->first()->balita ?? null;

    $tanggal = $dataPenimbangan->pluck('tanggal_timbang')->map(function ($tgl) {
        return \Carbon\Carbon::parse($tgl)->format('d M Y');
    })->toArray();

   $beratBadan = $dataPenimbangan->pluck('bb')->map(function ($bb) {
    return (float) $bb;
})->toArray();

$tinggiBadan = $dataPenimbangan->pluck('tb')->map(function ($tb) {
    return (float) $tb;
})->toArray();


    return view('timbangan.detail', compact('dataPenimbangan', 'tanggal', 'beratBadan', 'tinggiBadan', 'balita'));
}


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Penimbangan $penimbangan)
    {
        $balita = Balita::all();
        return view('timbangan.edit',compact('penimbangan','balita'));
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
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'tanggal_timbang' => 'required|date',
        ]);
    
        $balita = Balita::findOrFail($request->balita_id);
        $tanggalLahir = Carbon::parse($balita->tgl_lahir);
        $tanggalTimbang = Carbon::parse($request->tanggal_timbang);
    
        $umur = $tanggalLahir->diffInMonths($tanggalTimbang);
        $jenisKelamin = $balita->jenis_kelamin;  // Pastikan ambil jenis kelamin
        $zScoreBBU = $this->hitungZScoreBBU($umur, $request->bb, $jenisKelamin);
        $statusGizi = $this->hitungStatusGizi($zScoreBBU);
        
        $zScoreTBU = $this->hitungZScoreTBU($umur, $request->tb, $jenisKelamin);
        $statusStunting = $this->hitungStatusStunting($zScoreTBU);
        
        Penimbangan::create([
            'balita_id' => $request->balita_id,
            'bb' => $request->bb,
            'tb' => $request->tb,
            'user_id' => $request->user_id,
            'tanggal_timbang' => $request->tanggal_timbang,
            'umur' => $umur,
            'status_gizi' => $statusGizi,
            'z_score' => $zScoreBBU,
            'status_stunting' => $statusStunting,
            'z_score_stunting' => $zScoreTBU,
            'catatan' => $request->catatan,
            'acara_kegiatan' => $request->acara_kegiatan,
        ]);
        
    
        return redirect('/penimbangan')->with('status', 'Data Penimbangan berhasil diupdate!');
    }
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Penimbangan::destroy($id);
        return redirect('/penimbangan')->with('status','Data Penimbangan berhasil dihapus!');
    }

    private function getBbuReference($umur, $jenis_kelamin)
    {
        $tabel = [
            'Laki-Laki' => [
                0 => ['median' => 3.3, 'sd' => 0.5],
                6 => ['median' => 7.9, 'sd' => 0.9],
                12 => ['median' => 9.2, 'sd' => 1.1],
                18 => ['median' => 10.5, 'sd' => 1.2],
                24 => ['median' => 11.8, 'sd' => 1.3],
                30 => ['median' => 13.3, 'sd' => 1.4],
                36 => ['median' => 14.3, 'sd' => 1.5],
                42 => ['median' => 15.2, 'sd' => 1.6],
                48 => ['median' => 16.3, 'sd' => 1.6],
                54 => ['median' => 17.2, 'sd' => 1.7],
                60 => ['median' => 18.3, 'sd' => 1.8],
            ],
            'Perempuan' => [
                0 => ['median' => 3.2, 'sd' => 0.5],
                6 => ['median' => 7.3, 'sd' => 0.8],
                12 => ['median' => 8.9, 'sd' => 1.1],
                18 => ['median' => 10.2, 'sd' => 1.2],
                24 => ['median' => 11.5, 'sd' => 1.3],
                30 => ['median' => 13.0, 'sd' => 1.4],
                36 => ['median' => 14.2, 'sd' => 1.5],
                42 => ['median' => 15.1, 'sd' => 1.6],
                48 => ['median' => 16.2, 'sd' => 1.6],
                54 => ['median' => 17.1, 'sd' => 1.7],
                60 => ['median' => 18.2, 'sd' => 1.8],
            ]
        ];
    
        $umurTerdekat = round($umur / 6) * 6;
        $umurTerdekat = max(0, min(60, $umurTerdekat));
    
        return $tabel[$jenis_kelamin][$umurTerdekat];
    }
    
    private function getTbuReference($umur, $jenis_kelamin)
    {
        // Contoh data WHO TB/U (median & SD)
        $tabel = [
            'Laki-Laki' => [
                0 => ['median' => 49.9, 'sd' => 1.9],
                6 => ['median' => 67.6, 'sd' => 2.5],
                12 => ['median' => 76.1, 'sd' => 2.9],
                18 => ['median' => 81.7, 'sd' => 3.1],
                24 => ['median' => 87.1, 'sd' => 3.3],
                30 => ['median' => 91.9, 'sd' => 3.5],
                36 => ['median' => 96.1, 'sd' => 3.7],
                42 => ['median' => 99.9, 'sd' => 3.9],
                48 => ['median' => 103.3, 'sd' => 4.0],
                54 => ['median' => 106.5, 'sd' => 4.1],
                60 => ['median' => 109.4, 'sd' => 4.2],
            ],
            'Perempuan' => [
                0 => ['median' => 49.1, 'sd' => 1.8],
                6 => ['median' => 65.7, 'sd' => 2.4],
                12 => ['median' => 74.0, 'sd' => 2.8],
                18 => ['median' => 79.7, 'sd' => 3.1],
                24 => ['median' => 85.0, 'sd' => 3.3],
                30 => ['median' => 89.8, 'sd' => 3.5],
                36 => ['median' => 94.1, 'sd' => 3.7],
                42 => ['median' => 98.0, 'sd' => 3.9],
                48 => ['median' => 101.4, 'sd' => 4.0],
                54 => ['median' => 104.7, 'sd' => 4.1],
                60 => ['median' => 107.8, 'sd' => 4.2],
            ]
        ];
    
        $umurTerdekat = round($umur / 6) * 6;
        $umurTerdekat = max(0, min(60, $umurTerdekat));
    
        return $tabel[$jenis_kelamin][$umurTerdekat];
    }
    
    private function hitungZScoreBBU($umur, $bb, $jenis_kelamin)
    {
        $ref = $this->getBbuReference($umur, $jenis_kelamin);
        return round(($bb - $ref['median']) / $ref['sd'], 2);
    }
    
    private function hitungZScoreTBU($umur, $tb, $jenis_kelamin)
    {
        $ref = $this->getTbuReference($umur, $jenis_kelamin);
        return round(($tb - $ref['median']) / $ref['sd'], 2);
    }
    
    private function hitungStatusGizi($z)
    {
        if ($z < -3) {
            return 'Gizi Buruk';
        } elseif ($z >= -3 && $z < -2) {
            return 'Gizi Kurang';
        } elseif ($z >= -2 && $z <= 1) {
            return 'Gizi Normal / Baik';
        } elseif ($z > 1 && $z <= 2) {
            return 'Gizi Lebih';
        } else { // > 2
            return 'Gizi Lebih';
        }
    }
    
    private function hitungStatusStunting($z)
    {
        if ($z < -3) {
            return 'Stunting Berat';
        } elseif ($z >= -3 && $z < -2) {
            return 'Risiko Stunting';
        } elseif ($z >= -2 && $z <= 1) {
            return 'Normal';
        } else { // > 1
            return 'Tinggi';
        }
    }
    
        
    public function kms(Request $request, $balita_id)
    {
        $balita = \App\Models\Balita::findOrFail($balita_id);
    
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');
    
        $query = \App\Models\Penimbangan::where('balita_id', $balita_id);
    
        if ($dari) $query->whereDate('tanggal_timbang', '>=', $dari);
        if ($sampai) $query->whereDate('tanggal_timbang', '<=', $sampai);
    
        $penimbangans = $query->orderBy('tanggal_timbang')->get();
    
        // Ambil langsung umur dari field database
        $umur = $penimbangans->pluck('umur')->toArray();
        $berat = $penimbangans->pluck('bb')->map(fn($bb) => (float)$bb)->toArray();
    
        $maxAge = max($umur) ?? 36;
    
        $severelyUnderweight = [];
        $underweight = [];
        $normal = [];
        $overweight = [];
    
        for ($age = 0; $age <= $maxAge; $age++) {
            $baseWeight = 3.3 + ($age * 0.5);
            $severelyUnderweight[] = ['x' => $age, 'y' => max(2, $baseWeight - 2)];
            $underweight[] = ['x' => $age, 'y' => $baseWeight - 1];
            $normal[] = ['x' => $age, 'y' => $baseWeight + 1.5];
            $overweight[] = ['x' => $age, 'y' => $baseWeight + 3];
        }
    
        return view('timbangan.kms', compact(
            'balita', 'penimbangans', 'dari', 'sampai',
            'umur', 'berat',
            'severelyUnderweight', 'underweight', 'normal', 'overweight'
        ));
    }
    
    public function kms1(Request $request)
    {
        // Ambil user login
        $userId = auth()->id();
    
        // Cari balita milik user ini
        $balita = \App\Models\Balita::where('user_id', $userId)->firstOrFail();
    
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');
    
        // Query penimbangan berdasarkan balita_id
        $query = \App\Models\Penimbangan::where('balita_id', $balita->id);
    
        if ($dari) {
            $query->whereDate('tanggal_timbang', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal_timbang', '<=', $sampai);
        }
    
        $penimbangans = $query->orderBy('tanggal_timbang')->get();
    
        // Ambil umur & berat dari database
        $umur = $penimbangans->pluck('umur')->toArray();
        $berat = $penimbangans->pluck('bb')->map(fn($bb) => (float)$bb)->toArray();
    
      // Gunakan default 36 kalau tidak ada data umur
      $maxAge = !empty($umur) ? max($umur) : 36;
    
        $severelyUnderweight = [];
        $underweight = [];
        $normal = [];
        $overweight = [];
    
        for ($age = 0; $age <= $maxAge; $age++) {
            $baseWeight = 3.3 + ($age * 0.5);
            $severelyUnderweight[] = ['x' => $age, 'y' => max(2, $baseWeight - 2)];
            $underweight[] = ['x' => $age, 'y' => $baseWeight - 1];
            $normal[] = ['x' => $age, 'y' => $baseWeight + 1.5];
            $overweight[] = ['x' => $age, 'y' => $baseWeight + 3];
        }
    
        return view('timbangan.kms', compact(
            'balita', 'penimbangans', 'dari', 'sampai',
            'umur', 'berat',
            'severelyUnderweight', 'underweight', 'normal', 'overweight'
        ));
    }
    
    
    public function filterCetak(Request $request)
{
    return view('penimbangan.index'); // nanti buat view ini
}


public function cetakPdf(Request $request) 
{
    $tahun = $request->tahun;
    $status = $request->status;

    $statusGiziList = ['Normal', 'Gizi Kurang', 'Gizi Buruk', 'Gizi Lebih'];
    $statusStuntingList = ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'];

    $data = Penimbangan::with(['balita.imunisasis' => function ($q) {
        $q->orderBy('tanggal_imunisasi', 'desc')->limit(1);
    }])
    ->when($tahun, fn($q) => $q->whereYear('tanggal_timbang', $tahun))
    ->when($status, function ($q) use ($status, $statusGiziList, $statusStuntingList) {
        if (in_array($status, $statusGiziList)) {
            $q->where('status_gizi', $status);
        } elseif (in_array($status, $statusStuntingList)) {
            $q->where('status_stunting', $status);
        }
    })
    ->orderBy('balita_id')
    ->orderBy('tanggal_timbang')
    ->get()
    ->groupBy('balita_id');

    // Hitung nilai median, sd, z-score, status gizi, status stunting
    foreach ($data as $penimbangans) {
        foreach ($penimbangans as $item) {
            $jk = $item->balita->jenis_kelamin ?? null;
            $umur = $item->umur;

            $refBBU = $this->getBbuReference($umur, $jk);
            $item->median_bbu = $refBBU['median'] ?? 0;
            $item->sd_bbu = $refBBU['sd'] ?? 1;
            $item->z_score_bbu = $item->sd_bbu ? ($item->bb - $item->median_bbu) / $item->sd_bbu : 0;

            if ($item->z_score_bbu < -3) {
                $item->status_gizi_bbu = 'Gizi Buruk';
            } elseif ($item->z_score_bbu >= -3 && $item->z_score_bbu < -2) {
                $item->status_gizi_bbu = 'Gizi Kurang';
            } elseif ($item->z_score_bbu >= -2 && $item->z_score_bbu <= 1) {
                $item->status_gizi_bbu = 'Gizi Normal / Baik';
            } elseif ($item->z_score_bbu > 1 && $item->z_score_bbu <= 2) {
                $item->status_gizi_bbu = 'Gizi Lebih';
            } else {
                $item->status_gizi_bbu = 'Gizi Lebih';
            }

            $refTBU = $this->getTbuReference($umur, $jk);
            $item->median_tbu = $refTBU['median'] ?? 0;
            $item->sd_tbu = $refTBU['sd'] ?? 1;
            $item->z_score_tbu = $item->sd_tbu ? ($item->tb - $item->median_tbu) / $item->sd_tbu : 0;

            if ($item->z_score_tbu < -3) {
                $item->status_stunting = 'Stunting Berat';
            } elseif ($item->z_score_tbu >= -3 && $item->z_score_tbu < -2) {
                $item->status_stunting = 'Risiko Stunting';
            } elseif ($item->z_score_tbu >= -2 && $item->z_score_tbu <= 1) {
                $item->status_stunting = 'Normal';
            } elseif ($item->z_score_tbu > 1 && $item->z_score_tbu <= 2) {
                $item->status_stunting = 'Risiko Tinggi';
            } else {
                $item->status_stunting = 'Tinggi';
            }
        }
    }

    $pdf = PDF::loadView('timbangan.laporan_pdf', [
        'data' => $data,
        'tahun' => $tahun,
        'statusGizi' => in_array($status, $statusGiziList) ? $status : null,
        'statusStunting' => in_array($status, $statusStuntingList) ? $status : null,
    ]);

    $pdf->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-penimbangan.pdf');
}



}
