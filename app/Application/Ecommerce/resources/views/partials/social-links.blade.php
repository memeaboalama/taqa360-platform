@php
    $socialIcons = [
        'facebook' => 'facebook',
        'twitter' => 'twitter',
        'instagram' => 'instagram',
        'linkedin' => 'linkedin',
        'youtube' => 'youtube',
        'whatsapp' => 'whatsapp',
    ];
    $socialLinks = [];
    foreach ($socialIcons as $network => $icon) {
        $url = data_get($footerData ?? [], 'social.' . $network);
        if (filled($url)) {
            $socialLinks[$network] = ['icon' => $icon, 'url' => $url];
        }
    }
    $socialContainerClass = $socialContainerClass ?? 'socials';
@endphp

@if($socialLinks)
    @if(filled($socialSectionTitle ?? null))
        <section class="contact-social-section">
            <h2>{{ $socialSectionTitle }}</h2>
    @endif
    <div class="{{ $socialContainerClass }}">
        @foreach($socialLinks as $network => $social)
            <a href="{{ $social['url'] }}" aria-label="{{ ucfirst($network) }}" target="_blank" rel="noopener noreferrer">
                <i class="fa fa-{{ $social['icon'] }}" aria-hidden="true"></i>
            </a>
        @endforeach
    </div>
    @if(filled($socialSectionTitle ?? null))
        </section>
    @endif
@endif
