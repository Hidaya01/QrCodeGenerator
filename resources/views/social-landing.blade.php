@extends('layouts.app')

@section('content')
<div class="social-landing-container">
    <div class="social-landing-card">
        <h1>Connect With Us</h1>
        
        <div class="website-link">
            <a href="{{ $socialData['website'] }}" target="_blank">
                <i class="fas fa-globe"></i> Visit our website
            </a>
        </div>
        
        <div class="social-links">
            @foreach($socialData['platforms'] as $platform)
                <a href="{{ $platform['url'] }}" target="_blank" class="social-link {{ $platform['type'] }}">
                    <i class="fab fa-{{ $platform['type'] }}"></i>
                    <span>{{ $platform['text'] ?? 'Visit our '.$platform['type'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<style>
.social-landing-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.social-landing-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 2rem;
    text-align: center;
}

.website-link {
    margin: 2rem 0;
}

.website-link a {
    display: inline-block;
    padding: 1rem 2rem;
    background: #4a6bff;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-size: 1.1rem;
}

.social-links {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    border-radius: 4px;
    text-decoration: none;
    color: white;
    font-size: 1.1rem;
    border-radius:10px;
}

.social-link i {
    margin-right: 0.5rem;
    font-size: 1.3rem;
}

.facebook { background: #3b5998; }
.youtube { background: #ff0000; }
.instagram {  background: linear-gradient(to right,#833ab4,#fd1d1d,#fcb045 );}
.twitter { background: #1da1f2; }
.linkedin { background: #0077b5; }
</style>
@endsection