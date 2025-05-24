<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FileUploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,png,jpg,jpeg|max:20480',
        ]);

        $path = $request->file('file')->store('uploads', 'public');
        $filename = basename($path);

        // preview URL
$baseUrl = 'https://' . request()->getHost(); // ensures HTTPS
        $previewUrl = $baseUrl . '/preview/' . $filename;

        $qr = QrCode::format('svg')->size(300)->generate($previewUrl);

        return view('upload', [
            'qr' => $qr,
            'url' => $previewUrl,
        ]);
    }

    public function generateUrlQr(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');
        $qr = QrCode::format('svg')->size(300)->generate($url);

        return view('upload', [
            'qr' => $qr,
            'url' => $url,
        ]);
    }

    public function preview($filename)
{
    $filePath = 'storage/uploads/' . $filename;

    if (!file_exists(public_path($filePath))) {
        abort(404);
    }

    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    if ($extension === 'pdf') {
        return response()->file(public_path($filePath), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);

    return view('preview', [
        'filePath' => asset($filePath),
        'isImage' => $isImage,
        'isPdf' => false,
        'filename' => $filename
    ]);
}

}