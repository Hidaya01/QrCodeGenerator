@extends('layouts.app')

@section('content')
<div class="vcard-generator-container">
    <h1>Create Your vCard QR Code</h1>
    <p class="subtitle">Fill in your contact details. Not all fields are mandatory.</p>

    <form method="POST" action="{{ route('vcard.generate') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-section">
            <h2>Image:</h2>
            <div class="image-upload-container">
                <label for="imageInput" class="circle-upload-area" id="imageUploadArea">
                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden-input">
                    <div class="camera-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                    </div>
                    <div class="image-preview" id="imagePreview">
                        @if(old('image'))
                            <img src="{{ old('image') }}" alt="Preview">
                        @endif
                    </div>
                    <div class="upload-hint">Click to upload</div>
                </label>
            </div>

        <div class="form-section">
            <h2>Name:</h2>
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
                </div>
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Numbers:</h2>
            <div class="form-row">
                <div class="form-group">
                    <input type="tel" name="mobile" placeholder="Mobile number" value="{{ old('mobile') }}" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Phone" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <input type="tel" name="fax" placeholder="Fax" value="{{ old('fax') }}">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Email:</h2>
            <div class="form-group">
                <input type="email" name="email" placeholder="your@email.com" value="{{ old('email') }}">
            </div>
        </div>

        <div class="form-section">
            <h2>Company:</h2>
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="company" placeholder="Company" value="{{ old('company') }}">
                </div>
                <div class="form-group">
                    <input type="text" name="job_title" placeholder="Your Job" value="{{ old('job_title') }}">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Address:</h2>
            <div class="form-group">
                <textarea name="address" placeholder="Enter your address">{{ old('address') }}</textarea>
            </div>
        </div>

        <div class="form-section">
            <h2>Website:</h2>
            <div class="form-group">
                <input type="url" name="website" placeholder="www.your-website.com" value="{{ old('website') }}">
            </div>
        </div>

        <div class="form-section">
            <h2>Summary:</h2>
            <div class="form-group">
                <textarea name="summary" placeholder="Brief summary about yourself">{{ old('summary') }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="company">Company Name</label>
            <input type="text" class="form-control" name="company" required>
        </div>

        <div class="form-group">
            <label for="job_title">Job Title/Description</label>
            <input type="text" class="form-control" name="job_title">
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" name="address" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="services">Services (comma separated)</label>
            <input type="text" class="form-control" name="services" placeholder="Cooking, Catering, Wedding, Restauration">
        </div>

        <div class="form-group">
            <label for="summary">Description/About</label>
            <textarea class="form-control" name="summary" rows="4"></textarea>
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" id="backButton" onclick="window.history.back()">Back</button>
            <button type="submit" class="btn btn-primary">Generate QR Code</button>
        </div>
    </form>
</div>

<style>
    .vcard-generator-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .subtitle {
        color: #666;
        margin-bottom: 2rem;
    }

    .form-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .form-section h2 {
        margin-bottom: 1rem;
        color: #333;
    }

    .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-group {
        flex: 1;
    }

    input, textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    textarea {
        min-height: 100px;
        resize: vertical;
    }

    .image-upload {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    
    .upload-btn {
        padding: 0.5rem 1rem;
        background: #f0f0f0;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .upload-btn:hover {
        background: #e0e0e0;
    }

.image-upload-container {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.circle-upload-area {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background-color: #f5f5f5;
    border: 2px dashed #ccc;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.circle-upload-area:hover {
    border-color: #4a6bff;
    background-color: #f0f4ff;
}

.hidden-input {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.camera-icon {
    color: #666;
    margin-bottom: 8px;
}

.upload-hint {
    font-size: 12px;
    color: #666;
    text-align: center;
}

.image-preview {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* For when an image is uploaded */
.has-image .camera-icon,
.has-image .upload-hint {
    display: none;
}

.has-image {
    border-style: solid;
    border-color: #4a6bff;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    // Initialize if there's an old image (you'll need to implement this properly)
    @if(old('image'))
        uploadArea.classList.add('has-image');
    @endif

    imageInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function(event) {
                imagePreview.innerHTML = '';
                const img = document.createElement('img');
                img.src = event.target.result;
                img.alt = 'Preview';
                imagePreview.appendChild(img);
                uploadArea.classList.add('has-image');
            }
            
            reader.readAsDataURL(file);
        }
    });

    // Click effect
    uploadArea.addEventListener('mousedown', function() {
        this.style.transform = 'scale(0.95)';
    });
    
    uploadArea.addEventListener('mouseup', function() {
        this.style.transform = 'scale(1)';
    });
    
    uploadArea.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});
</script>
@endsection