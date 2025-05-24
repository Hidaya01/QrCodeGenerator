<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\QrCode as QrCodeModel;
use App\Models\QrVcard;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrVCardController extends QrCodeController
{
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
            'email' => 'nullable|email|max:100',
            'company' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:100',
            'summary' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:2048',
            'services' => 'nullable|string|max:255'
        ]);

        // Store image if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vcards/images', 'public');
        }

        // Create QR code record
        $qrCode = QrCodeModel::create([
            'type' => 'vcard',
            'content' => '',
            'user_id' => auth()->id()
        ]);

        // Create vCard record
        $vcard = QrVcard::create(array_merge(
            ['qr_code_id' => $qrCode->id],
            $validated,
            ['image_path' => $imagePath]
        ));

        // Update QR code with final URL
        $qrCode->update([
            'content' => route('qr.vcard.view', ['id' => $qrCode->id])
        ]);

        return $this->showQrResult(
            $qrCode->content,
            'vcard',
            $qrCode
        );
    }

    public function view($id)
    {
        $vcard = QrVcard::with('qrCode')
            ->whereHas('qrCode', fn($q) => $q->where('id', $id))
            ->firstOrFail();

        return view('vcard-view', compact('vcard'));
    }
    public function downloadVCard($id)
{
    $vcard = QrVcard::with('qrCode')
        ->whereHas('qrCode', fn($q) => $q->where('id', $id))
        ->firstOrFail();

    $vcardContent = $this->generateVCardContent($vcard);

    return response($vcardContent)
        ->header('Content-Type', 'text/vcard')
        ->header('Content-Disposition', 'attachment; filename="' . str_slug($vcard->company) . '.vcf"');
}

private function generateVCardContent($vcard)
{
    $content = "BEGIN:VCARD\n";
    $content .= "VERSION:3.0\n";
    $content .= "FN:" . $vcard->first_name . " " . $vcard->last_name . "\n";
    $content .= "ORG:" . $vcard->company . "\n";
    $content .= "TITLE:" . $vcard->job_title . "\n";
    $content .= "TEL;TYPE=CELL:" . $vcard->mobile . "\n";
    $content .= "EMAIL:" . $vcard->email . "\n";
    $content .= "URL:" . $vcard->website . "\n";
    $content .= "ADR;TYPE=WORK:;;" . str_replace("\n", ";", $vcard->address) . "\n";
    $content .= "NOTE:" . $vcard->summary . "\n";
    $content .= "END:VCARD";

    return $content;
}
}