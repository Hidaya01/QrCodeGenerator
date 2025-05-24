@extends('layouts.app')

@section('content')
<div class="qr-result-container">
    <div class="qr-result-card">
        <h1>Your QR Code is Ready!</h1>
        
       
        @if($type === 'vcard')
            <div class="qr-code-display">
                {!! $qrCode !!}
            </div>
        @else
         <div class="qr-code-display">
            {!! $qrCode !!}
            <p class="qr-content">{{ $qrContent }}</p>
        </div>
        @endif
        <div class="download-options">
            <button id="downloadPNG" class="btn btn-download">
                <i class="fas fa-download"></i> Download PNG
            </button>
            <button id="downloadSVG" class="btn btn-download">
                <i class="fas fa-download"></i> Download SVG
            </button>
        </div>
        @if($type === 'vcard')
            <div class="vcard-preview">
                <h3>vCard Content Preview</h3>
                <p class="vcard-instructions">
                    When scanned, this QR code will add the contact information to the user's address book.
                </p>
            </div>
        @endif
        <div class="share-options">
            <h3>Share your QR Code:</h3>
            <div class="share-buttons">
                <button class="btn btn-share whatsapp" data-share="whatsapp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </button>
                <button class="btn btn-share facebook" data-share="facebook">
                    <i class="fab fa-facebook"></i> Facebook
                </button>
                
                <button class="btn btn-share copy" data-share="copy">
                    <i class="fas fa-copy"></i> Copy Link
                </button>
                <div id="copyMessage" style="display: none; color: green; margin-top: 10px;"></div>

            </div>
        </div>
    </div>
</div>

<style>
.qr-result-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.qr-result-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 2rem;
    text-align: center;
}

.qr-code-display {
    margin: 2rem 0;
}

.qr-code-display svg {
    max-width: 150px;
    height: 150px;
    margin: 0 auto;
}

.qr-content {
    margin-top: 1rem;
    word-break: break-all;
    color: #666;
}

.download-options, .share-options {
    margin: 2rem 0;
}

.btn {
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    cursor: pointer;
    border: none;
    margin: 0.5rem;
    font-size: 1rem;
}

.btn-download {
    background:rgb(199, 201, 210);
    color: black;
}.btn-download:hover {
    background:rgb(209, 32, 12);
    color: white;
}

.btn-share {
    color: white;
}

.whatsapp { background: #25D366; }
.facebook { background: #0866FF; }
.copy { background: #6c757d; }

.share-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
}

@media (max-width: 600px) {
    .share-buttons {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        margin: 0.5rem 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Download functionality
    document.getElementById('downloadPNG').addEventListener('click', function() {
        const svg = document.querySelector('.qr-code-display svg');
        const svgData = new XMLSerializer().serializeToString(svg);
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            const pngFile = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.download = 'QRCode.png';
            downloadLink.href = pngFile;
            downloadLink.click();
        };
        
        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
    });

    document.getElementById('downloadSVG').addEventListener('click', function() {
        const svg = document.querySelector('.qr-code-display svg');
        const svgData = new XMLSerializer().serializeToString(svg);
        const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
        const svgUrl = URL.createObjectURL(svgBlob);
        const downloadLink = document.createElement('a');
        
        downloadLink.download = 'QRCode.svg';
        downloadLink.href = svgUrl;
        downloadLink.click();
    });

    // Share functionality
    const shareButtons = document.querySelectorAll('.btn-share');
    const qrContent = document.querySelector('.qr-content').textContent;
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function() {
            const platform = this.dataset.share;
            let shareUrl = '';
            
            switch(platform) {
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=Check%20out%20this%20QR%20Code:%20${encodeURIComponent(qrContent)}`;
                    window.open(shareUrl, '_blank');
                    break;
                    
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(qrContent)}`;
                    window.open(shareUrl, '_blank', 'width=600,height=400');
                    break;
                    
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?text=Check%20out%20this%20QR%20Code:%20${encodeURIComponent(qrContent)}`;
                    window.open(shareUrl, '_blank', 'width=600,height=400');
                    break;
                    
                case 'copy':
                navigator.clipboard.writeText(qrContent)
                    .then(() => {
                        const messageBox = document.getElementById('copyMessage');
                        messageBox.textContent = 'Link copied to clipboard!';
                        messageBox.style.display = 'block';
                        
                        // Optional: hide after 3 seconds
                        setTimeout(() => {
                            messageBox.style.display = 'none';
                        }, 3000);
                    })
                    .catch(err => console.error('Could not copy text: ', err));
                break;

            }
        });
    });
});
</script>
@endsection