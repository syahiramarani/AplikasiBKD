<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BebanDosenController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistribusiHistoryController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VeruficationController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('bkd.login');
});


Route::resource('dosen', DosenController::class);
Route::resource('jurusan', JurusanController::class);

Route::get('/login', fn() => view('bkd.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', fn() => view('bkd.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);

// routes/web.php
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/dashboard', [BebanDosenController::class, 'index'])->name('dashboard');
// Route::get('/beban/create', [BebanDosenController::class, 'create']);
// Route::post('/beban/store', [BebanDosenController::class, 'store']);

Route::group(['middleware' => ['auth', 'check_role:kajur']], function () {
    Route::get('/verify', [VeruficationController::class, 'index']);
});

Route::get('/user/delete/{id}', [UserController::class, 'delete']);

Route::group(['middleware' => ['check_role:kajur', 'check_status']], function () {
    Route::get('/kajur', fn() => 'halaman kajur');

});
Route::group(['middleware' => ['check_role:admin,p4m']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

});

Route::get('/dosen/create', [DosenController::class, 'create']);
Route::get('/dosen/create', [DosenController::class, 'create'])->name('dosen.create');
Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store');
Route::get('/dosen/{id}/edit', [DosenController::class, 'edit']);
Route::put('/dosen/{id}', [DosenController::class, 'update']);

Route::get('/get-prodi/{id}', [DosenController::class, 'getProdi']);
Route::get('/get-bidang/{jurusan_id}', [DosenController::class, 'getBidang']);

Route::get('/jurusan', [JurusanController::class, 'index'])
    ->name('jurusan.index');

Route::post('/jurusan/store', [JurusanController::class, 'store'])->name('jurusan.store');

Route::get('/prodi', [ProdiController::class, 'index'])
    ->name('prodi.index');

Route::resource('prodi', ProdiController::class);

Route::post(
    '/prodi/store',
    [ProdiController::class, 'store']
)
    ->name('prodi.store');

Route::post(
    '/prodi/update/{id}',
    [ProdiController::class, 'update']
)
    ->name('prodi.update');

Route::delete(
    '/prodi/delete/{id}',
    [ProdiController::class, 'destroy']
)
    ->name('prodi.destroy');

Route::resource('matakuliah', MataKuliahController::class);
Route::post('/MataKuliahImport', [MataKuliahController::class, 'import'])->name('matakuliah.import');
Route::put('/matakuliah/{matakuliah}', [MataKuliahController::class, 'update'])
    ->name('matakuliah.update');
Route::delete('/matakuliah/{matakuliah}', [MataKuliahController::class, 'destroy'])
    ->name('matakuliah.destroy');

Route::post('/DosenImport', [DosenController::class, 'import'])->name('dosen.import');
Route::resource('beban-dosen', BebanDosenController::class);

Route::post('/distribusi/simpan-history', [DistribusiController::class, 'simpanHistory']);
Route::prefix('distribusi')->name('distribusi.')->middleware(['auth'])->group(function () {
    Route::get('/', [DistribusiController::class, 'index'])->name('index');
    Route::post('/proses', [DistribusiController::class, 'proses'])->name('proses');
    Route::post('/simpan', [DistribusiController::class, 'simpan'])->name('simpan');

    Route::delete('/{distribusi}', [DistribusiController::class, 'hapus'])->name('hapus');
    Route::put('/{distribusi}', [DistribusiController::class, 'update'])->name('update');
    Route::get('/{distribusi}/kandidat-edit/{mataKuliah}', [DistribusiController::class, 'kandidatEdit'])
        ->name('kandidatEdit');
    Route::get('/dosen-kandidat/{mataKuliah}', [DistribusiController::class, 'dosenKandidat'])->name('dosen-kandidat');
    Route::get('/kelas-tersedia', [DistribusiController::class, 'kelasTersedia'])->name('kelas-tersedia');
    Route::get('/rekap', [DistribusiController::class, 'rekap'])->name('rekap');

    Route::get('/ga-progress', function () {
        return response()->json(session('ga_progress', [
            'current' => 0,
            'max' => 1,
        ]));
    })->name('ga-progress');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/rekap-distribusi', [DistribusiHistoryController::class, 'index'])
        ->name('rekap-distribusi.index');

    Route::get('/rekap-distribusi/batch/{batch}', [DistribusiHistoryController::class, 'show'])
        ->name('rekap-distribusi.show');

    Route::get('/rekap-distribusi/batch/{batch}/dosen/{dosen}', [DistribusiHistoryController::class, 'detailDosen'])
        ->name('rekap-distribusi.dosen-detail');
});



Route::middleware(['auth', 'check_status', 'check_role:p4m'])->group(function () {

    // Dashboard PPMPP
    Route::get('/ppmpp', fn() => 'Dashboard PPMPP');

    // Kelola User (hanya PPMPP)
    Route::get('/User', [UserController::class, 'index']);
    Route::get('/User/create', [UserController::class, 'create']);
    Route::post('/User/store', [UserController::class, 'store']);
    Route::get('/User/show/{id}', [UserController::class, 'show']);
    Route::get('/User/edit/{id}', [UserController::class, 'edit']);
    Route::post('/User/update/{id}', [UserController::class, 'update']);
    Route::get('/User/delete/{id}', [UserController::class, 'delete']);
});

Route::middleware(['auth', 'check_status', 'check_role:kaprodi,p4m'])->group(function () {
    Route::get('/kaprodi', fn() => 'Dashboard Kaprodi');

    // Kaprodi: kelola data master (sesuai use case)
    Route::resource('/dosen', DosenController::class);
    Route::resource('/mata-kuliah', MataKuliahController::class);
    Route::resource('/beban-dosen', BebanDosenController::class);
    Route::resource('/angkatan', AngkatanController::class);
    Route::resource('/kelas', KelasController::class);

    // Kaprodi: lihat hasil & rekap distribusi
    Route::get('/distribusi/hasil', [DistribusiController::class, 'hasil']);
    Route::get('/distribusi/rekap', [DistribusiController::class, 'rekap']);
});


Route::middleware(['auth', 'check_status', 'check_role:kajur,p4m'])->group(function () {
    Route::get('/kajur', function () {
        return redirect('/dosen');
    });
    // Kajur: hanya lihat data dosen & mata kuliah
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    Route::get('/dosen/{dosen}', [DosenController::class, 'show'])->name('dosen.show');

    Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])->name('mata-kuliah.index');
    Route::get('/mata-kuliah/{mata_kuliah}', [MataKuliahController::class, 'show'])->name('mata-kuliah.show');

    // Kajur: distribusi, rekap, laporan (lihat/cetak)
    Route::get('/distribusi/hasil', [DistribusiController::class, 'hasil'])->name('distribusi.hasil');
    Route::get('/distribusi/rekap', [DistribusiController::class, 'rekap'])->name('distribusi.rekap');
    Route::get('/distribusi/laporan', [DistribusiController::class, 'cetak'])->name('distribusi.laporan');
});







Route::get('/logout', [AuthController::class, 'logout']);