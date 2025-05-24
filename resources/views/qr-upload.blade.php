@extends('layouts.app')

@section('content')
<div class="qr-generator-container">
    <div class="qr-generator-card">
        <div class="qr-generator-header">
            <h1>Upload {{ strtoupper($type) }} File</h1>
            <p class="subtitle">Select the file you want to generate a QR code for</p>
        </div>
        
        <div class="qr-upload-content">
            <form method="POST" action="{{ route('qr.process-upload') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                
                <div class="file-upload-area">
                    <div class="file-upload-box">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h4>Drag and drop your {{ $type }} file here</h4>
                        <p>or</p>
                        <label for="file" class="btn btn-outline-primary">
                            Browse Files
                            <input type="file" id="file" name="file" class="d-none" 
                                   accept="{{ $type === 'pdf' ? '.pdf' : '.vcf,.txt' }}" required>
                        </label>
                        <div class="file-info mt-3" id="fileInfo"></div>
                    </div>
                    
                    @error('file')
                        <div class="alert alert-danger mt-3">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="qr-generator-actions">
                    <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="backButton" onclick="window.history.back() ">Back</button>
                    <button type="submit" class="btn btn-primary">Generate QR Code</button>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const fileInfo = document.getElementById('fileInfo');
    const submitBtn = document.getElementById('submitBtn');
    
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileInfo.innerHTML = `
                <div class="file-details">
                    <i class="fas fa-file-${file.type.includes('pdf') ? 'pdf' : 'alt'}"></i>
                    <div>
                        <strong>${file.name}</strong>
                        <div>${(file.size / 1024).toFixed(2)} KB</div>
                    </div>
                </div>
            `;
            submitBtn.disabled = false;
        }
    });
    
    // Drag and drop functionality
    const uploadBox = document.querySelector('.file-upload-box');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadBox.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadBox.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadBox.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        uploadBox.classList.add('highlight');
    }
    
    function unhighlight() {
        uploadBox.classList.remove('highlight');
    }
    
    uploadBox.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        
        // Trigger change event
        const event = new Event('change');
        fileInput.dispatchEvent(event);
    }
});
</script>

<style>
.file-upload-area {
    padding: 2rem;
    text-align: center;
}

.file-upload-box {
    border: 2px dashed #ddd;
    border-radius: 12px;
    padding: 3rem 2rem;
    transition: all 0.3s ease;
    background-color: #f9f9f9;
}

.file-upload-box.highlight {
    border-color: var(--primary-color);
    background-color: rgba(67, 97, 238, 0.05);
}

.file-upload-box i {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.file-upload-box h4 {
    margin-bottom: 0.5rem;
    color: var(--text-color);
}

.file-upload-box p {
    color: var(--text-light);
    margin: 0.5rem 0;
}

.file-details {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 1rem;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.file-details i {
    font-size: 1.5rem;
    color: #e63946;
}

.subtitle {
    color: var(--text-light);
    margin-top: 0.5rem;
    font-size: 1rem;
}
/* Style for the file input container */
.file-input-container {
    position: relative;
    display: inline-block;
    overflow: hidden;
}

/* Style for the actual file input */
input[type="file"] {
    font-size: 16px;
    padding: 10px 15px;
    background: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 4px;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s;
}

/* Hover state */
input[type="file"]:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

/* Focus state */
input[type="file"]:focus {
    outline: none;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Remove the "No file chosen" text in some browsers */
input[type="file"]::file-selector-button {
    padding: 8px 12px;
    margin-right: 10px;
    background:rgb(156, 156, 156);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
}

input[type="file"]::file-selector-button:hover {
    background:rgb(156, 165, 174);
}

</style>
@endsection