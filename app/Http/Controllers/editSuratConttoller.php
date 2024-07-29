<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\daftarsurat;
use App\Models\suratskd;
use App\Models\penduduk;
use App\Models\suratkehilangan;
use App\Models\suratpenghasilan;
use App\Models\suratsk;
use App\Models\suratskck;
use App\Models\suratsktm;
use App\Models\suratsktmsiswa;
use App\Models\suratwalinikah;

class editSuratConttoller extends Controller
{
    public function showedit($id){
        $surat = daftarsurat::findOrFail($id);
        
        if($surat->jenis_surat == 'SKD'){
            $title = 'Edit Surat Keterangan Domisili';
            $skd = suratskd::where('daftarsurat_id', $surat->id)->first();
            return view('editsurat.editSKD', compact('title', 'surat', 'skd'));
        }

        elseif($surat->jenis_surat == 'SK'){
            $title = 'Edit Surat Keterangan';
            $sk = suratsk::where('daftarsurat_id', $surat->id)->first();
            return view('editsurat.editSK', compact('title', 'surat', 'sk'));
        }

        elseif($surat->jenis_surat == 'SKTMS'){
            $title = 'Edit Surat Keterangan Tidak Mampu Untuk Siswa';
            $sktms = suratsktmsiswa::where('daftarsurat_id', $surat->id)->first();
            $siswa = penduduk::where('NIK', $sktms->nik_murid)->first();
            return view('editsurat.editSKTMS', compact('title', 'surat', 'sktms', 'siswa'));
        }

        elseif($surat->jenis_surat == 'SKTM'){
            $title = 'Edit Surat Keterangan Tidak Mampu';
            $sktm = suratsktm::where('daftarsurat_id', $surat->id)->first();
            return view('editsurat.editSKTM', compact('title', 'surat', 'sktm'));
        }

        elseif($surat->jenis_surat == 'SKK'){
            $title = 'Edit Surat Keterangan Kehilangan';
            $skk = suratkehilangan::where('daftarsurat_id', $surat->id)->first();
            return view('editsurat.editSKK', compact('title', 'surat', 'skk'));
        }

        elseif($surat->jenis_surat == 'SKWN'){
            $title = 'Edit Surat Keterangan Menjadi Wali Nikah';
            $skwn = suratwalinikah::where('daftarsurat_id', $surat->id)->first();
            return view('editsurat.editSKWN', compact('title', 'surat', 'skwn'));
        }

        elseif($surat->jenis_surat == 'SKP'){
            $title = 'Edit Surat Keterangan Penghasilan';
            $skp = suratpenghasilan::where('daftarsurat_id', $surat->id)->first();
            return view('editsurat.editSKP', compact('title', 'surat', 'skp'));
        }

        elseif($surat->jenis_surat == 'SKCK'){
            $title = 'Edit Surat Pengantar SKCK';
            $skck = suratskck::where('daftarsurat_id', $surat->id)->first();
            return view('editsurat.editSKCK', compact('title', 'surat', 'skck'));
        }
        else{
            $title = 'Edit Surat Lain-lain';
            return view('editsurat.editlain', compact('title', 'surat'));
        }
        
    }

    public function editdomisili(Request $request, $id){
        $request->validate([
            'selectedNIK' => 'required|string|max:16',
            'keperluan' => 'required|string|max:1000',
        ]);

        // Temukan surat berdasarkan ID
        $daftarsurat = daftarsurat::findOrFail($id);
        
        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        // Update data di tabel daftarsurats
        $daftarsurat->update([
            'nama_pemohon' => $pemohon->nama,
            'nik_pemohon' => $request->input('selectedNIK'),
        ]);

        // Temukan surat keterangan domisili terkait
        $suratskd = suratskd::where('daftarsurat_id', $daftarsurat->id)->first();

        // Update data di tabel suratskd
        $suratskd->update([
            'alamat' => $request->input('alamat'),
            'keperluan' => $request->input('keperluan'),
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat berhasil diperbarui');
    }

    public function editketerangan(Request $request, $id){
        $request->validate([
            'selectedNIK' => 'required|string|max:16',
            'keterangan' => 'required|string|max:1000',
        ]);

        // Temukan surat berdasarkan ID
        $daftarsurat = daftarsurat::findOrFail($id);
        
        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        // Update data di tabel daftarsurats
        $daftarsurat->update([
            'nama_pemohon' => $pemohon->nama,
            'nik_pemohon' => $request->input('selectedNIK'),
        ]);

        // Temukan surat keterangan domisili terkait
        $suratsk = suratsk::where('daftarsurat_id', $daftarsurat->id)->first();

        // Update data di tabel suratsk
        $suratsk->update([
            'keterangan' => $request->input('keterangan'),
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat berhasil diperbarui');
    }

    public function editSKTMS(Request $request, $id){
        $request->validate([
            'selectedNIKWaliMurid' => 'required|string|max:16',
            'selectedNIKMurid' => 'required|string|max:16',
            'asal_sekolah' => 'required|string|max:255',
            'keperluan' => 'required|string|max:1000',
        ]);

        $daftarsurat = daftarsurat::findOrFail($id);

        $pemohon = penduduk::where('NIK', $request->input('selectedNIKWaliMurid'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat->update([
            'nama_pemohon' => $pemohon->nama,
            'nik_pemohon' => $request->input('selectedNIKWaliMurid'),
        ]);

        $sktms = suratsktmsiswa::where('daftarsurat_id', $daftarsurat->id)->first();

        $sktms->update([
            'asal_sekolah'=>$request->input('asal_sekolah'),
            'nik_murid'=>$request->input('selectedNIKMurid'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat berhasil diperbarui');
    }

    public function editSKTM(Request $request, $id){
        $request->validate([
            'selectedNIK' => 'required|string|max:16',
            'keterangan' => 'required|string|max:1000',
            'keperluan' => 'required|string|max:1000',
        ]);

        $daftarsurat = daftarsurat::findOrFail($id);

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat->update([
            'nama_pemohon' => $pemohon->nama,
            'nik_pemohon' => $request->input('selectedNIK'),
        ]);

        $sktm = suratsktm::where('daftarsurat_id', $daftarsurat->id)->first();

        $sktm->update([
            'keterangan' => $request->input('keterangan'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat berhasil diperbarui');
    }

    public function editSKK(Request $request, $id){
        $request->validate([
            'selectedNIK' => 'required|string|max:16',
            'keterangan' => 'required|string|max:1000',
            'keperluan' => 'required|string|max:1000',
        ]);

        $daftarsurat = daftarsurat::findOrFail($id);

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat->update([
            'nama_pemohon' => $pemohon->nama,
            'nik_pemohon' => $request->input('selectedNIK'),
        ]);

        $skk = suratkehilangan::where('daftarsurat_id', $daftarsurat->id)->first();

        $skk->update([
            'keterangan' => $request->input('keterangan'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat berhasil diperbarui');
    }

    public function editSKWN(Request $request, $id){
        $request->validate([
            'selectedNIK' => 'required|string|max:16',
            'keterangan' => 'required|string|max:1000',
            'keperluan' => 'required|string|max:1000',
        ]);

        $daftarsurat = daftarsurat::findOrFail($id);

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat->update([
            'nama_pemohon' => $pemohon->nama,
            'nik_pemohon' => $request->input('selectedNIK'),
        ]);

        $skwn = suratwalinikah::where('daftarsurat_id', $daftarsurat->id)->first();

        $skwn->update([
            'keterangan' => $request->input('keterangan'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat berhasil diperbarui');
    }

    public function editSKP(Request $request, $id){
        $request->validate([
            'selectedNIK' => 'required|string|max:16',
            'penghasilan' => 'required|numeric|min:0',
            'keperluan' => 'required|string|max:1000',
        ]);

        $daftarsurat = daftarsurat::findOrFail($id);

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat->update([
            'nama_pemohon' => $pemohon->nama,
            'nik_pemohon' => $request->input('selectedNIK'),
        ]);

        $skp = suratpenghasilan::where('daftarsurat_id', $daftarsurat->id)->first();

        $skp->update([
            'penghasilan' => $request->input('penghasilan'),
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat berhasil diperbarui');
    }

    public function editSKCK(Request $request, $id){
        $request->validate([
            'selectedNIK' => 'required|string|max:16',
            'keperluan' => 'required|string|max:1000',
        ]);

        $daftarsurat = daftarsurat::findOrFail($id);

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat->update([
            'nama_pemohon' => $pemohon->nama,
            'nik_pemohon' => $request->input('selectedNIK'),
        ]);

        $skck = suratskck::where('daftarsurat_id', $daftarsurat->id)->first();

        $skck->update([
            'keperluan' => $request->input('keperluan')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat berhasil diperbarui');
    }

    public function editlain(Request $request, $id){
        $request->validate([
            'selectedNIK' => 'required|string|max:16',
            'perihal' => 'required|string|max:20'
        ]);

        $daftarsurat = daftarsurat::findOrFail($id);

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat->update([
            'nama_pemohon' => $pemohon->nama,
            'nik_pemohon' => $request->input('selectedNIK'),
            'jenis_surat' => $request->input('perihal')
        ]);

        return redirect('/user/operator/kesekretariatan')->with('success', 'Surat berhasil diperbarui');
    }
}
