<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrCode as QrCodeModel;
use App\Models\QrSocialLink;
use App\Models\QrSocialPlatform;

class QrSocialController extends QrCodeController
{
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
            'platforms.*.text' => 'nullable|string|max:100',
            'platforms.*.type' => 'required|string' // Ajout de la validation pour le type
        ]);

        $qrCode = QrCodeModel::create([
            'type' => 'social',
            'content' => '',
            'user_id' => auth()->id()
        ]);
        
        $socialLink = QrSocialLink::create([
            'qr_code_id' => $qrCode->id,
            'website_url' => $request->website_url
        ]);

        foreach ($request->platforms as $platform) {
            QrSocialPlatform::create([
                'social_link_id' => $socialLink->id,
                'platform_name' => $platform['type'], // Utilisation du type sélectionné
                'url' => $platform['url'],
                'text' => $platform['text'] ?? null
            ]);
        }

        // Préparation des données pour la vue
        $platformsData = array_map(function($platform) {
            return [
                'type' => $platform['type'],
                'url' => $platform['url'],
                'text' => $platform['text'] ?? null
            ];
        }, $request->platforms);

        $qrCode->update([
            'content' => route('qr.social.landing', ['id' => $qrCode->id])
        ]);

        return $this->showQrResult(
            $qrCode->content, 
            'social', 
            $qrCode,
            [
                'website' => $request->website_url,
                'platforms' => $platformsData // Utilisation des données formatées
            ]
        );
    }

    public function showSocialLanding($id)
    {
        $socialLink = QrSocialLink::with(['platforms', 'qrCode'])
            ->whereHas('qrCode', fn($q) => $q->where('id', $id))
            ->firstOrFail();

        // Formatage des données des plateformes
        $platforms = $socialLink->platforms->map(function($platform) {
            return [
                'type' => strtolower($platform->platform_name), // Conversion en minuscules
                'url' => $platform->url,
                'text' => $platform->text
            ];
        });

        return view('social-landing', [
            'socialData' => [
                'website' => $socialLink->website_url,
                'platforms' => $platforms
            ]
        ]);
    }

    private function extractPlatformName($url)
    {
        $domain = parse_url($url, PHP_URL_HOST);
        $domain = str_replace(['www.', '.com', '.net', '.org'], '', $domain);
        return ucfirst($domain);
    }
}