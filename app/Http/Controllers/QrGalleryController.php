<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\QrCode as QrCodeModel ;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\QrGallery;
use App\Models\QrGalleryImage;

class QrGalleryController extends QrCodeController
{
    public function showImageForm()
    {
        return view('image-form');
    }


public function generateImageQr(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', 
            'title' => 'nullable|string|max:100',
            'button_url' => 'nullable|url',
            'button_text' => 'nullable|string|max:50',
            'grid_view' => 'nullable|boolean' 
        ]);

        // Store images
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('uploads/gallery', 'public');
            $imagePaths[] = '/storage/' . str_replace('public/', '', $path);
        }

        // Create QR code record
        $qrCode = QrCodeModel::create([
            'type' => 'gallery',
            'content' => route('gallery.view', ['id' => Str::uuid()]),
            'user_id' => auth()->id() ?? null
        ]);

        // Create gallery
        $gallery = QrGallery::create([
            'qr_code_id' => $qrCode->id,
            'title' => $request->input('title'),
            'button_url' => $request->input('button_url'),
            'button_text' => $request->input('button_text'),
            'grid_view' => $request->has('grid_view')
        ]);

        // Store images
        foreach ($imagePaths as $path) {
            QrGalleryImage::create([
                'gallery_id' => $gallery->id,
                'image_path' => $path
            ]);
        }

        $qrImage = QrCode::size(300)->generate($qrCode->content);


        return view('qr-result', [
            'qrCode' => $qrImage,
            'qrContent' => $qrCode->content,
            'type' => 'gallery',
            'qrRecord' => $qrCode
        ]);
    }

    public function viewGallery($id)
    {
         $gallery = QrGallery::with('images')->whereHas('qrCode', function($q) use ($id) {
            $q->where('content', route('gallery.view', ['id' => $id]));
        })->firstOrFail();

        return view('view-gallery', [
            'gallery' => $gallery
        ]);
    }
}