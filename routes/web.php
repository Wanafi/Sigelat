<?php

use App\Models\Alat;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\ScanController;


Route::get('/scan/{kode}', function ($kode) {
    $alat = Alat::where('kode_barcode', $kode)->firstOrFail();

    if (Auth::check() && Auth::user()->is_admin) {
        return redirect()->route('filament.admin.resources.manajemen/alats.view', ['record' => $alat->id]);
    }

    return view('scan.alat-info', ['alat' => $alat]);
})->name('scan.barcode');

Route::put('/scan/update/{id}', function (Request $request, $id) {
    $alat = \App\Models\Alat::findOrFail($id);

    if (!Auth::check() || !Auth::user()->is_admin) {
        abort(403);
    }

    $alat->update([
        'status_alat' => $request->status_alat,
        'spesifikasi' => $request->spesifikasi,
    ]);

    return redirect()->route('scan.barcode', ['kode' => $alat->kode_barcode])->with('success', 'Alat berhasil diperbarui');
})->name('scan.barcode.update');

Route::put('/scan/{alat}/update-status', function (Request $request, Alat $alat) {
    $request->validate([
        'status' => 'required|in:Bagus,Rusak,Hilang',
    ]);

    $alat->update(['status_alat' => $request->status]);

    return back()->with('success', 'Status alat berhasil diperbarui.');
})->name('scan.barcode.update-status');

