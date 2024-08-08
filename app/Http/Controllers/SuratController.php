<?php

namespace App\Http\Controllers;

use App\Models\suratsk;
use App\Models\penduduk;
use App\Models\suratskd;
use App\Models\suratskck;
use App\Models\suratsktm;
use App\Models\daftarsurat;
use App\Models\namattdkades;
use Illuminate\Http\Request;
use App\Models\suratsktmsiswa;
use App\Models\suratwalinikah;
use App\Models\suratkehilangan;
use App\Models\suratpenghasilan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\domisililuar;
use App\Models\suratusaha;

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

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSKD', compact('title', 'nomor_surat'));
    }

    public function submitDomisili(Request $request){
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
            'jenis_surat'=> 'Surat Keterangan Domisili',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $skd = suratskd::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'keterangan' => $request->input('keterangan'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');
    }

    public function showSKDluar(){
        $title = 'Buat Surat Keterangan Secara Manual';

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSKDLuar', compact('title', 'nomor_surat'));
    }

    public function submitSKDLuar(Request $request){
        $request->validate([
            'perihal' => 'required|string|max:255',
            'nomor_surat' => 'required|string|max:255',
            'nama_pemohon' => 'required|string|max:255',
            'nik_pemohon' => 'nullable|string|size:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:255',
            'kewarganegaraan' => 'nullable|string|max:255',
            'pekerjaan' => 'nullable|string|max:255',
            'status_perkawinan' => 'nullable|string|max:255',
            'pendidikan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'keperluan' => 'nullable|string|max:500',
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'Surat Manual',
            'nama_pemohon'=> $request->input('nama_pemohon'),
            'nik_pemohon' => $request->has('nik_pemohon') && !empty($request->input('nik_pemohon')) ? $request->input('nik_pemohon') : '-',
            'status_surat'=> 'belum_cetak',
        ]);

        $skdluar = domisililuar::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'perihal' => $request->input('perihal'),
            'tempat_lahir_pemohon' =>$request->input('tempat_lahir'),
            'tanggal_lahir_pemohon' =>$request->input('tanggal_lahir'),
            'jenis_kelamin_pemohon' =>$request->input('jenis_kelamin'),
            'agama_pemohon' =>$request->input('agama'),
            'kewarganegaraan_pemohon' =>$request->input('kewarganegaraan'),
            'pekerjaan_pemohon' =>$request->input('pekerjaan'),
            'status_perkawinan_pemohon' =>$request->input('status_perkawinan'),
            'pendidikan_pemohon' =>$request->input('pendidikan'),
            'alamat_pemohon' =>$request->input('alamat'),
            'keterangan' =>$request->input('keterangan'),
            'keperluan' =>$request->input('keperluan'),
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');

    }

    public function showSK(){
        $title = 'Buat Surat Keterangan Untuk Warga Tertentu';

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSK', compact('title', 'nomor_surat'));
    }

    public function submitSK(Request $request){
        // dd($request->all());
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
            'jenis_surat'=> 'Surat Keterangan',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $suratsk = suratsk::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'keterangan' => $request->input('keterangan'),
            'keperluan' => $request->input('keperluan'),
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');

    }

    public function showSKTMsiswa(){
        $title = 'Buat Surat Keretangan Tidak Mampu untuk Siswa';

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSKTMS', compact('title', 'nomor_surat'));
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
            'jenis_surat'=> 'Surat Keterangan Tidak Mampu Siswa',
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

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSKTM', compact('title', 'nomor_surat'));
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
            'jenis_surat'=> 'Surat Keterangan Tidak Mampu',
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

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSKK', compact('title', 'nomor_surat'));
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
            'jenis_surat'=> 'Surat Keterangan Kehilangan',
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

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSKwalinikah', compact('title', 'nomor_surat'));
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
            'jenis_surat'=> 'Surat Keterangan Menjadi Wali Nikah',
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

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSKP', compact('title', 'nomor_surat')); 
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
            'jenis_surat'=> 'Surat Keterangan Penghasilan',
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

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSKCK', compact('title', 'nomor_surat'));
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
            'jenis_surat'=> 'Surat Pengantar SKCK',
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

    public function showSKU(){
        $title = 'Buat Surat Keterangan Usaha';
        
        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.FormSKU', compact('title', 'nomor_surat'));
    }

    public function submitSKU(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'selectedNIK' => 'required|string|max:16',
            'usaha' => 'required|string|max:255',
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
            'jenis_surat'=> 'Surat Keterangan Usaha',
            'nama_pemohon'=> $pemohon->nama,
            'nik_pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
        ]);

        $usaha = suratusaha::create([
            'daftarsurat_id' => $daftarsurat -> id,
            'usaha' => $request->input('usaha'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat Berhasil Dibuat');
    }

    public function showLain(){
        $title = "Catat Nomor Surat Diluar Aplikasi";

        $nomor_surat = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        return view('formsurat.Formlain', compact('title', 'nomor_surat'));
    }

    public function submitLain(Request $request){
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'nama_pemohon' => 'required|string|max:255',
            'perihal' => 'required|string|max:20'
        ]);

        $exists = daftarsurat::where('nomor_surat', $request->input('nomor_surat'))->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nomor Surat   '. $request->input('nomor_surat') .' Sudah Digunakan')->withInput();
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => $request->input('tanggal_surat'),
            'jenis_surat'=> $request->input('perihal'),
            'nama_pemohon'=> $request->input('nama_pemohon'),
            'nik_pemohon'=> '-',
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

        $tanggal_surat = \Carbon\Carbon::parse($daftarsurat->tanggal_surat)->locale('id')->translatedFormat('d F Y');

        $ttd = namattdkades::all();

        $penduduk = penduduk::where('NIK', $daftarsurat->nik_pemohon)->first();

        if(!$penduduk){
            if($daftarsurat->jenis_surat === 'Surat Manual'){
                $manual = domisililuar::where('daftarsurat_id', $id)->first();
    
                // Check if the suratskd entry exists
                if(!$manual){
                    return redirect()->back()->with(['error' => 'Surat Manual tidak ditemukan']);
                }
    
                $daftarsurat->update([
                    'status_surat' => 'sudah_cetak'
                ]);
    
                $judulsurat = $manual->perihal;
    
                // Pass the data to the SKD-specific view
                return view('cetaksurat.manual', compact('judulsurat','daftarsurat', 'manual', 'tanggal_surat', 'ttd'));
            }
            return redirect()->back()->with(['error' => 'Penduduk tidak ditemukan']);
        }


        // Check the keperluan field and fetch associated data if keperluan is 'SKD'
        if($daftarsurat->jenis_surat === 'Surat Keterangan Domisili'){
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

        elseif($daftarsurat->jenis_surat === 'Surat Keterangan'){
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
        
        elseif($daftarsurat->jenis_surat === 'Surat Keterangan Tidak Mampu Siswa'){
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

        elseif($daftarsurat->jenis_surat === 'Surat Keterangan Tidak Mampu'){
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

        elseif($daftarsurat->jenis_surat === 'Surat Keterangan Kehilangan'){
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

        elseif($daftarsurat->jenis_surat === 'Surat Keterangan Menjadi Wali Nikah'){
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

        elseif($daftarsurat->jenis_surat === 'Surat Keterangan Penghasilan'){
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

        elseif($daftarsurat->jenis_surat === 'Surat Pengantar SKCK'){
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

        elseif($daftarsurat->jenis_surat === 'Surat Keterangan Usaha'){
            $suratusaha = suratusaha::where('daftarsurat_id', $id)->first();

            // Check if the suratskd entry exists
            if(!$suratusaha){
                return redirect()->back()->with(['error' => 'Surat SKCK tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = 'SURAT KETERANGAN USAHA';

            // Pass the data to the SKD-specific view
            return view('cetaksurat.usaha', compact('judulsurat','daftarsurat', 'suratusaha', 'penduduk', 'tanggal_surat', 'ttd'));
        }

        elseif($daftarsurat->jenis_surat === 'Surat Manual'){
            $manual = domisililuar::where('daftarsurat_id', $id)->first();

            // Check if the suratskd entry exists
            if(!$manual){
                return redirect()->back()->with(['error' => 'Surat Manual tidak ditemukan']);
            }

            $daftarsurat->update([
                'status_surat' => 'sudah_cetak'
            ]);

            $judulsurat = $manual->perihal;

            // Pass the data to the SKD-specific view
            return view('cetaksurat.manual', compact('judulsurat','daftarsurat', 'manual', 'tanggal_surat', 'ttd'));
        }
        
        // Handle other types of keperluan if needed
        // For now, redirect back with an error message if not SKD
        return redirect()->back()->with(['error' => 'Jenis surat tidak valid']);
        
    }
}
