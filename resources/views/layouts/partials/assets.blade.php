@php
    $manifestPath = public_path('build/manifest.json');
    $useManifest = file_exists($manifestPath);
@endphp

@if ($useManifest)
    @php
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
        $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
    @endphp
    @if ($cssFile)
        <link rel="stylesheet" href="{{ asset('build/'.$cssFile) }}">
    @endif
    @if ($jsFile)
        <script type="module" src="{{ asset('build/'.$jsFile) }}" defer></script>
    @endif
@else
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
