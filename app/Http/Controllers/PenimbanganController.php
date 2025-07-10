<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Balita;
use App\Models\Jadwal;
use App\Models\Penimbangan;
use Carbon\Carbon;


use Illuminate\Http\Request;

class PenimbanganController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dari = '';
        $sampai = '';
        $balita = Balita::all();
        $timbangan = Penimbangan::with('balita','user')->orderBy('tanggal_timbang', 'DESC')->paginate(10);
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
        $tanggalLahir = Carbon::parse($balita->tanggal_lahir);
        $tanggalTimbang = Carbon::parse($request->tanggal_timbang);
    
        // Hitung umur dalam bulan
        $umur = $tanggalLahir->diffInMonths($tanggalTimbang);
    
        // Hitung status gizi
        $statusGizi = $this->hitungStatusGizi($umur, $request->bb);
    
        // Hitung z_score (dummy, bisa diganti sesuai standar WHO)
        $zScore = $this->hitungZScore($umur, $request->bb);
    
        Penimbangan::create([
            'balita_id' => $request->balita_id,
            'bb' => $request->bb,
            'tb' => $request->tb,
            'user_id' => $request->user_id,
            'tanggal_timbang' => $request->tanggal_timbang,
            'umur' => $umur,
            'status_gizi' => $statusGizi,
            'z_score' => $zScore,
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
        $penimbangan = Penimbangan::with('balita')->where('id',$id)->get();
        $chart = [];
        $tinggiBadan = [];
        $beratBadan = [];
        $tanggal =[];
        foreach($penimbangan as $mp){
            $chart[]= $mp->balita->nama_balita;
            $beratBadan[]= $mp->bb;
            $tinggiBadan[]= $mp->tb;
        }
        return view('timbangan.detail',compact('penimbangan','chart','beratBadan','tinggiBadan'));
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
        $tanggalLahir = Carbon::parse($balita->tanggal_lahir);
        $tanggalTimbang = Carbon::parse($request->tanggal_timbang);
    
        $umur = $tanggalLahir->diffInMonths($tanggalTimbang);
        $statusGizi = $this->hitungStatusGizi($umur, $request->bb);
        $zScore = $this->hitungZScore($umur, $request->bb);
    
        Penimbangan::where('id', $id)->update([
            'balita_id' => $request->balita_id,
            'bb' => $request->bb,
            'tb' => $request->tb,
            'tanggal_timbang' => $request->tanggal_timbang,
            'umur' => $umur,
            'status_gizi' => $statusGizi,
            'z_score' => $zScore,
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

public function kms(Request $request, $balita_id)
{
    $balita = \App\Models\Balita::findOrFail($balita_id);

    // Ambil filter tanggal dari query param, kalau kosong pakai null (ambil semua)
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

    return view('timbangan.kms', compact('balita', 'penimbangans', 'dari', 'sampai'));
}



}
