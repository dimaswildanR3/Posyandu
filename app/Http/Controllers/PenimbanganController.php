<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\Balita;
use App\Models\Jadwal;
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
        $orangTua = OrangTua::where('user_id', $user->id)->first();

        if ($user->role === 'ortu') {
            // Ambil semua balita milik orang tua
            $balita = Balita::where('orang_tua_id', $orangTua->id)->get();
            $balitaIds = $balita->pluck('id');

            // Ambil data penimbangan untuk anak-anak tersebut
            $timbangan = Penimbangan::with('balita', 'user')
                ->whereIn('balita_id', $balitaIds)
                ->orderBy('tanggal_timbang', 'DESC')
                ->paginate(10);

            // Hitung jenis kelamin anak-anak ortu ini
            $jenisKelaminLaki = $balita->where('jenis_kelamin', 'Laki-laki')->count();
            $jenisKelaminPerem = $balita->where('jenis_kelamin', 'Perempuan')->count();
        } else {
            // Kalau admin
            $balita = Balita::all();
            $timbangan = Penimbangan::with('balita', 'user')->orderBy('tanggal_timbang', 'DESC')->paginate(10);
            $jenisKelaminLaki = Balita::where('jenis_kelamin', 'Laki-laki')->count();
            $jenisKelaminPerem = Balita::where('jenis_kelamin', 'Perempuan')->count();
        }

        // Chart
        foreach ($timbangan as $mp) {
            $chart[] = $mp->balita->nama_balita;
            $beratBadan[] = $mp->bb;
            $tinggiBadan[] = $mp->tb;
        }

        $laki[] = $jenisKelaminLaki;
        $perem[] = $jenisKelaminPerem;

        $tanggalPelayanan = Jadwal::all();

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
    
        // Hitung status gizi
        $statusGizi = $this->hitungStatusGizi($umur, $request->bb);
    
        // Hitung z_score (dummy, bisa diganti sesuai standar WHO)
        $zScore = $this->hitungZScore($umur, $request->bb);
        // Hitung status stunting
        $statusStunting = $this->hitungStatusStunting($umur, $request->tb);

    
        Penimbangan::create([
            'balita_id' => $request->balita_id,
            'bb' => $request->bb,
            'tb' => $request->tb,
            'user_id' => $request->user_id,
            'tanggal_timbang' => $request->tanggal_timbang,
            'umur' => $umur,
            'status_gizi' => $statusGizi,
            'z_score' => $zScore,
            'status_stunting' => $statusStunting,
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
        $statusGizi = $this->hitungStatusGizi($umur, $request->bb);
        $zScore = $this->hitungZScore($umur, $request->bb);
        // Hitung status stunting
        $statusStunting = $this->hitungStatusStunting($umur, $request->tb);

    
        Penimbangan::where('id', $id)->update([
            'balita_id' => $request->balita_id,
            'bb' => $request->bb,
            'tb' => $request->tb,
            'tanggal_timbang' => $request->tanggal_timbang,
            'umur' => $umur,
            'status_gizi' => $statusGizi,
            'z_score' => $zScore,
            'status_stunting' => $statusStunting,
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

    private function hitungStatusGizi($umur, $bb)
{
    // Contoh sederhana berdasarkan BB/U (untuk demo)
    if ($umur < 24) {
        if ($bb < 8) return 'Gizi Kurang';
        elseif ($bb >= 8 && $bb <= 11) return 'Gizi Baik';
        else return 'Risiko Gizi Lebih';
    }

    if ($bb < 10) return 'Gizi Kurang';
    elseif ($bb <= 14) return 'Gizi Baik';
    else return 'Risiko Gizi Lebih';
}
private function hitungZScore($umur, $bb)
{
    // Dummy contoh perhitungan z-score sederhana
    // Asumsikan median berat badan per umur = 10 kg, SD = 1.5
    $median = 10;
    $sd = 1.5;

    return round(($bb - $median) / $sd, 2);
}

private function hitungStatusStunting($umur, $tb)
{
    // Dummy contoh: median tinggi dan SD disederhanakan
    // Asumsi tinggi ideal (cm) = 75 + umur x 0.5; SD = 3
    $median = 75 + ($umur * 0.5); // ini logika kira-kira, bisa kamu ganti
    $sd = 3;

    $z = ($tb - $median) / $sd;

    if ($z < -3) {
        return 'Sangat Pendek';
    } elseif ($z < -2) {
        return 'Pendek';
    } elseif ($z <= 2) {
        return 'Normal';
    } else {
        return 'Tinggi';
    }
}


public function kms(Request $request, $balita_id)
{
    $balita = \App\Models\Balita::findOrFail($balita_id);

    // Ambil filter tanggal dari query param
    $dari = $request->query('dari');
    $sampai = $request->query('sampai');

    $query = \App\Models\Penimbangan::where('balita_id', $balita_id);

    if ($dari) {
        $query->whereDate('tanggal_timbang', '>=', $dari);
    }
    if ($sampai) {
        $query->whereDate('tanggal_timbang', '<=', $sampai);
    }

    $penimbangans = $query->orderBy('tanggal_timbang')->get();

    // Untuk grafik
    $labels = $penimbangans->pluck('tanggal_timbang')->map(function ($t) {
        return \Carbon\Carbon::parse($t)->format('d M Y');
    });

    $berat = $penimbangans->pluck('bb')->map(fn($bb) => (float) $bb);
    $tinggi = $penimbangans->pluck('tb')->map(fn($tb) => (float) $tb);

    return view('timbangan.kms', compact(
        'balita',
        'penimbangans',
        'dari',
        'sampai',
        'labels',
        'berat',
        'tinggi'
    ));
}

public function cetakPdf(Request $request) 
{
    $tahun = $request->tahun;
    $status = $request->status;

    $query = Penimbangan::with('balita', 'user');

    if ($tahun) {
        $query->whereYear('tanggal_timbang', $tahun);
    }

    if ($status) {
        $statusGiziList = ['Normal', 'Gizi Kurang', 'Gizi Buruk', 'Risiko Gizi Lebih'];
        $statusStuntingList = ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'];

        if (in_array($status, $statusGiziList)) {
            $query->where('status_gizi', $status);
        } elseif (in_array($status, $statusStuntingList)) {
            $query->where('status_stunting', $status);
        }
    }

    $data = Penimbangan::with('balita')
    ->when($tahun, fn($q) => $q->whereYear('tanggal_timbang', $tahun))
    ->when($status, function ($q) use ($status) {
        $statusGiziList = ['Normal', 'Gizi Kurang', 'Gizi Buruk', 'Risiko Gizi Lebih'];
        $statusStuntingList = ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'];

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

    $pdf = PDF::loadView('timbangan.laporan_pdf', [
        'data' => $data,
        'tahun' => $tahun,
        'statusGizi' => in_array($status, $statusGiziList ?? []) ? $status : null,
        'statusStunting' => in_array($status, $statusStuntingList ?? []) ? $status : null,
    ]);

    return $pdf->stream('laporan-penimbangan.pdf');
}


}
