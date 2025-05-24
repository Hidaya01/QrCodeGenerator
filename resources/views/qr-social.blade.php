@extends('layouts.app')

@section('content')
<div class="qr-social-container">
    <div class="qr-social-card">
        <h1>Social Media Channels</h1>
        <p class="subtitle">Add your username or links to social media pages below.</p>
        
        <form method="POST" action="{{ route('qr.generate.social') }}" id="socialForm">
            @csrf
            <input type="hidden" name="type" value="social">
            
            <div class="form-section">
                <h2>Website:</h2>
                <div class="form-group">
                    <label>URL <span class="required">*</span></label>
                    <input type="url" name="website_url" placeholder="www.your-website.com" required>
                </div>
            </div>
            
            <div class="social-platforms">
                <div class="platform" id="platformTemplate">
                    <div class="platform-header">
                        <select class="platform-select" name="platforms[0][type]">
                            <option value="facebook">Facebook</option>
                            <option value="youtube">YouTube</option>
                            <option value="instagram">Instagram</option>
                            <option value="twitter">Twitter</option>
                            <option value="linkedin">LinkedIn</option>
                        </select>
                        <button type="button" class="btn-remove-platform">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="form-group">
                        <label>URL <span class="required">*</span></label>
                        <input type="url" name="platforms[0][url]" placeholder="www.facebook.com/page" required>
                    </div>
                    <div class="form-group">
                        <label>Text</label>
                        <input type="text" name="platforms[0][text]" placeholder="Become a fan">
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="addPlatform">
                    <i class="fas fa-plus"></i> Add Social Media
                </button>
                <button type="submit" class="btn btn-primary">
                    Generate QR Code
                </button>
            </div>
        </form>
        
        <div class="preview-section">
            <h3>Connect with us on social media</h3>
            <p>Follow us and get updates delivered to your favorite social media channel.</p>
            
            <div class="preview-content" id="previewContent">

            </div>
        </div>
    </div>
</div>

<style>
.qr-social-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.qr-social-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 2rem;
}

h1 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

h2 {
    font-size: 1.3rem;
    margin: 1.5rem 0 0.5rem;
}

h3 {
    font-size: 1.2rem;
    margin: 2rem 0 0.5rem;
}

.subtitle {
    color: #666;
    margin-bottom: 1.5rem;
}

.form-section {
    margin-bottom: 2rem;
}

.social-platforms {
    margin-bottom: 2rem;
}

.platform {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    position: relative;
}

.platform-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.platform-select {
    flex-grow: 1;
    padding: 0.5rem;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.btn-remove-platform {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    margin-left: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.3rem;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.required {
    color: #dc3545;
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
    font-size: 1rem;
}


.btn-secondary {
    background: #f0f0f0;
    color: #333;
}

.preview-section {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

.preview-content {
    margin-top: 1rem;
}

.social-link {
    margin-bottom: 1rem;
}

.social-link h4 {
    margin-bottom: 0.3rem;
}

.social-link p {
    margin: 0;
    color: #666;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const socialPlatforms = document.querySelector('.social-platforms');
    const addPlatformBtn = document.getElementById('addPlatform');
    const previewContent = document.getElementById('previewContent');
    const form = document.getElementById('socialForm');
    let platformCount = 1;

    // Add new platform
    addPlatformBtn.addEventListener('click', function() {
        const newPlatform = document.getElementById('platformTemplate').cloneNode(true);
        newPlatform.id = '';
        
        const inputs = newPlatform.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.name.replace('[0]', `[${platformCount}]`);
            input.name = name;
        });
        
        socialPlatforms.appendChild(newPlatform);
        platformCount++;
        
        newPlatform.querySelector('.btn-remove-platform').addEventListener('click', function() {
            socialPlatforms.removeChild(newPlatform);
            updatePreview();
        });
        
        newPlatform.querySelector('.platform-select').addEventListener('change', updatePreview);
        newPlatform.querySelector('input[name*="[url]"]').addEventListener('input', updatePreview);
        newPlatform.querySelector('input[name*="[text]"]').addEventListener('input', updatePreview);
    });

    // Remove platform
    document.querySelector('.btn-remove-platform').addEventListener('click', function() {
        if (document.querySelectorAll('.platform').length > 1) {
            socialPlatforms.removeChild(this.closest('.platform'));
            updatePreview();
        }
    });

    function updatePreview() {
        let previewHTML = '';
        
        // Website
        const websiteUrl = form.querySelector('input[name="website_url"]').value;
        if (websiteUrl) {
            previewHTML += `
                <div class="social-link">
                    <h4>Visit us online</h4>
                    <p>${websiteUrl}</p>
                </div>
            `;
        }
        
        // Social platforms
        document.querySelectorAll('.platform').forEach((platform, index) => {
            const type = platform.querySelector('.platform-select').value;
            const url = platform.querySelector('input[name*="[url]"]').value;
            const text = platform.querySelector('input[name*="[text]"]').value || `Visit our ${type}`;
            
            if (url) {
                const platformName = type.charAt(0).toUpperCase() + type.slice(1);
                previewHTML += `
                    <div class="social-link">
                        <h4>${platformName}</h4>
                        <p>${text}</p>
                    </div>
                `;
            }
        });
        
        previewContent.innerHTML = previewHTML || '<p>Add social media links to see preview</p>';
    }

    document.querySelector('.platform-select').addEventListener('change', updatePreview);
    document.querySelector('input[name="website_url"]').addEventListener('input', updatePreview);
    document.querySelector('input[name*="[url]"]').addEventListener('input', updatePreview);
    document.querySelector('input[name*="[text]"]').addEventListener('input', updatePreview);
    
    updatePreview();
});
</script>
@endsection