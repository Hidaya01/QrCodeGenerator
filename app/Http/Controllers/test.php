<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache; // Add this line

use App\Models\QrCode as QrCodeModel;
use App\Models\QrGallery;
use App\Models\QrGalleryImage;
use App\Models\QrSocialLink;
use App\Models\QrSocialPlatform;
use App\Models\QrVcard;
use Illuminate\Support\Str;

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
            $request->validate(['url' => 'required|url']);
            $url = $request->input('url');
            
            // Store in database
            $qrCode = QrCodeModel::create([
                'type' => $type,
                'content' => $url,
                'user_id' => auth()->id() ?? null
            ]);
            
            $qrImage = QrCode::size(300)->generate($url);
            
            return view('qr-result', [
                'qrCode' => $qrImage,
                'qrContent' => $url,
                'type' => $type,
                'qrRecord' => $qrCode
            ]);
        }
        
        if ($type === 'pdf') {
            return redirect()->route('qr.upload.form', ['type' => $type]);
        }
        
        if ($type === 'vcard') {
            return redirect()->route('vcard.form');
        }
        if ($type === 'images') {
            return redirect()->route('qr.images.form');
        }
        if ($type === 'social') { 
            return redirect()->route('qr.social.form');
        }
    
        return back();
    }

    public function showUploadForm($type)
    {
        if (!in_array($type, ['pdf', 'vcard'])) {
            abort(404);
        }

        return view('qr-upload', ['type' => $type]);
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pdf,vcard',
            'file' => 'required|file|mimes:' . ($request->type === 'pdf' ? 'pdf' : 'vcf,txt') . '|max:2048'   
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        $file->move(public_path('uploads'), $fileName);
        
        if ($request->type === 'pdf') {
            $qrContent = route('pdf.preview', ['filename' => $fileName]);
        } else {
            $qrContent = asset('uploads/' . $fileName);
        }

        $qrCode = QrCode::size(300)->generate($qrContent);

        return view('qr-result', [
            'qrCode' => $qrCode,
            'qrContent' => $qrContent,
            'type' => $request->type
        ]);
    }
   public function preview($filename)
    {
        $filePath = public_path('uploads/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, "File not found: " . $filename);
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $fileUrl = asset('uploads/' . $filename);
        $isMobile = preg_match('/Android|iPhone|iPad/i', request()->header('User-Agent'));

        return view('preview', [
            'filePath' => $fileUrl,
            'isImage' => in_array($extension, ['jpg', 'jpeg', 'png', 'gif']),
            'isPdf' => ($extension === 'pdf'),
            'filename' => $filename,
            'isMobile' => $isMobile,
            'pdfEmbedUrl' => $isMobile 
                ? "https://docs.google.com/viewer?embedded=true&url=".urlencode($fileUrl)
                : $fileUrl."#toolbar=0&navpanes=0"
        ]);
    }

    //VCard
    public function showVCardForm()
    {
        return view('vcard-form');
    }

    public function generateVCard(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'company' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:100',
            'summary' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vcard-images', 'public');
            $imagePath = Storage::disk('public')->url($imagePath);
        }

        $vCard = $this->generateVCardContent($validated, $imagePath);

        $qrCode = QrCode::size(300)->generate($vCard);

        return view('qr-result', [
            'qrCode' => $qrCode,
            'qrContent' => $vCard,
            'type' => 'vcard'
        ]);
    }

    private function generateVCardContent($data, $imageUrl = null)
    {
        $vCard = "BEGIN:VCARD\n";
        $vCard .= "VERSION:3.0\n";
        
        // Name
        if (!empty($data['first_name']) || !empty($data['last_name'])) {
            $vCard .= "N:".$data['last_name'].";".$data['first_name'].";;;\n";
            $vCard .= "FN:".$data['first_name']." ".$data['last_name']."\n";
        }
        
        // Phone numbers
        if (!empty($data['mobile'])) {
            $vCard .= "TEL;TYPE=CELL:".$data['mobile']."\n";
        }
        if (!empty($data['phone'])) {
            $vCard .= "TEL;TYPE=WORK,VOICE:".$data['phone']."\n";
        }
        if (!empty($data['fax'])) {
            $vCard .= "TEL;TYPE=WORK,FAX:".$data['fax']."\n";
        }
        
        // Email
        if (!empty($data['email'])) {
            $vCard .= "EMAIL;TYPE=INTERNET:".$data['email']."\n";
        }
        
        // Organization
        if (!empty($data['company']) || !empty($data['job_title'])) {
            $vCard .= "ORG:".$data['company']."\n";
            $vCard .= "TITLE:".$data['job_title']."\n";
        }
        
        // Address
        if (!empty($data['address'])) {
            $vCard .= "ADR;TYPE=WORK:;;".str_replace("\n", ";", $data['address'])."\n";
        }
        
        // Website
        if (!empty($data['website'])) {
            $vCard .= "URL:".$data['website']."\n";
        }
        
        // Photo
        if (!empty($imageUrl)) {
            $vCard .= "PHOTO;VALUE=URI:".$imageUrl."\n";
        }
        
        // Summary/Note
        if (!empty($data['summary'])) {
            $vCard .= "NOTE:".$data['summary']."\n";
        }
        
        $vCard .= "END:VCARD";
        
        return $vCard;
    }
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

    public function showSocialForm()
    {
        return view('qr-social');
    }

    public function generateSocialQr(Request $request)
    {
        $request->validate([
            'website_url' => 'required|url',
            'platforms' => 'required|array|min:1',
            'platforms.*.url' => 'required|url',
            'platforms.*.text' => 'nullable|string|max:100'
        ]);

        // Create a landing page URL with all social links
        $socialData = [
            'website' => $request->website_url,
            'platforms' => $request->platforms
        ];

        // Generate a unique URL for this social QR code
        $hash = md5(json_encode($socialData));
        $url = route('qr.social.landing', ['hash' => $hash]);

        // Store the data temporarily (in a real app, you'd store this in database)
        Cache::put('social_qr_'.$hash, $socialData, now()->addHours(24));

        $qrCode = QrCode::size(300)->generate($url);

        return view('qr-result', [
            'qrCode' => $qrCode,
            'qrContent' => $url,
            'type' => 'social',
            'metaData' => $socialData
        ]);
    }

    public function showSocialLanding($hash)
    {
        if (!Cache::has('social_qr_'.$hash)) {
            abort(404);
        }

        $socialData = Cache::get('social_qr_'.$hash);
        return view('social-landing', compact('socialData'));
    }
}