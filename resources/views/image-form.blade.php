@extends('layouts.app')

@section('content')
<div class="image-gallery-container">
    <form method="POST" action="{{ route('qr.images.generate') }}" enctype="multipart/form-data">
        @csrf
        @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <div class="form-section">
            <h2>Images</h2>
            <p class="subtitle">Upload or drag and drop images to include in your gallery. You can arrange them once they appear.</p>
            
            <div class="image-upload-area" id="dropZone">
                <div class="upload-instructions">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Drag and drop images here</p>
                    <span>or</span>
                    <label class="upload-btn">
                        <input type="file" name="images[]" id="imageInput" multiple accept="image/*">
                        Upload
                    </label>
                </div>
                <div class="image-preview-container" id="imagePreviews"></div>
            </div>
            
            <div class="form-check">
    <input type="checkbox" name="grid_view" id="gridView" class="form-check-input" value="1" >
    <label for="gridView" class="form-check-label">Show images in grid view</label>
</div>
        </div>
        
        <div class="form-section">
            <h2>Basic Information</h2>
            <p class="subtitle">Add some context to your gallery. Optionally add a button to link to a website of your choice.</p>
            
            <div class="form-group">
                <label for="galleryTitle">Title</label>
                <input type="text" name="title" id="galleryTitle" class="form-control" placeholder="Title for your gallery">
            </div>
            
            <div class="form-group">
                <label for="buttonUrl">Button URL (optional)</label>
                <input type="url" name="button_url" id="buttonUrl" class="form-control" placeholder="https://example.com">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Back</button>
            <button type="submit" class="btn btn-primary">Generate QR Code</button>
        </div>
    </form>
</div>

<style>
.image-gallery-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.subtitle {
    color: #666;
    margin-bottom: 1rem;
}

.image-upload-area {
    border: 2px dashed #ccc;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    margin-bottom: 1rem;
    position: relative;
}

.image-upload-area.drag-over {
    border-color: #4a6bff;
    background-color: rgba(74, 107, 255, 0.05);
}

.upload-instructions {
    color: #666;
}

.upload-instructions i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.upload-btn {
    padding: 0.5rem 1rem;
    background: #4a6bff;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    display: inline-block;
    margin-top: 0.5rem;
}

.upload-btn:hover {
    background: #3a56d4;
}

.upload-btn input[type="file"] {
    display: none;
}

.image-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.image-preview {
    width: 100px;
    height: 100px;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-preview .remove-btn {
    position: absolute;
    top: 0;
    right: 0;
    background: rgba(0,0,0,0.5);
    color: white;
    border: none;
    width: 20px;
    height: 20px;
    border-radius: 0 0 0 4px;
    cursor: pointer;
}


</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('imageInput');
    const imagePreviews = document.getElementById('imagePreviews');
    const form = document.querySelector('form');
    
    // Handle drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropZone.classList.add('drag-over');
    }
    
    function unhighlight() {
        dropZone.classList.remove('drag-over');
    }
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }
    
    // Handle file input change
    imageInput.addEventListener('change', function() {
        handleFiles(this.files);
    });
    
    function handleFiles(files) {
        [...files].forEach(file => {
            if (!file.type.match('image.*')) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'image-preview';
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}">
                    <button type="button" class="remove-btn" onclick="this.parentElement.remove()">×</button>
                `;
                imagePreviews.appendChild(preview);
            }
            reader.readAsDataURL(file);
        });
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        // You can add validation here if needed
    });
});
</script>
@endsection