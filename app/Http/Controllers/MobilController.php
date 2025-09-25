<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class MobilController extends Controller
{
    public function printAllQr(Mobil $mobil)
    {
        // pastikan relasi mobil->alats ada di model Mobil
        $alats = $mobil->alats()->get();

        // setup Bacon renderer (size 200)
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        // generate svg base64 untuk setiap alat
        $qrSvgs = [];
        foreach ($alats as $alat) {
            $url = url('/scan/' . $alat->kode_barcode);
            $qrSvg = $writer->writeString($url); // SVG string
            $qrSvgs[$alat->id] = base64_encode($qrSvg);
        }

        // render view dan kirim data (notice nama variabel 'mobil' not 'record')
        return view('pdf.qr-alat-mobil', compact('mobil', 'alats', 'qrSvgs'));
    }
}
