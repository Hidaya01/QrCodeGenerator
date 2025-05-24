<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\QrFileController; 
use App\Http\Controllers\QrGalleryController;
use App\Http\Controllers\QrSocialController;
use App\Http\Controllers\QrVCardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/', [QrCodeController::class, 'showOptions'])->name('qr.options');
Route::get('/qr-generator', [QrCodeController::class, 'showOptions'])->name('qr.options');
Route::post('/qr-generator/select-type', [QrCodeController::class, 'handleSelection'])->name('qr.select-type');
Route::get('/qr-generator/generate', [QrCodeController::class, 'showGenerateForm'])->name('qr.generate.form');
Route::post('/qr-generator/generate', [QrCodeController::class, 'generateQr'])->name('qr.generate');

//pdf
// File QR Routes
Route::get('/upload/{type}', [QrFileController::class, 'showUploadForm'])->name('qr.upload.form');
Route::post('/upload/process', [QrFileController::class, 'processUpload'])->name('qr.process-upload');Route::get('/file/preview/{id}', [QrFileController::class, 'preview'])->name('file.preview');
Route::get('/file/download/{id}', [QrFileController::class, 'download'])->name('file.download');

// Vcard
Route::prefix('vcard')->group(function () {
    Route::get('/', [QrVCardController::class, 'showVCardForm'])->name('qr.vcard.form');
    Route::post('/generate', [QrVCardController::class, 'generateVCard'])->name('qr.vcard.generate');
    Route::get('/view/{id}', [QrVCardController::class, 'view'])->name('qr.vcard.view');
    
});
Route::get('/vcard/download/{id}', [QrVCardController::class, 'downloadVCard'])->name('qr.vcard.download');

//image gallery
Route::get('/qr-generator/images', [QrGalleryController::class, 'showImageForm'])->name('qr.images.form');
Route::post('/qr-generator/images', [QrGalleryController::class, 'generateImageQr'])->name('qr.images.generate');
Route::get('/gallery/{id}', [QrGalleryController::class, 'viewGallery'])->name('gallery.view');

// Social Media QR Routes
Route::get('/qr-generator/social', [QrSocialController::class, 'showSocialForm'])->name('qr.social.form');
Route::post('/qr-generator/social', [QrSocialController::class, 'generateSocialQr'])->name('qr.generate.social');
Route::get('/social/{id}', [QrSocialController::class, 'showSocialLanding'])->name('qr.social.landing');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
