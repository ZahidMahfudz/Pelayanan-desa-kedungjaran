<?php

namespace App\Http\Controllers;

use DOMDocument;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use App\Http\Controllers\Controller;
use App\Models\daftarsurat;
use App\Models\domisililuar;
use App\Models\namattdkades;
use App\Models\penduduk;
use App\Models\suratkehilangan;
use App\Models\suratpenghasilan;
use App\Models\suratsk;
use App\Models\suratskck;
use App\Models\suratskd;
use App\Models\suratsktm;
use App\Models\suratsktmsiswa;
use App\Models\suratusaha;
use App\Models\suratwalinikah;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFit;

class ExportController extends Controller
{
    public function exportWord($id)
    {
        $surat = daftarsurat::find($id);

        if($surat->jenis_surat === 'Surat Manual'){
            $manual = domisililuar::where('daftarsurat_id', $id)->first();

            $perihal = $manual->perihal;
        }
        else{
            $perihal = $surat->jenis_surat;
        }


        $penduduk = penduduk::where('NIK', $surat->nik_pemohon)->first();
        
        $paragraphStyle = array(
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
            'indentation' => array('firstLine' => 720), // Menjorok ke dalam 0.5 inci
            'spaceAfter' => 200, // Jarak setelah paragraf
            'spaceBefore' => 200, // Jarak sebelum paragraf
            'lineHeight' => 1.5 // Jarak antar baris
        );

        // Membuat instance PhpWord baru
        $phpWord = new PhpWord();

        // Menambahkan bagian baru ke dokumen
        $section = $phpWord->addSection();

        // Menambahkan header dengan teks dan logo
        $header = $section->addHeader();

         // Membuat tabel untuk header
         $table = $header->addTable();

         // Baris pertama tabel
         $table->addRow();
         // Kolom pertama untuk gambar
         $cell = $table->addCell(2000);
         $cell->addImage(public_path('image/logo-pekalongan.png'), array(
             'width' => 60,
             'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
             'wrappingStyle' => 'square'
         ));
 
         // Kolom kedua untuk teks
         $cell = $table->addCell(8000);
         $cell->addText(
             'PEMERINTAH KABUPATEN PEKALONGAN',
             array('name' => 'Times New Roman', 'size' => 16, 'bold' => true),
             array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
         );
         $cell->addText(
             'KECAMATAN SRAGI',
             array('name' => 'Times New Roman', 'size' => 16, 'bold' => true),
             array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
         );
         $cell->addText(
             'DESA KEDUNGJARAN',
             array('name' => 'Times New Roman', 'size' => 18, 'bold' => true),
             array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
         );
         $cell->addText(
             'Alamat: Jl. Raya Sragi-Bojong Km 2 No. 3 Pekalongan 51115',
             array('name' => 'Times New Roman', 'size' => 12),
             array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
         );
 
         // Menambahkan garis dengan style dua garis (atas garis tipis, bawah garis tebal)
         $header->addLine(
            array('weight' => 3, 'width' => 450, 'height' => 0, 'align' => 'center', 'color' => '#000000')
        );

        // Menambahkan teks ke dokumen
        $section->addText(
            strtoupper($perihal),
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true, 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE),
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
        );
        $section->addText(
            'No '.$surat->nomor_surat,
            array('name' => 'Times New Roman', 'size' => 12),
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
        );
        $section->addTextBreak(1);

        $section->addText(
            'Yang bertanda tangan dibawah ini, kami Kepala Desa Kedungjaran Kecamatan Sragi Kabupaten Pekalongan menerangkan bahwa:',
            array('name' => 'Times New Romah', 'size' => 12),
            $paragraphStyle
        );
        // Menambahkan tabel
        $table = $section->addTable();

        if($surat->jenis_surat === 'Surat Keterangan Usaha'){
            $usaha = suratusaha::where('daftarsurat_id', $id)->first();
            
            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $penduduk->tempat_lahir. ', ' .\Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $penduduk->agama,
                'Kewarganegaraan' => $penduduk->kewarganegaraan,
                'Pendidikan Terakhir' => $penduduk->pendidikan,
                'Pekerjaan' => $penduduk->pekerjaan,
                'Status Perkawinan' => $penduduk->status_perkawinan,
                'Tempat tinggal' => $penduduk->dusun.' RT '.$penduduk->RT.' RW '.$penduduk->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah',
                'Keterangan' => 'Bahwa orang tersebut benar-benar warga Desa Kedungjaran yang memiliki usaha '.$usaha->usaha,
                'Keperluan' => $usaha->keperluan
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Demikian surat keterangan usaha ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="suratusaha-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        elseif($surat->jenis_surat === 'Surat Keterangan'){
            $keterangan = suratsk::where('daftarsurat_id', $id)->first();

            if(!$keterangan){
                return redirect()->back()->with(['error' => 'Surat SKk tidak ditemukan']);
            }
            
            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $penduduk->tempat_lahir. ', ' .\Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $penduduk->agama,
                'Kewarganegaraan' => $penduduk->kewarganegaraan,
                'Pendidikan Terakhir' => $penduduk->pendidikan,
                'Pekerjaan' => $penduduk->pekerjaan,
                'Status Perkawinan' => $penduduk->status_perkawinan,
                'Tempat tinggal' => $penduduk->dusun.' RT '.$penduduk->RT.' RW '.$penduduk->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah' ,
                'Keterangan' => $keterangan->keterangan,
                'Keperluan' => $keterangan->keperluan
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Demikian surat keterangan ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="suratketerangan-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        elseif($surat->jenis_surat === 'Surat Keterangan Domisili'){
            $domisili = suratskd::where('daftarsurat_id', $id)->first();

            if(!$domisili){
                return redirect()->back()->with(['error' => 'Surat SKD tidak ditemukan']);
            }
            
            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $penduduk->tempat_lahir. ', ' .\Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $penduduk->agama,
                'Kewarganegaraan' => $penduduk->kewarganegaraan,
                'Pendidikan Terakhir' => $penduduk->pendidikan,
                'Pekerjaan' => $penduduk->pekerjaan,
                'Status Perkawinan' => $penduduk->status_perkawinan,
                'Tempat tinggal' => $penduduk->dusun.' RT '.$penduduk->RT.' RW '.$penduduk->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah' ,
                'keterangan' => $domisili->keterangan,
                'Keperluan' => $domisili->keperluan
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Demikian surat keterangan ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="suratdomisili-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        elseif($surat->jenis_surat === 'Surat Keterangan Tidak Mampu'){
            $sktm = suratsktm::where('daftarsurat_id', $id)->first();

            if(!$sktm){
                return redirect()->back()->with(['error' => 'Surat SKk tidak ditemukan']);
            }
            
            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $penduduk->tempat_lahir. ', ' .\Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $penduduk->agama,
                'Kewarganegaraan' => $penduduk->kewarganegaraan,
                'Pendidikan Terakhir' => $penduduk->pendidikan,
                'Pekerjaan' => $penduduk->pekerjaan,
                'Status Perkawinan' => $penduduk->status_perkawinan,
                'Tempat tinggal' => $penduduk->dusun.' RT '.$penduduk->RT.' RW '.$penduduk->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah' ,
                'keterangan' => 'Bahwa orang tersebut benar warga Desa Kedungjaran Kecamatan Sragi Kabupaten Pekalongan yang perekonomiannya tidak mampu dan '.$sktm->keterangan,
                'Keperluan' => $sktm->keperluan
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Demikian surat keterangan tidak mampu ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="surattidakmampu-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        elseif($surat->jenis_surat === 'Surat Keterangan Tidak Mampu Siswa'){
            $sktmsiswa = suratsktmsiswa::where('daftarsurat_id', $id)->first();

            if(!$sktmsiswa){
                return redirect()->back()->with(['error' => 'Surat SKk tidak ditemukan']);
            }
            
            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $penduduk->tempat_lahir. ', ' .\Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $penduduk->agama,
                'Kewarganegaraan' => $penduduk->kewarganegaraan,
                'Pendidikan Terakhir' => $penduduk->pendidikan,
                'Pekerjaan' => $penduduk->pekerjaan,
                'Status Perkawinan' => $penduduk->status_perkawinan,
                'Tempat tinggal' => $penduduk->dusun.' RT '.$penduduk->RT.' RW '.$penduduk->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah' ,
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Adalah benar-benar orang tua dan wali murid yang perekonomiannya kurang mampu dari:',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $table = $section->addTable();

            $murid = penduduk::where('NIK', $sktmsiswa->nik_murid)->first();

            $datamurid = [
                'Nama' => $murid->nama,
                'NIK' => $murid->NIK,
                'Tempat/tanggal lahir' => $murid->tempat_lahir. ', ' .\Carbon\Carbon::parse($murid->tanggal_lahir)->format('d-m-Y'),
                'Asal Sekolah' => $sktmsiswa->asal_sekolah,
                'Tempat tinggal' => $murid->dusun.' RT '.$murid->RT.' RW '.$murid->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah' ,
            ];

            foreach ($datamurid as $kunci => $nilai) {
                $table->addRow();
                $table->addCell(3000)->addText($kunci, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($nilai, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                $sktmsiswa->keperluan,
                array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
                $paragraphStyle
            );
            $section->addText(
                'Demikian surat keterangan tidak mampu ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="surattidakmampusiswa-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        elseif($surat->jenis_surat === 'Surat Keterangan Kehilangan'){
            $kehilangan = suratkehilangan::where('daftarsurat_id', $id)->first();

            if(!$kehilangan){
                return redirect()->back()->with(['error' => 'Surat SKk tidak ditemukan']);
            }
            
            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $penduduk->tempat_lahir. ', ' .\Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $penduduk->agama,
                'Kewarganegaraan' => $penduduk->kewarganegaraan,
                'Pendidikan Terakhir' => $penduduk->pendidikan,
                'Pekerjaan' => $penduduk->pekerjaan,
                'Status Perkawinan' => $penduduk->status_perkawinan,
                'Tempat tinggal' => $penduduk->dusun.' RT '.$penduduk->RT.' RW '.$penduduk->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah' ,
                'keterangan' => $kehilangan->keterangan,
                'Keperluan' => $kehilangan->keperluan
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Demikian surat keterangan kehilangan ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="suratkehilangan-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        elseif($surat->jenis_surat === 'Surat Keterangan Menjadi Wali Nikah'){
            $walinikah = suratwalinikah::where('daftarsurat_id', $id)->first();

            if(!$walinikah){
                return redirect()->back()->with(['error' => 'Surat SKk tidak ditemukan']);
            }
            
            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $penduduk->tempat_lahir. ', ' .\Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $penduduk->agama,
                'Kewarganegaraan' => $penduduk->kewarganegaraan,
                'Pendidikan Terakhir' => $penduduk->pendidikan,
                'Pekerjaan' => $penduduk->pekerjaan,
                'Status Perkawinan' => $penduduk->status_perkawinan,
                'Tempat tinggal' => $penduduk->dusun.' RT '.$penduduk->RT.' RW '.$penduduk->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah' ,
                'keterangan' => $walinikah->keterangan,
                'Keperluan' => $walinikah->keperluan
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Demikian surat keterangan menjadi wali nikah ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="suratwalinikah-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        elseif($surat->jenis_surat === 'Surat Keterangan Penghasilan'){
            $penghasilan = suratpenghasilan::where('daftarsurat_id', $id)->first();

            if(!$penghasilan){
                return redirect()->back()->with(['error' => 'Surat SKk tidak ditemukan']);
            }
            
            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $penduduk->tempat_lahir. ', ' .\Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $penduduk->agama,
                'Kewarganegaraan' => $penduduk->kewarganegaraan,
                'Pendidikan Terakhir' => $penduduk->pendidikan,
                'Pekerjaan' => $penduduk->pekerjaan,
                'Status Perkawinan' => $penduduk->status_perkawinan,
                'Tempat tinggal' => $penduduk->dusun.' RT '.$penduduk->RT.' RW '.$penduduk->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah' ,
                'keterangan' => 'Bahwa orang tersebut benar warga Desa Kedungjaran yang pekerjaannya '.$penduduk->pekerjaan.' dengan penghasilan Rp.'.number_format($penghasilan->penghasilan).' per bulan',
                'Keperluan' => $penghasilan->keperluan
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Demikian surat keterangan penghasilan ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('Pemegang Surat', array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText($surat->nama_pemohon, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="suratpenghasilan-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        elseif($surat->jenis_surat === 'Surat Pengantar SKCK'){
            $skck = suratskck::where('daftarsurat_id', $id)->first();

            if(!$skck){
                return redirect()->back()->with(['error' => 'Surat SKk tidak ditemukan']);
            }
            
            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $penduduk->tempat_lahir. ', ' .\Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $penduduk->agama,
                'Kewarganegaraan' => $penduduk->kewarganegaraan,
                'Pendidikan Terakhir' => $penduduk->pendidikan,
                'Pekerjaan' => $penduduk->pekerjaan,
                'Status Perkawinan' => $penduduk->status_perkawinan,
                'Tempat tinggal' => $penduduk->dusun.' RT '.$penduduk->RT.' RW '.$penduduk->RW.' Desa Kedungjaran Kecamatan Sragi Kab. Pekalongan Jawa Tengah' ,
                'keterangan' => 'Bahwa orang tersebut benar-benar warga Desa Kedungjaran yang berkelakuan baik dan tidak pernah terlibat perkara apapun',
                'Keperluan' => $skck->keperluan
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Demikian surat keterangan penghasilan ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('Pemegang Surat', array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText($surat->nama_pemohon, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            //mengetahui camat
            $section->addText(
                'Mengetahui',
                array('name' => 'Times New Romah', 'size' => 12),
                array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
            );
            $section->addText(
                'Camat Sragi',
                array('name' => 'Times New Romah', 'size' => 12),
                array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
            );

            $section->addTextBreak(2);

            $section->addText(
                '(..................................)',
                array('name' => 'Times New Romah', 'size' => 12),
                array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
            );

            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="suratskck-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        elseif($surat->jenis_surat === 'Surat Manual'){
            $manual = domisililuar::where('daftarsurat_id', $id)->first();

            if(!$manual){
                return redirect()->back()->with(['error' => 'Surat SKk tidak ditemukan']);
            }
            
            $perihal = $manual->perihal;

            // Data yang akan dimasukkan ke dalam tabel
            $data = [
                'Nama' => $surat->nama_pemohon,
                'NIK' => $surat->nik_pemohon,
                'Tempat/tanggal lahir' => $manual->tempat_lahir_pemohon. ', ' .\Carbon\Carbon::parse($manual->tanggal_lahir)->format('d-m-Y'),
                'Jenis Kelamin' => $manual->jenis_kelamin_pemohon == 'P' ? 'Perempuan' : 'Laki-laki',
                'Agama' => $manual->agama_pemohon,
                'Kewarganegaraan' => $manual->kewarganegaraan_pemohon,
                'Pendidikan Terakhir' => $manual->pendidikan_pemohon,
                'Pekerjaan' => $manual->pekerjaan_pemohon,
                'Status Perkawinan' => $manual->status_perkawinan_pemohon,
                'Tempat tinggal' => $manual->alamat_pemohon ,
                'keterangan' => $manual->keterangan,
                'Keperluan' => $manual->keperluan
            ];

            // Menambahkan data ke dalam tabel
            foreach ($data as $key => $value) {
                $table->addRow();
                $table->addCell(3000)->addText($key, array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(100)->addText(':', array('name' => 'Times New Roman', 'size' => 12));
                $table->addCell(6000)->addText($value, array('name' => 'Times New Roman', 'size' => 12, 'bold' => ($key == 'Nama')));
            }

            $section->addText(
                'Demikian surat keterangan '.$perihal.' ini kami buat untuk dipergunakan sebagaimana mestinya',
                array('name' => 'Times New Roman', 'size' => 12),
                $paragraphStyle
            );

            $namattdkades = namattdkades::first();
            $date = \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('d F Y'); // Contoh data dari database
            $head = 'Kepala Desa Kedungjaran'; // Contoh data dari database
            $name = $namattdkades->nama_kades; // Contoh data dari database
            $signatureImagePath = public_path('image/' . $namattdkades->nama_gambar); // Contoh data dari database

            // Menambahkan tabel untuk tanda tangan
            $table = $section->addTable();

            // Baris pertama: Tanggal dan Jabatan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText('Kedungjaran, '. $date, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($head, array('name' => 'Times New Roman', 'size' => 12), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris kedua: Gambar tanda tangan
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addImage($signatureImagePath, array('width' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

            // Baris ketiga: Nama
            $table->addRow();
            $table->addCell(5000)->addText('');
            $table->addCell(5000)->addText($name, array('name' => 'Times New Roman', 'size' => 12, 'bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));


            // Menyimpan dokumen ke dalam memori untuk didownload
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Mengatur header untuk mendownload file
            return response()->stream(function() use ($objWriter) {
                $objWriter->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="suratmanual-'.$surat->nama_pemohon.'-'.$surat->tanggal_surat.'.docx"',
            ]);
        }

        

    }

}
