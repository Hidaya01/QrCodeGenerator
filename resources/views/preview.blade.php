@extends('layouts.app')

@section('content')
<div class="preview-container">
    <div class="preview-header">
        <h2 style="text-align:center">File Preview</h2>
        <div class="file-meta">
        </div>
    </div>
    
    <div class="preview-content">
        @if ($isImage)
            <div class="image-preview-wrapper">
                <img src="{{ $filePath }}" class="file-preview" alt="Image Preview">
            </div>
            <a href="{{ $filePath }}" class="btn btn-download" download>
                <i class="fas fa-download"></i> Download Image
            </a>
            
        @elseif ($isPdf)
            <div class="pdf-preview-wrapper">
                <iframe src="{{ $pdfEmbedUrl }}" 
                        width="100%" 
                        height="500px"
                        class="pdf-iframe"
                        allowfullscreen></iframe>
            </div>
            
            <div class="download-actions" style="text-align:center;">
                <a href="{{ $filePath }}" class="btn btn-download" style="text-decoration:none;" download>
                    <i class="fas fa-download"></i> Download PDF
                </a>
                
                @if($isMobile)
                <div class="mobile-help-text">
                    <p>Having trouble viewing? Try downloading the file instead.</p>
                </div>
                @endif
            </div>
            
        @else
            <div class="unsupported-file">
                <i class="fas fa-file-excel"></i>
                <p>This file type cannot be previewed.</p>
            </div>
            <a href="{{ $filePath }}" class="btn btn-download" download>
                <i class="fas fa-download"></i> Download File
            </a>
        @endif
    </div>
</div>
@endsection