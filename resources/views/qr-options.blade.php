@extends('layouts.app')

@section('content')
<div class="qr-generator-container">
    <form method="POST" action="{{ route('qr.generate') }}" id="qrGeneratorForm">
        @csrf
        <input type="hidden" name="type" id="selectedType" value="website">
        
        <div class="qr-generator-card">
            <div class="qr-generator-header">
                <h1>Create your QR Code</h1>
            </div>
            
            <div class="qr-type-option active" data-type="website">
                <div class="qr-type-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="qr-type-content">
                    <h3>Website</h3>
                    <p>Create this QR Code type to link to your website, any Google URL or document, your social media profile or any other page on the web.</p>
                    <div class="url-input-container">
                        <div class="input-group">
                            <input type="url" 
                                   name="url" 
                                   id="urlInput"
                                   class="form-control @error('url') is-invalid @enderror" 
                                   placeholder="https://www.example.com" 
                                   value="{{ old('url') }}"
                                   required>
                            <button type="button" id="generateBtn" class="btn btn-primary">
                                Generate
                            </button>
                        </div>
                        @error('url')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="divider">
                <span>or select from more Dynamic Code types</span>
            </div>
            
            <div class="qr-type-options-grid">
                <div class="qr-type-option" data-type="vcard">
                    <div class="qr-type-icon">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <div class="qr-type-content">
                        <h4>VCard Plus</h4>
                        <p>Share personalized contact details</p>
                    </div>
                </div>
                
                <div class="qr-type-option" data-type="pdf">
                    <div class="qr-type-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="qr-type-content">
                        <h4>PDF</h4>
                        <p>Link to a mobile-optimized PDF</p>
                    </div>
                </div>
                <div class="qr-type-option" data-type="social">
                    <div class="qr-type-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div class="qr-type-content">
                        <h4>Social Media</h4>
                        <p>Link to your social media channels</p>
                    </div>
                </div>
                <div class="qr-type-option" data-type="images">
                    <div class="qr-type-icon">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="qr-type-content">
                        <h4>Images</h4>
                        <p>Show a series of images</p>
                    </div>
                </div>
            </div>
            
            <div class="qr-generator-actions" id="nextButtonContainer">
                <button type="submit" class="btn btn-primary btn-lg" id="nextButton">
                    NEXT <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.url-input-container {
    margin-top: 1rem;
}

.input-group {
    display: flex;
}

.input-group .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    white-space: nowrap;
}

.invalid-feedback.d-block {
    display: block;
    margin-top: 0.5rem;
}

/* Initially hide the NEXT button for website */
#nextButtonContainer[data-show="false"] {
    display: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const options = document.querySelectorAll('.qr-type-option');
    const selectedTypeInput = document.getElementById('selectedType');
    const form = document.getElementById('qrGeneratorForm');
    const nextButtonContainer = document.getElementById('nextButtonContainer');
    const generateBtn = document.getElementById('generateBtn');
    const urlInput = document.getElementById('urlInput');
    
    // Initialize - hide NEXT button for website
    nextButtonContainer.setAttribute('data-show', 'false');
    
    // Handle type selection
    options.forEach(option => {
        option.addEventListener('click', function() {
            options.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            selectedTypeInput.value = this.dataset.type;
            
            // Show/hide NEXT button and toggle URL requirement
            if (this.dataset.type === 'website') {
                nextButtonContainer.setAttribute('data-show', 'false');
                urlInput.required = true;
            } else {
                nextButtonContainer.setAttribute('data-show', 'true');
                urlInput.required = false;
                // Clear any validation errors
                urlInput.setCustomValidity('');
            }
        });
    });
    
    // Handle Generate button click (only for website)
    generateBtn.addEventListener('click', function() {
        if (selectedTypeInput.value === 'website') {
            if (urlInput.checkValidity()) {
                form.submit();
            } else {
                urlInput.reportValidity();
            }
        }
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        // Only validate URL if website is selected
        if (selectedTypeInput.value === 'website') {
            if (!urlInput.checkValidity()) {
                e.preventDefault();
                urlInput.reportValidity();
            }
        } else {
            // For other types, remove the URL value if it exists
            urlInput.value = '';
        }
    });
});
</script>
@endsection