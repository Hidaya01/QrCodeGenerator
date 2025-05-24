@extends('layouts.app')

@section('content')
<div class="qr-pdf-upload-container">
    <div class="qr-pdf-upload-card">
        <h1>Name your QR Code</h1>
        <h2>Upload PDF</h2>
        <p class="subtitle">Select any PDF from your computer</p>

        <div class="upload-section">
            <div class="upload-info">
                <h3>Upload PDF files (up to 100KB)</h3>
                <div class="qr-feature">
                    <i class="fas fa-qrcode"></i>
                    <span>QR Code included directly on PDF</span>
                </div>
            </div>

            <form method="POST" action="{{ route('qr.process.upload') }}" enctype="multipart/form-data" id="pdfUploadForm">
                @csrf
                <input type="hidden" name="type" value="pdf">
                
                <div class="file-upload-wrapper">
                    <input type="file" id="pdfFile" name="file" accept=".pdf" required>
                    <label for="pdfFile" class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Choose PDF File</span>
                    </label>
                    <div class="file-info" id="fileInfo">No file selected</div>
                </div>

                <div class="design-section">
                    <h3>Design & Customization</h3>
                    <p>Presented by new page by selecting source colors</p>
                    
                    <div class="color-options">
                        <div class="color-option" data-color="#000000" style="background-color: #000000"></div>
                        <div class="color-option" data-color="#FF0000" style="background-color: #FF0000"></div>
                        <div class="color-option" data-color="#00FF00" style="background-color: #00FF00"></div>
                        <div class="color-option" data-color="#0000FF" style="background-color: #0000FF"></div>
                        <input type="hidden" name="qr_color" id="qrColor" value="#000000">
                    </div>
                </div>

                <div class="basic-info-section">
                    <h3>Basic Information</h3>
                    <p>Provide your business info and add some contact on your PDF</p>
                    
                    <div class="form-group">
                        <label for="company">Company:</label>
                        <input type="text" id="company" name="company" placeholder="Internet directory or PDF owner">
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" placeholder="Title or PDF name" maxlength="20">
                        <div class="character-counter"><span id="titleCounter">0</span>/20</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" placeholder="Provide more info about your PDF"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="website">Website:</label>
                        <input type="url" id="website" name="website" placeholder="www.yourwebsite.com">
                    </div>
                </div>

                <div class="logo-section">
                    <h3>Welcome Source</h3>
                    <p>Display your logo with your page is binding</p>
                    
                    <div class="logo-upload-wrapper">
                        <input type="file" id="logoFile" name="logo" accept="image/*">
                        <label for="logoFile" class="logo-upload-label">
                            <i class="fas fa-image"></i>
                            <span>Upload Logo</span>
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="backButton">Back</button>
                    <button type="submit" class="btn btn-primary">Generate QR Code</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.qr-pdf-upload-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.qr-pdf-upload-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 2rem;
}

h1 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

h2 {
    font-size: 1.5rem;
    margin: 1.5rem 0 0.5rem;
}

h3 {
    font-size: 1.2rem;
    margin: 1.5rem 0 0.5rem;
}

.subtitle {
    color: #666;
    margin-bottom: 1.5rem;
}

.upload-section {
    margin-top: 2rem;
}

.file-upload-wrapper {
    margin: 1.5rem 0;
}

.file-upload-label, .logo-upload-label {
    display: inline-block;
    padding: 0.8rem 1.5rem;
    background: #f0f0f0;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.2s;
}

.file-upload-label:hover, .logo-upload-label:hover {
    background: #e0e0e0;
}

.file-upload-label i, .logo-upload-label i {
    margin-right: 0.5rem;
}

.file-info {
    margin-top: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.color-options {
    display: flex;
    gap: 0.5rem;
    margin: 1rem 0;
}

.color-option {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent;
}

.color-option.selected {
    border-color: #333;
}

.form-group {
    margin-bottom: 1.2rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input, .form-group textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.character-counter {
    text-align: right;
    font-size: 0.8rem;
    color: #666;
    margin-top: 0.3rem;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
}

.btn {
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: #4a6bff;
    color: white;
}

.btn-secondary {
    background: #f0f0f0;
    color: #333;
}

.qr-feature {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    color: #4a6bff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File selection handler
    const pdfFileInput = document.getElementById('pdfFile');
    const fileInfo = document.getElementById('fileInfo');
    
    pdfFileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            fileInfo.textContent = this.files[0].name;
        } else {
            fileInfo.textContent = 'No file selected';
        }
    });

    // Color selection handler
    const colorOptions = document.querySelectorAll('.color-option');
    const qrColorInput = document.getElementById('qrColor');
    
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            colorOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            qrColorInput.value = this.dataset.color;
        });
    });

    // Title character counter
    const titleInput = document.getElementById('title');
    const titleCounter = document.getElementById('titleCounter');
    
    titleInput.addEventListener('input', function() {
        titleCounter.textContent = this.value.length;
    });

    // Back button handler
    const backButton = document.getElementById('backButton');
    backButton.addEventListener('click', function() {
        window.history.back();
    });
});
</script>
@endsection