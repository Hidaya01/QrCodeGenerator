<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\QrCode as QrCodeModel;
use App\Models\QrFile;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class QrFileController extends QrCodeController
{
    public function showUploadForm($type)
    {
        abort_unless(in_array($type, ['pdf', 'vcard']), 404);
        return view('qr-upload', ['type' => $type]);
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pdf,vcard',
            'file' => 'required|file|mimes:' . ($request->type === 'pdf' ? 'pdf' : 'vcf,txt') . '|max:2048'
        ]);

        // Store the file
        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        // First create the QR code record with a temporary URL
        $qrCode = QrCodeModel::create([
            'type' => $request->type,
            'content' => 'temporary-url', // Will be updated later
            'user_id' => auth()->id()
        ]);

        // Now create the file upload record with the valid qr_code_id
        $fileUpload = QrFile::create([
            'qr_code_id' => $qrCode->id,
            'original_name' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $request->type
        ]);

        // Update the QR code with the correct preview URL
        $qrCode->update([
            'content' => route('file.preview', ['id' => $fileUpload->id])
        ]);

        // Generate the QR code image
        $qrImage = QrCode::size(300)->generate($qrCode->content);

        // Return the view directly like QrCodeController does
        return view('qr-result', [
            'qrCode' => $qrImage,
            'qrContent' => $qrCode->content,
            'type' => $request->type,
            'qrRecord' => $qrCode
        ]);
    }
    public function preview($id)
    {
        $fileUpload = QrFile::findOrFail($id);
        $filePath = storage_path('app/public/' . $fileUpload->storage_path);

        abort_unless(file_exists($filePath), 404, "File not found");

        $extension = strtolower(pathinfo($fileUpload->original_name, PATHINFO_EXTENSION));
        $isMobile = preg_match('/Android|iPhone|iPad/i', request()->header('User-Agent'));

        return view('preview', [
            'filePath' => $fileUpload->public_url,
            'isImage' => in_array($extension, ['jpg', 'jpeg', 'png', 'gif']),
            'isPdf' => ($extension === 'pdf'),
            'filename' => $fileUpload->original_name,
            'isMobile' => $isMobile,
            'pdfEmbedUrl' => $isMobile 
                ? "https://docs.google.com/viewer?embedded=true&url=".urlencode($fileUpload->public_url)
                : $fileUpload->public_url."#toolbar=0&navpanes=0",
            'downloadUrl' => $fileUpload->download_url
        ]);
    }

    public function download($id)
    {
        $fileUpload = QrFile::findOrFail($id);
        $filePath = storage_path('app/public/' . $fileUpload->storage_path);

        abort_unless(file_exists($filePath), 404);

        return response()->download($filePath, $fileUpload->original_name, [
            'Content-Type' => $fileUpload->mime_type
        ]);
    }
}