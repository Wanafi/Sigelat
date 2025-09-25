<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class QrCodeController extends Controller
{
    public function print(Alat $alat)
    {
        $url = 'https://sigelat.web.id/scan/' . $alat->kode_barcode;

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        $qrSvg = $writer->writeString($url);
        $base64 = base64_encode($qrSvg);

        return view('alat.print-qr', [
            'alat' => $alat,
            'qrCode' => 'data:image/svg+xml;base64,' . $base64,
        ]);
    }
}

