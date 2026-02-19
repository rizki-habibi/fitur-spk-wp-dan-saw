<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\LaporanController;

// Proyek (Project Management)
Route::get('proyek', [ProyekController::class, 'index'])->name('proyek.index');
Route::post('proyek', [ProyekController::class, 'store'])->name('proyek.store');
Route::put('proyek/{proyek}', [ProyekController::class, 'update'])->name('proyek.update');
Route::delete('proyek/{proyek}', [ProyekController::class, 'destroy'])->name('proyek.destroy');
Route::post('proyek/{proyek}/activate', [ProyekController::class, 'activate'])->name('proyek.activate');

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Kriteria CRUD (modal-based, no create/edit pages)
Route::get('kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
Route::post('kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
Route::put('kriteria/{kriteria}', [KriteriaController::class, 'update'])->name('kriteria.update');
Route::delete('kriteria/{kriteria}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');

// Alternatif CRUD (modal-based, no create/edit pages)
Route::get('alternatif', [AlternatifController::class, 'index'])->name('alternatif.index');
Route::post('alternatif', [AlternatifController::class, 'store'])->name('alternatif.store');
Route::put('alternatif/{alternatif}', [AlternatifController::class, 'update'])->name('alternatif.update');
Route::delete('alternatif/{alternatif}', [AlternatifController::class, 'destroy'])->name('alternatif.destroy');

// Penilaian
Route::get('penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
Route::post('penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
Route::delete('penilaian/{alternatifId}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');

// Perhitungan
Route::get('perhitungan/wp', [PerhitunganController::class, 'wp'])->name('perhitungan.wp');
Route::get('perhitungan/saw', [PerhitunganController::class, 'saw'])->name('perhitungan.saw');
Route::get('perhitungan/perbandingan', [PerhitunganController::class, 'perbandingan'])->name('perhitungan.perbandingan');

// Excel Export
Route::get('export/kriteria', [ExcelController::class, 'exportKriteria'])->name('export.kriteria');
Route::get('export/alternatif', [ExcelController::class, 'exportAlternatif'])->name('export.alternatif');
Route::get('export/hasil', [ExcelController::class, 'exportHasil'])->name('export.hasil');
Route::get('export/allinone', [ExcelController::class, 'exportAllInOne'])->name('export.allinone');
Route::get('export/word', [ExcelController::class, 'exportWord'])->name('export.word');

// Excel Import
Route::post('import/kriteria', [ExcelController::class, 'importKriteria'])->name('import.kriteria');
Route::post('import/alternatif', [ExcelController::class, 'importAlternatif'])->name('import.alternatif');

// Laporan
Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
