<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $gallery['title'] ?? 'Image Gallery' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gallery-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .gallery-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .gallery-title {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .gallery-controls {
            display: flex;
            justify-content: center; 
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        

        .grid-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .list-view {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            align-items:center;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #fff;
            border: 1px solid #eaeaea;
            width: 100%;
            justify-content:center;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.15);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .grid-view .gallery-item {
            aspect-ratio: 1;
        }

        .list-view .gallery-item {
            display: flex;
            flex-direction: row;
            aspect-ratio: unset;
            max-height: 300px;
        }

        .list-view .gallery-item img {
            width: 50%;
            height: auto;
        }

        .image-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            padding: 1rem;
            display: flex;
            justify-content: flex-end;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .image-actions {
            opacity: 1;
        }

        
        .gallery-action {
            text-align: center;
            margin-top: 2.5rem;
        }

        .btn-primary {
            background-color: #4a6bff;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
        }

        .btn-primary:hover {
            background-color: #3a56d4;
        }

        @media (max-width: 768px) {
            .grid-view {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 1rem;
            }

            .list-view .gallery-item {
                flex-direction: column;
                max-height: none;
            }

            .list-view .gallery-item img {
                width: 100%;
            }

            .gallery-controls {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .grid-view {
                grid-template-columns: 1fr;
            }

            .gallery-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="gallery-container">
        <div class="gallery-header">
            @if($gallery['title'])
                <h1 class="gallery-title">{{ $gallery['title'] }}</h1>
            @endif
        </div>

        <div class="gallery-controls">
            <button id="downloadAll" class="btn btn-download" style="text-align:center">
                <i class="fas fa-download"></i> Download All
            </button>
        </div>
        
        <div class="gallery-images @if($gallery['grid_view']) grid-view @else list-view @endif">
            @foreach($gallery['images'] as $index => $image)
                <div class="gallery-item">
                    <img src="{{ asset($image->image_path) }}" alt="Gallery image">
                    <div class="image-actions">
                        <button class="btn btn-download download-single" data-image="{{ asset($image) }}">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($gallery['button_url'] && $gallery['button_text'])
            <div class="gallery-action">
                <a href="{{ $gallery['button_url'] }}" class="btn btn-primary">
                    {{ $gallery['button_text'] }}
                </a>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Download single image
            document.querySelectorAll('.download-single').forEach(button => {
                button.addEventListener('click', function() {
                    const imageUrl = this.getAttribute('data-image');
                    const fileName = imageUrl.split('/').pop();
                    
                    // Create a temporary anchor element
                    const a = document.createElement('a');
                    a.href = imageUrl;
                    a.download = fileName || 'download';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                });
            });

            // Download all images as ZIP (would need server-side implementation)
           // Function to download all images as a ZIP file
async function downloadAllAsZip() {
    try {
        // Show loading state
        const downloadBtn = document.getElementById('downloadAll');
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing ZIP...';
        downloadBtn.disabled = true;

        // Load the JSZip library dynamically
        const JSZip = await loadJSZip();
        const zip = new JSZip();
        const imgFolder = zip.folder("images");
        const title = document.querySelector('.gallery-title')?.textContent || 'gallery';
        
        // Fetch all images and add to ZIP
        const imagePromises = Array.from(document.querySelectorAll('.gallery-item img')).map(async (img, index) => {
            const response = await fetch(img.src);
            const blob = await response.blob();
            const fileName = img.src.split('/').pop() || `image_${index + 1}.jpg`;
            imgFolder.file(fileName, blob);
        });

        await Promise.all(imagePromises);
        
        // Generate the ZIP file
        const content = await zip.generateAsync({ type: 'blob' });
        const zipUrl = URL.createObjectURL(content);
        
        // Create download link
        const a = document.createElement('a');
        a.href = zipUrl;
        a.download = `${title}.zip`;
        document.body.appendChild(a);
        a.click();
        
        // Clean up
        setTimeout(() => {
            document.body.removeChild(a);
            URL.revokeObjectURL(zipUrl);
        }, 100);

    } catch (error) {
        console.error('Error creating ZIP:', error);
        alert('Error creating ZIP file. Please try again.');
    } finally {
        // Restore button state
        const downloadBtn = document.getElementById('downloadAll');
        downloadBtn.innerHTML = '<i class="fas fa-download"></i> Download All';
        downloadBtn.disabled = false;
    }
}

// Helper function to load JSZip library
function loadJSZip() {
    return new Promise((resolve, reject) => {
        if (window.JSZip) {
            resolve(window.JSZip);
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js';
        script.onload = () => resolve(window.JSZip);
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

// Add event listener
document.getElementById('downloadAll').addEventListener('click', downloadAllAsZip);
        });
    </script>
</body>
</html>