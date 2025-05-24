<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QrCode as QrCodeModel;

class QrCodeController extends Controller
{
    public function showOptions()
    {
        return view('qr-options');
    }

    public function generateQr(Request $request)
    {
        $type = $request->input('type', 'website');
        
        if ($type === 'website') {
            return $this->generateWebsiteQr($request);
        }
        if ($type === 'pdf') {
            return redirect()->route('qr.upload.form', ['type' => $type]);
        }
        return redirect()->route("qr.{$type}.form");
    }

    protected function generateWebsiteQr(Request $request)
    {
        $request->validate(['url' => 'required|url']);
        $url = $request->input('url');
        
        $qrCode = QrCodeModel::create([
            'type' => 'website',
            'content' => $url,
            'user_id' => auth()->id()
        ]);
        
        return view('qr-result', [
            'qrCode' => QrCode::size(300)->generate($url),
            'qrContent' => $url,
            'type' => 'website',
            'qrRecord' => $qrCode
        ]);
    }

    protected function showQrResult($content, $type, $qrRecord = null, $metaData = null)
    {
        return view('qr-result', [
            'qrCode' => QrCode::size(300)->generate($content),
            'qrContent' => $content,
            'type' => $type,
            'qrRecord' => $qrRecord,
            'metaData' => $metaData
        ]);
    }
}