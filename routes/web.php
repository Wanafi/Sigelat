<?php

use App\Models\Alat;
use App\Models\Gelar;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/scan/{kode}', function ($kode, Request $request) {
    $alat = Alat::where('kode_barcode', $kode)->firstOrFail();
    return view('scan.alat-info', ['alat' => $alat]);
})->name('scan.barcode');

// ✅ Verifikasi password admin sebelum bisa update status
Route::post('/scan/verifikasi/{id}', function (Request $request, $id) {
    $alat = Alat::findOrFail($id);

    $request->validate([
        'akses_password' => 'required',
    ]);

    // Password admin
    $passwordBenar = 'plnadmin123';

    if ($request->akses_password === $passwordBenar) {
        session(['akses_diizinkan' => true]);
        return redirect()->route('scan.barcode', ['kode' => $alat->kode_barcode])
                         ->with('success', 'Akses admin berhasil.');
    } else {
        return redirect()->route('scan.barcode', ['kode' => $alat->kode_barcode])
                         ->with('akses_error', 'Password salah.');
    }
})->name('scan.barcode.verifikasi');

// ✅ Update status alat (hanya jika sudah akses)
Route::put('/scan/{alat}/update-status', function (Request $request, Alat $alat) {
    $request->validate([
        'status' => 'required|in:Bagus,Rusak,Hilang',
    ]);

    if (!session('akses_diizinkan')) {
        abort(403, 'Tidak memiliki akses untuk memperbarui status.');
    }

    $alat->update([
        'status_alat' => $request->status,
    ]);

    return back()->with('success', 'Status alat berhasil diperbarui.');
})->name('scan.barcode.update-status');



Route::get('/admin/gelars/{id}/formulir', function ($id) {
    $gelar = Gelar::with(['mobil.alats', 'detailGelars.alat'])->findOrFail($id);
    return view('filament.resources.gelar-resource.pages.formulir', compact('gelar'));
})->name('admin.gelars.formulir');