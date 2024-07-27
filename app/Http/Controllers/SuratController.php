<?php

namespace App\Http\Controllers;

use App\Models\daftarsurat;
use App\Models\namattdkades;
use App\Models\penduduk;
use App\Models\suratkehilangan;
use App\Models\suratpenghasilan;
use App\Models\suratsk;
use App\Models\suratskck;
use App\Models\suratskd;
use App\Models\suratsktm;
use App\Models\suratsktmsiswa;
use App\Models\suratwalinikah;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function Caripenduduk(){
        $pemohon = penduduk::where('nama', 'LIKE', '%'.request('q').'%')->get();
        return response()->json($pemohon);
    }

    public function deleteSurat($id){
        // Fetch the daftarsurat entry by its ID
        $daftarsurat = daftarsurat::find($id);
    
        // Check if the daftarsurat entry exists
        if(!$daftarsurat){
            return redirect()->back()->with(['error' => 'Surat tidak ditemukan']);
        }
    
        // Fetch the associated suratskd entry
        $suratskd = suratskd::where('daftarsurat_id', $id)->first();
    
        // Delete the associated suratskd entry if it exists
        if($suratskd){
            $suratskd->delete();
        }
    
        // Delete the daftarsurat entry
        $daftarsurat->delete();
    
        // Redirect with success message
        return redirect()->back()->with('success', 'Surat berhasil dihapus');
    }


    public function showSKD(){
        $title = 'Buat Surat Keterangan Domisili';

        return view('formsurat.FormSKD', compact('title'));
    }

    public function showeditSKD($id){
        $title = 'Buat Surat Keterangan Domisili';
        $surat = daftarsurat::findOrFail($id);

        return view('editsurat.editSKD', [
            'title' => $title,
            'surat' => $surat
        ]);
    }


    public function submitDomisili(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIK' => 'required|string|max:16',
            'keperluan' => 'required|string|max:1000',
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'SKD',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $skd = suratskd::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'alamat' => $request->input('alamat'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');
    }

    public function update_domisili(Request $request, $id){
        dd($request->all());
    }

    public function showSK(){
        $title = 'Buat Surat Keterangan Untuk Warga Tertentu';

        return view('formsurat.FormSK', compact('title'));
    }

    public function submitSK(Request $request){
        // dd($request->all());
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIK' => 'required|string|max:16',
            'keterangan' => 'required|string|max:1000',
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'SK',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $suratsk = suratsk::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'keterangan' => $request->input('keterangan'),
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');

    }

    public function showSKTMsiswa(){
        $title = 'Buat Surat Keretangan Tidak Mampu untuk Siswa';

        return view('formsurat.FormSKTMS', compact('title'));
    }

    public function submitSKTMS(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIKWaliMurid' => 'required|string|max:16',
            'selectedNIKMurid' => 'required|string|max:16',
            'asal_sekolah' => 'required|string|max:255',
            'keperluan' => 'required|string|max:1000',
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $pemohon = penduduk::where('NIK', $request->input('selectedNIKWaliMurid'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'SKTMS',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIKWaliMurid'),
            'status_surat'=> 'belum_cetak',
        ]);

        $sktms = suratsktmsiswa::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'asal_sekolah' => $request->input('asal_sekolah'),
            'nik_murid' => $request->input('selectedNIKMurid'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');
    }

    public function showSKTM(){
        $title = 'Buat Surat Keretangan Tidak Mampu';

        return view('formsurat.FormSKTM', compact('title'));
    }

    public function submitSKTM(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIK' => 'required|string|max:16',
            'keterangan' => 'required|string|max:1000',
            'keperluan' => 'required|string|max:1000',
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'SKTM',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $sktm = suratsktm::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'keterangan' => $request->input('keterangan'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');
    }

    public function showSKK(){
        $title = 'Buat Surat Keterangan Kehilangan';

        return view('formsurat.FormSKK', compact('title'));
    }

    public function submitSKK(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIK' => 'required|string|max:16',
            'keterangan' => 'required|string|max:1000',
            'keperluan' => 'required|string|max:1000',
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'SKK',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $suratkehilangan = suratkehilangan::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'keterangan' => $request->input('keterangan'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');

    }

    public function showSKWALI(){
        $title = 'Buat Surat Keterangan Wali Nikah';

        return view('formsurat.FormSKwalinikah', compact('title'));
    }

    public function submitSKwalinikah(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIK' => 'required|string|max:16',
            'keterangan' => 'required|string|max:1000',
            'keperluan' => 'required|string|max:1000',
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'SKWN',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $walinikah = suratwalinikah::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'keterangan' => $request->input('keterangan'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');
    }

    public function showSKP(){
        $title = 'Buat Surat Keterangan Penghasilan';

        return view('formsurat.FormSKP', compact('title')); 
    }

    public function submitSKP(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIK' => 'required|string|max:16',
            'penghasilan' => 'required|numeric|min:0',
            'keperluan' => 'required|string|max:1000',
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'SKP',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $suratpenghasikan = suratpenghasilan::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'penghasilan' => $request->input('penghasilan'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');
    }

    public function showSKCK(){
        $title = 'Buat Surat Pengantar SKCK';

        return view('formsurat.FormSKCK', compact('title'));
    }

    public function submitSKCK(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIK' => 'required|string|max:16',
            'keperluan' => 'required|string|max:1000',
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'SKCK',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $skck = suratskck::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');
    }

    public function showLain(){
        $title = "Catat Nomor Surat Diluar Aplikasi";

        return view('formsurat.Formlain', compact('title'));
    }

    public function submitLain(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIK' => 'required|string|max:16',
            'perihal' => 'required|string|max:20'
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> $request->input('perihal'),
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'sudah_cetak',
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');
    }

    public function cetakSurat($id){
        // Fetch the daftarsurat entry by its ID
        $daftarsurat = daftarsurat::find($id);

        
        // Check if the daftarsurat entry exists
        if(!$daftarsurat){
            return redirect()->back()->with(['error' => 'Surat tidak ditemukan']);
        }

        $penduduk = penduduk::where('NIK', $daftarsurat->nik_pemohon)->first();

        if(!$penduduk){
            return redirect()->back()->with(['error' => 'Penduduk tidak ditemukan']);
        }

        $tanggal_surat = \Carbon\Carbon::parse($daftarsurat->tanggal_surat)->locale('id')->translatedFormat('d F Y');

        $ttd = namattdkades::all();

        // Check the keperluan field and fetch associated data if keperluan is 'SKD'
        if($daftarsurat->jenis_surat === 'SKD'){
            $suratskd = suratskd::where('daftarsurat_id', $id)->first();

            // Check if the suratskd entry exists
            if(!$suratskd){
                return redirect()->back()->with(['error' => 'Surat SKD tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = 'SURAT KETERANGAN DOMISILI';

            // Pass the data to the SKD-specific view
            return view('cetaksurat.domisili', compact('judulsurat','daftarsurat', 'suratskd', 'penduduk', 'tanggal_surat', 'ttd'));
        }

        elseif($daftarsurat->jenis_surat === 'SK'){
            $suratsk = suratsk::where('daftarsurat_id', $id)->first();

            // Check if the suratskd entry exists
            if(!$suratsk){
                return redirect()->back()->with(['error' => 'Surat SKD tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = 'SURAT KETERANGAN';

            // Pass the data to the SKD-specific view
            return view('cetaksurat.keterangan', compact('judulsurat','daftarsurat', 'suratsk', 'penduduk', 'tanggal_surat', 'ttd'));
        }
        
        elseif($daftarsurat->jenis_surat === 'SKTMS'){
            $suratsktms = suratsktmsiswa::where('daftarsurat_id', $id)->first();
            $murid = penduduk::where('NIK', $suratsktms->nik_murid)->first();

            // Check if the suratskd entry exists
            if(!$suratsktms){
                return redirect()->back()->with(['error' => 'Surat SKD tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = 'SURAT KETERANGAN TIDAK MAMPU';

            // Pass the data to the SKD-specific view
            return view('cetaksurat.sktmsiswa', compact('judulsurat','daftarsurat', 'suratsktms', 'penduduk', 'murid', 'tanggal_surat', 'ttd'));
        }

        elseif($daftarsurat->jenis_surat === 'SKTM'){
            $suratsktm = suratsktm::where('daftarsurat_id', $id)->first();

            // Check if the suratskd entry exists
            if(!$suratsktm){
                return redirect()->back()->with(['error' => 'Surat SKTM tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = 'SURAT KETERANGAN TIDAK MAMPU';

            // Pass the data to the SKD-specific view
            return view('cetaksurat.tidakmampu', compact('judulsurat','daftarsurat', 'suratsktm', 'penduduk', 'tanggal_surat', 'ttd'));
        }

        elseif($daftarsurat->jenis_surat === 'SKK'){
            $suratkehilangan = suratkehilangan::where('daftarsurat_id', $id)->first();

            // Check if the suratskd entry exists
            if(!$suratkehilangan){
                return redirect()->back()->with(['error' => 'Surat SKTM tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = 'SURAT KETERANGAN KEHILANGAN';

            // Pass the data to the SKD-specific view
            return view('cetaksurat.kehilangan', compact('judulsurat','daftarsurat', 'suratkehilangan', 'penduduk', 'tanggal_surat', 'ttd'));
        }

        elseif($daftarsurat->jenis_surat === 'SKWN'){
            $suratwalinikah = suratwalinikah::where('daftarsurat_id', $id)->first();

            // Check if the suratskd entry exists
            if(!$suratwalinikah){
                return redirect()->back()->with(['error' => 'Surat SKTM tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = 'SURAT KETERANGAN MENJADI WALI NIKAH';

            // Pass the data to the SKD-specific view
            return view('cetaksurat.walinikah', compact('judulsurat','daftarsurat', 'suratwalinikah', 'penduduk', 'tanggal_surat', 'ttd'));
        }

        elseif($daftarsurat->jenis_surat === 'SKP'){
            $suratpenghasilan = suratpenghasilan::where('daftarsurat_id', $id)->first();

            // Check if the suratskd entry exists
            if(!$suratpenghasilan){
                return redirect()->back()->with(['error' => 'Surat SKTM tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = 'SURAT KETERANGAN PENGHASILAN';

            // Pass the data to the SKD-specific view
            return view('cetaksurat.penghasilan', compact('judulsurat','daftarsurat', 'suratpenghasilan', 'penduduk', 'tanggal_surat', 'ttd'));
        }

        elseif($daftarsurat->jenis_surat === 'SKCK'){
            $suratskck = suratskck::where('daftarsurat_id', $id)->first();

            // Check if the suratskd entry exists
            if(!$suratskck){
                return redirect()->back()->with(['error' => 'Surat SKCK tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = 'SURAT PENGANTAR';

            // Pass the data to the SKD-specific view
            return view('cetaksurat.SKCK', compact('judulsurat','daftarsurat', 'suratskck', 'penduduk', 'tanggal_surat', 'ttd'));
        }
        

        // Handle other types of keperluan if needed
        // For now, redirect back with an error message if not SKD
        return redirect()->back()->with(['error' => 'Jenis surat tidak valid']);
        
    }
}
