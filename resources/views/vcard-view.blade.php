<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $vcard->company }} - Digital Business Card</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        
        .vcard-container {
            max-width: 600px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .vcard-container:hover {
            transform: translateY(-5px);
        }
        .header-bg {
            background-color:rgba(51, 51, 51, 0.79);
        }
        .services span {
            display: inline-block;
            margin: 0 8px 8px 0;
            padding: 6px 14px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 20px;
            font-size: 14px;
            color: #667eea;
            font-weight: 500;
        }
        .map-link {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .map-link:hover {
            text-decoration: underline;
        }
        
        .contact-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
            color: #667eea;
            margin-right: 16px;
        }
        .social-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        .social-icon:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
        }
        .social-icon-insta {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        .social-icon-insta:hover {
            background: linear-gradient(
                to right,
                #833ab4,#fd1d1d,#fcb045
            );            color: white;
            transform: translateY(-3px);
        }
        .divider {
            height: 1px;
            background: rgba(0,0,0,0.1);
            margin: 24px 0;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="vcard-container bg-white w-full">
        <!-- Header with Logo/Image -->
        <div class="header-bg text-white p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/always-grey.png');"></div>
            @if($vcard->image_path)
            <div class="relative z-10">
                <img src="{{ Storage::url($vcard->image_path) }}" alt="{{ $vcard->company }}" class="h-24 w-24 mx-auto mb-4 rounded-full object-cover border-4 border-white shadow-md">
            </div>
            @endif
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-1">{{ $vcard->company }}</h1>
                <p class="text-gray-100 opacity-90">{{ $vcard->job_title ?? 'Professional Services' }}</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-8">
            <!-- Description -->
            <div class="mb-8">
                <p class="text-gray-700 leading-relaxed">{{ $vcard->summary }}</p>
            </div>

            <div class="divider"></div>

            <!-- Contact Info -->
            <div class="space-y-6 mb-8">
                @if($vcard->mobile)
                <div class="flex items-start">
                    <div class="contact-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Mobile</p>
                        <a href="tel:{{ $vcard->mobile }}" class="text-gray-800 hover:text-gray-600 font-medium">{{ $vcard->mobile }}</a>
                    </div>
                </div>
                @endif

                @if($vcard->email)
                <div class="flex items-start">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">E-mail</p>
                        <a href="mailto:{{ $vcard->email }}" class="text-gray-800 hover:text-gray-600 font-medium">{{ $vcard->email }}</a>
                    </div>
                </div>
                @endif

                @if($vcard->address)
                <div class="flex items-start">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Adresse</p>
                        <p class="text-gray-800 font-medium mb-1">{{ $vcard->address }}</p>
                        <a href="https://maps.google.com?q={{ urlencode($vcard->address) }}" target="_blank" class="map-link text-sm font-medium">Show on map</a>
                    </div>
                </div>
                @endif

                @if($vcard->website)
                <div class="flex items-start">
                    <div class="contact-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Website</p>
                        <a href="{{ $vcard->website }}" target="_blank" class="text-gray-800 hover:text-gray-600 font-medium">{{ $vcard->website }}</a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Services -->
            @if($vcard->services)
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Services</h3>
                <div class="services">
                    @foreach(explode(',', $vcard->services) as $service)
                    <span>{{ trim($service) }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="divider"></div>

            <!-- Social Media -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Connect With Me</h3>
                <div class="flex space-x-4">
                    <a href="#" class="social-icon text-blue-800"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon text-blue-400"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon-insta text-pink-600"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon text-blue-700"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Download VCard Button -->
            <div class="text-center mt-8">
                <a href="{{ route('qr.vcard.download', ['id' => $vcard->qrCode->id]) }}" class="btn btn-download">
                    <i class="fas fa-download mr-2"></i> DOWNLOAD VCARD
                </a>
            </div>
        </div>
    </div>
</body>
</html>