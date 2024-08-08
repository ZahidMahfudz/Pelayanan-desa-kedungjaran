<?php

use App\Http\Controllers\editSuratConttoller;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\OperatController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {return view('login');});

Route::middleware(['guest'])->group(function(){
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
});

Route::get('/home', function() {return redirect('/userpermissions');});

Route::middleware(['auth'])->group(function(){
    Route::get('/userpermissions', [SesiController::class, 'terobos']);
    Route::get('/user/admin', [UserController::class, 'admin']);
    Route::get('/user/operator/dashboard', [OperatController::class, 'showdashboard']);
    Route::get('/logout', [SesiController::class, 'logout']);

    //Route operator
    //kesekretariatan
    Route::get('/user/operator/kesekretariatan', [OperatController::class, 'showKesekretariatan']);
    Route::get('/hapussurat/{id}', [SuratController::class, 'deleteSurat']);
    Route::get('/cetaksurat/{id}', [SuratController::class, 'cetakSurat']);
    Route::get('/rekapsurat', [OperatController::class, 'rekapsurat']);
    Route::get('/hapusdaftarsurat', [OperatController::class, 'hapusdaftarsurat']);
    //Data Penduduk
    Route::get('/user/operator/datapenduduk', [OperatController::class, 'showPenduduk'])->name('showPenduduk');
    Route::post('/editpenduduk/{NIK}', [OperatController::class, 'editpenduduk'])->name('editpenduduk');
    Route::get('/user/operator/show_tambah_penduduk', [OperatController::class, 'showAddPenduduk']);
    Route::post('/user/operator/addpenduduk', [OperatController::class, 'addPenduduk']);
    Route::get('/deletependuduk/{NIK}', [OperatController::class, 'deletePenduduk']);
    Route::post('/importPenduduk', [OperatController::class, 'importPenduduk'])->name('importPenduduk');
    //Buat Surat
    Route::get('/user/operator/buatsurat', [OperatController::class, 'showbuatsurat']);
    //editsurat
    Route::get('/showeditsurat/{id}', [editSuratConttoller::class, 'showedit']);
    //Tanda tangan kades
    Route::get('/user/operator/ttdkades', [OperatController::class, 'showTTD']);
    Route::post('/addnamattdkades', [OperatController::class, 'addnamattdkades'])->name('addnamattdkades');
    Route::post('/edit_namattdkades/{id}', [OperatController::class, 'editTTD'])->name('edit_namattdkades');

    //coba eksport word
    Route::get('/export-word/{id}', [ExportController::class, 'exportWord']);

    //show form surat
    //SKD
    Route::get('/buatformSKD', [SuratController::class, 'showSKD']);
    Route::post('/submit_domisili', [SuratController::class, 'submitDomisili'])->name('submit_domisili');
    Route::post('edit_domisili/{id}', [editSuratConttoller::class, 'editdomisili'])->name('edit_domisili');

    //SKD luar
    Route::get('/buatformSKDluar', [SuratController::class, 'showSKDluar']);
    Route::post('/submit_skd_luar', [SuratController::class, 'submitSKDLuar'])->name('submit_skd_luar');
    Route::post('/edit_manual/{id}', [editSuratConttoller::class, 'editManual'])->name('edit_manual');

    //SK
    Route::get('/buatformSK', [SuratController::class, 'showSK']);
    Route::post('/submit_sk', [SuratController::class, 'submitSK'])->name('submit_sk');
    Route::post('/edit_SK/{id}', [editSuratConttoller::class, 'editketerangan'])->name('edit_SK');

    //SKTM siswa
    Route::get('/buatformSKTMsiswa', [SuratController::class, 'showSKTMsiswa']);
    Route::post('/submit_SKTMS', [SuratController::class, 'submitSKTMS'])->name('submit_SKTMS');
    Route::post('/edit_SKTMS/{id}', [editSuratConttoller::class, 'editSKTMS'])->name('edit_SKTMS');

    //SKTM
    Route::get('/buatformSKTM', [SuratController::class, 'showSKTM']);
    Route::post('/submit_sktm', [SuratController::class, 'submitSKTM'])->name('submit_SKTM');
    Route::post('/edit_sktm/{id}', [editSuratConttoller::class, 'editSKTM'])->name('edit_sktm');

    //SKK
    Route::get('/buatformSKK', [SuratController::class, 'showSKK']);
    Route::post('/submit_skk', [SuratController::class, 'submitSKK'])->name('submit_skk');
    route::post('/edit_skk/{id}', [editSuratConttoller::class, 'editSKK'])->name('edit_skk');

    //SKWALI
    Route::get('/buatformwali', [SuratController::class, 'showSKWALI']);
    Route::post('/submit_skwalinikah', [SuratController::class, 'submitSKwalinikah'])->name('submit_skwalinikah');
    Route::post('/edit_SKWN/{id}', [editSuratConttoller::class, 'editSKWN'])->name('edit_SKWN');

    //SKP
    Route::get('/buatformskp', [SuratController::class, 'showSKP']);
    Route::post('submit_skp', [SuratController::class, 'submitSKP'])->name('submit_skp');
    Route::post('/update_skp/{id}', [editSuratConttoller::class, 'editSKP'])->name('update_skp');

    //SKU
    Route::get('/buatformsku', [SuratController::class, 'showSKU']);
    Route::post('/submit_SKU', [SuratController::class, 'submitSKU'])->name('submit_SKU');
    Route::post('/edit_SKU/{id}', [editSuratConttoller::class, 'editSKU'])->name('edit_SKU');

    //SKCK
    Route::get('/buatformSKCK', [SuratController::class, 'showSKCK']);
    Route::post('/submit_skck', [SuratController::class, 'submitSKCK'])->name('submit_skck');
    Route::post('/edit_SKCK/{id}', [editSuratConttoller::class, 'editSKCK'])->name('edit_SKCK');

    //lain-lain
    Route::get('/buatformlain', [SuratController::class, 'showLain']);
    Route::post('submit_lain', [SuratController::class, 'submitLain'])->name('submit_lain');
    Route::post('/edit_lain/{id}', [editSuratConttoller::class, 'editlain'])->name('edit_lain');

    //isiform penduduk
    Route::get('searchPemohon', [SuratController::class, 'Caripenduduk'])->name('Caripemohon');
    Route::get('showdatapenduduk/{NIK}', [SuratController::class, 'showdatapenduduk'])->name('showdatapenduduk');

    //cetak surat
    Route::post('/user/operator/submit_domisili', [OperatController::class, 'suratdomisili']);


});

