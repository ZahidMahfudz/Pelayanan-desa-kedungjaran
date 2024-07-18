<?php

namespace App\Http\Controllers;

use App\Models\daftarsurat;
use App\Models\penduduk;
use App\Models\suratskd;
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

        $pemohon = penduduk::where('NIK', $request->input('selectedNIK'))->first();

        if(!$pemohon){
            return redirect()->back()->with(['erros' => 'Pemohon tidak ditemukan']);
        }

        $daftarsurat = daftarsurat::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'tanggal_surat' => now(),
            'jenis_surat'=> 'SKD',
            'pemohon'=> $request->input('selectedNIK'),
            'status_surat'=> 'belum_cetak',
            'status_ttd'=> 'belum'
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

    public function cetakSurat($id){
        // Fetch the daftarsurat entry by its ID
        $daftarsurat = daftarsurat::find($id);

        
        // Check if the daftarsurat entry exists
        if(!$daftarsurat){
            return redirect()->back()->with(['error' => 'Surat tidak ditemukan']);
        }

        $penduduk = penduduk::where('NIK', $daftarsurat->pemohon)->first();

        if(!$penduduk){
            return redirect()->back()->with(['error' => 'Penduduk tidak ditemukan']);
        }

        $tanggal_surat = \Carbon\Carbon::parse($daftarsurat->tanggal_surat)->locale('id')->translatedFormat('d F Y');

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
            return view('cetaksurat.domisili', compact('judulsurat','daftarsurat', 'suratskd', 'penduduk', 'tanggal_surat'));
        }

        // Handle other types of keperluan if needed
        // For now, redirect back with an error message if not SKD
        return redirect()->back()->with(['error' => 'Jenis surat tidak valid']);
        
    }
}
