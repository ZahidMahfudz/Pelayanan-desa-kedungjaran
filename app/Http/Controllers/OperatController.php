<?php

namespace App\Http\Controllers;

use App\Models\penduduk;
use App\Models\daftarsurat;
use App\Models\namattdkades;
use Illuminate\Http\Request;
use App\Imports\PendudukImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Constraint\Operator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use App\Models\suratsk;
use App\Models\suratskd;
use App\Models\suratskck;
use App\Models\suratsktm;
use App\Models\suratsktmsiswa;
use App\Models\suratwalinikah;
use App\Models\suratkehilangan;
use App\Models\suratpenghasilan;
use Illuminate\Support\Facades\DB;

class OperatController extends Controller
{
    public function showKesekretariatan(){
        $title = 'Kesekretariatan';
        $data = daftarsurat::orderBy('created_at', 'desc')->paginate(10);
        return view('operator.kesekretariatan', compact('title', 'data'));
    }

    public function showPenduduk(Request $request){
        $title = 'Data Penduduk';
        
        // Mendapatkan nilai pencarian dari request
        $search = $request->input('cariPenduduk');
    
        // Mengambil data penduduk berdasarkan pencarian atau semua data dengan pagination
        if ($search) {
            $datapenduduk = penduduk::where('nama', 'like', "%{$search}%")
                                    ->orWhere('nik', 'like', "%{$search}%")
                                    ->paginate(15)
                                    ->appends(['cariPenduduk' => $search]);
        } else {
            $datapenduduk = penduduk::orderBy('created_at', 'desc')->paginate(15);
        }
    
        return view('operator.Penduduk', compact('title', 'datapenduduk'));
    }

    public function editpenduduk(Request $request, $NIK){
        $request->validate([
            'kk' => 'required|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:255',
            'status_perkawinan' => 'required|string|max:255',
            'shdk' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'dusun' => 'required|string|max:255',
            'RT' => 'required|integer',
            'RW' => 'required|integer',
            'kewarganegaraan' => 'required|in:WNI,WNA',
        ],[
            'kk.required' => 'Nomor KK wajib diisi.',
            'kk.string' => 'Nomor KK harus berupa teks.',
            'kk.max' => 'Nomor KK maksimal 16 karakter.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.string' => 'Tempat lahir harus berupa teks.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'agama.required' => 'Agama wajib diisi.',
            'agama.string' => 'Agama harus berupa teks.',
            'agama.max' => 'Agama maksimal 255 karakter.',
            'status_perkawinan.required' => 'Status perkawinan wajib diisi.',
            'shdk.required' => 'SHDK wajib diisi.',
            'shdk.string' => 'SHDK harus berupa teks.',
            'shdk.max' => 'SHDK maksimal 255 karakter.',
            'pendidikan.required' => 'Pendidikan wajib diisi.',
            'pendidikan.string' => 'Pendidikan harus berupa teks.',
            'pendidikan.max' => 'Pendidikan maksimal 255 karakter.',
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'pekerjaan.string' => 'Pekerjaan harus berupa teks.',
            'pekerjaan.max' => 'Pekerjaan maksimal 255 karakter.',
            'nama_ayah.required' => 'Nama ayah wajib diisi.',
            'nama_ayah.string' => 'Nama ayah harus berupa teks.',
            'nama_ayah.max' => 'Nama ayah maksimal 255 karakter.',
            'nama_ibu.required' => 'Nama ibu wajib diisi.',
            'nama_ibu.string' => 'Nama ibu harus berupa teks.',
            'nama_ibu.max' => 'Nama ibu maksimal 255 karakter.',
            'dusun.required' => 'Dusun wajib diisi.',
            'dusun.string' => 'Dusun harus berupa teks.',
            'dusun.max' => 'Dusun maksimal 255 karakter.',
            'RT.required' => 'RT wajib diisi.',
            'RT.integer' => 'RT harus berupa angka.',
            'RW.required' => 'RW wajib diisi.',
            'RW.integer' => 'RW harus berupa angka.',
            'kewarganegaraan.required' => 'kewarganegaraan wajib diisi.',
            'kewarganegaraan.in' => 'kewarganegaraan harus WNI atau WNA.',
        ]);

        $data = Penduduk::where('NIK', $NIK)->first();

        $data->update([
            'kk' => $request->input('kk'),
            'nama' => $request->input('nama'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'agama' => $request->input('agama'),
            'status_perkawinan' => $request->input('status_perkawinan'),
            'shdk' => $request->input('shdk'),
            'pendidikan' => $request->input('pendidikan'),
            'pekerjaan' => $request->input('pekerjaan'),
            'nama_ayah' => $request->input('nama_ayah'),
            'nama_ibu' => $request->input('nama_ibu'),
            'dusun' => $request->input('dusun'),
            'RT' => $request->input('RT'),
            'RW' => $request->input('RW'),
            'kewarganegaraan' => $request->input('kewarganegaraan')
        ]);

        return redirect('/user/operator/datapenduduk')->with('success', 'Data berhasil diedit');
    }


    public function showAddPenduduk(){
        $title = 'Tambah Penduduk';
        return view('operator.buatpenduduk', compact('title'));
    }

    public function addPenduduk(Request $request){
        $existingNIK = Penduduk::where('NIK', $request->input('NIK'))->first();
        if ($existingNIK) {
            return redirect('/user/operator/datapenduduk')->with('error', 'NIK ' . $request->input('NIK') . ' sudah ditambahkan.')->withInput();
        }

        $request->validate([
            'kk' => 'required|string|max:16',
            'NIK' => 'required|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:255',
            'status_perkawinan' => 'required|string|max:255',
            'shdk' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'dusun' => 'required|string|max:255',
            'RT' => 'required|integer',
            'RW' => 'required|integer',
            'kewarganegaraan' => 'required|in:WNI,WNA',
        ],[
            'kk.required' => 'Nomor KK wajib diisi.',
            'kk.string' => 'Nomor KK harus berupa teks.',
            'kk.max' => 'Nomor KK maksimal 16 karakter.',
            'NIK.required' => 'Nomor NIK wajib diisi.',
            'NIK.string' => 'Nomor NIK harus berupa teks.',
            'NIK.max' => 'Nomor NIK maksimal 16 karakter.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.string' => 'Tempat lahir harus berupa teks.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'agama.required' => 'Agama wajib diisi.',
            'agama.string' => 'Agama harus berupa teks.',
            'agama.max' => 'Agama maksimal 255 karakter.',
            'status_perkawinan.required' => 'Status perkawinan wajib diisi.',
            'shdk.required' => 'SHDK wajib diisi.',
            'shdk.string' => 'SHDK harus berupa teks.',
            'shdk.max' => 'SHDK maksimal 255 karakter.',
            'pendidikan.required' => 'Pendidikan wajib diisi.',
            'pendidikan.string' => 'Pendidikan harus berupa teks.',
            'pendidikan.max' => 'Pendidikan maksimal 255 karakter.',
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'pekerjaan.string' => 'Pekerjaan harus berupa teks.',
            'pekerjaan.max' => 'Pekerjaan maksimal 255 karakter.',
            'nama_ayah.required' => 'Nama ayah wajib diisi.',
            'nama_ayah.string' => 'Nama ayah harus berupa teks.',
            'nama_ayah.max' => 'Nama ayah maksimal 255 karakter.',
            'nama_ibu.required' => 'Nama ibu wajib diisi.',
            'nama_ibu.string' => 'Nama ibu harus berupa teks.',
            'nama_ibu.max' => 'Nama ibu maksimal 255 karakter.',
            'dusun.required' => 'Dusun wajib diisi.',
            'dusun.string' => 'Dusun harus berupa teks.',
            'dusun.max' => 'Dusun maksimal 255 karakter.',
            'RT.required' => 'RT wajib diisi.',
            'RT.integer' => 'RT harus berupa angka.',
            'RW.required' => 'RW wajib diisi.',
            'RW.integer' => 'RW harus berupa angka.',
            'kewarganegaraan.required' => 'kewarganegaraan wajib diisi.',
            'kewarganegaraan.in' => 'kewarganegaraan harus WNI atau WNA.',
        ]);

        $simpan = Penduduk::create([
            'NIK' => $request->input('NIK'),
            'kk' => $request->input('kk'),
            'nama' => $request->input('nama'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'agama' => $request->input('agama'),
            'status_perkawinan' => $request->input('status_perkawinan'),
            'shdk' => $request->input('shdk'),
            'pendidikan' => $request->input('pendidikan'),
            'pekerjaan' => $request->input('pekerjaan'),
            'nama_ayah' => $request->input('nama_ayah'),
            'nama_ibu' => $request->input('nama_ibu'),
            'dusun' => $request->input('dusun'),
            'RT' => $request->input('RT'),
            'RW' => $request->input('RW'),
            'kewarganegaraan' => $request->input('kewarganegaraan'),
        ]);

        return redirect('/user/operator/datapenduduk')->with('success', 'Data berhasil ditambah');
    }

    public Function deletePenduduk($NIK){
        $data = Penduduk::where('NIK', $NIK)->first();
        if ($data) {
            $data->delete();
            return redirect('/user/operator/datapenduduk')->with('success', 'Data berhasil dihapus.');
        }
    
        return redirect('/user/operator/datapenduduk')->with('error', 'Data tidak ditemukan.');
    }

    public function showdashboard(){
        $title = 'Dashboard';

        $nomor_surat_terakhir = DB::table('daftarsurats')
                     ->orderBy('created_at', 'desc')
                     ->value('nomor_surat');

        $total_surat = daftarsurat::count();

        $suratData = [
            'SK' => daftarsurat::where('jenis_surat', 'SK')->count(),
            'SKD' => daftarsurat::where('jenis_surat', 'SKD')->count(),
            'SKTM Siswa' => daftarsurat::where('jenis_surat', 'SKTMS')->count(),
            'SKTM' => daftarsurat::where('jenis_surat', 'SKTM')->count(),
            'SKK' => daftarsurat::where('jenis_surat', 'SKK')->count(),
            'SKWN' => daftarsurat::where('jenis_surat', 'SKWN')->count(),
            'SKP' => daftarsurat::where('jenis_surat', 'SKP')->count(),
            'SKCK' => daftarsurat::where('jenis_surat', 'SKCK')->count(),
            'Lain-lain' => daftarsurat::whereNotIn('jenis_surat', ['SK', 'SKD', 'SKTMS', 'SKTM', 'SKK', 'SKWN', 'SKP', 'SKCK'])->count()
        ];

        $total_penduduk = penduduk::count();

        $dusunData = Penduduk::selectRaw('dusun, 
                                          SUM(CASE WHEN jenis_kelamin = "L" THEN 1 ELSE 0 END) as L, 
                                          SUM(CASE WHEN jenis_kelamin = "P" THEN 1 ELSE 0 END) as P,
                                          COUNT(*) as Total')
                             ->groupBy('dusun')
                             ->get();

        return view('operator.Dashboard', compact('title', 'nomor_surat_terakhir','total_surat', 'suratData', 'total_penduduk', 'dusunData'));
    }

    public function showbuatsurat(){
        $title = 'Buat Surat';
        return view('operator.BuatSurat', compact('title'));
    }

    public function showTTD(){
        $title = 'Nama Dan Tanda Tangan Kepala Desa';

        $ttd = namattdkades::all();

        return view('operator.namattdkades', compact('title', 'ttd'));
    }

    public function addnamattdkades(Request $request){
        $request->validate([
            'nama_kades' => 'required|string|max:255',
            'nama_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('nama_file')) {
            $nm = $request->file('nama_file');
            // Mendapatkan ukuran gambar
            list($width, $height) = getimagesize($nm);
            
            // Validasi untuk memastikan gambar memiliki orientasi 1:1
            if ($width != $height) {
                return redirect()->back()->with('error', 'Gambar harus memiliki orientasi 1:1 (persegi).');
            }
            $namafile = time().rand(100,999).".".$nm->getClientOriginalExtension();
    
            // Simpan nama file dan nama kades ke dalam tabel
            $dtuplaod = new namattdkades;
            $dtuplaod->nama_kades = $request->input('nama_kades');
            $dtuplaod->nama_gambar = $namafile;
    
            // Pindahkan file gambar ke direktori public/image dengan nama asli file
            $nm->move(public_path('image'), $namafile);
            $dtuplaod->save();
    
            return redirect()->back()->with('success', 'Nama dan Tanda Tangan Berhasil Ditambahkan.');
        }
    
        return redirect()->back()->with('error', 'File not uploaded.');
    }
    

    public function editTTD(Request $request, $id){
        // dd($request->all());
        $request->validate([
            'nama_kades' => 'required|string|max:255',
            'file_baru' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $kades = namattdkades::findOrFail($id);

        if(!$kades){
            return redirect()->back()->with('error', 'Nama dan Tanda Tangan Tidak ditemukan');
        }

        $namaGambarLama = $kades->nama_gambar;

        $data = [
            'nama_kades' => $request->input('nama_kades'),
            'nama_gambar' => $namaGambarLama
        ];

        if ($request->hasFile('file_baru')) {
            $file = $request->file('file_baru');
            // Pindahkan file baru dengan nama file yang sama seperti nama file lama
            $file->move(public_path('image'), $namaGambarLama);
        }

        $kades->update($data);

        return redirect()->back()->with('success', 'Data has been updated successfully.');
    }

    public function importPenduduk(Request $request){
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        Excel::import(new PendudukImport, $request->file('import_file')->store('temp'));

        return redirect('/user/operator/datapenduduk')->with('success', 'Import Data Berhasil');
    }

    public function rekapsurat(){
        $surat = daftarsurat::all();
        $judulsurat = 'Rekap Surat';

        return view('cetaksurat.daftarsurat', compact('surat', 'judulsurat'));

    }

    public function hapusdaftarsurat(){
        try {
            DB::beginTransaction();

            // Fetch all daftarsurat entries
            $daftarSurat = daftarsurat::all();

            // Loop through each daftarsurat entry
            foreach ($daftarSurat as $surat) {
                // Delete associated suratskd entry if it exists
                if ($surat->suratskd) {
                    $surat->suratskd()->delete();
                }

                // Delete associated suratsk entry if it exists
                if ($surat->suratsk) {
                    $surat->suratsk()->delete();
                }

                // Delete associated suratsktmsiswa entry if it exists
                if ($surat->suratsktmsiswa) {
                    $surat->suratsktmsiswa()->delete();
                }

                // Delete associated suratsktm entry if it exists
                if ($surat->suratsktm) {
                    $surat->suratsktm()->delete();
                }

                // Delete associated suratkehilangan entry if it exists
                if ($surat->suratkehilangan) {
                    $surat->suratkehilangan()->delete();
                }

                // Delete associated suratwalinikah entry if it exists
                if ($surat->suratwalinikah) {
                    $surat->suratwalinikah()->delete();
                }

                // Delete associated suratpenghasilan entry if it exists
                if ($surat->suratpenghasilan) {
                    $surat->suratpenghasilan()->delete();
                }

                // Delete associated suratskck entry if it exists
                if ($surat->suratskck) {
                    $surat->suratskck()->delete();
                }

                // Delete the daftarsurat entry
                $surat->delete();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Semua surat berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle any errors
            return redirect()->back()->with('error', 'Gagal menghapus surat: ' . $e->getMessage());
        }
    }
}
