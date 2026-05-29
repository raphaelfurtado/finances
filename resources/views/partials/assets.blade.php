@php($cssVersion = @filemtime(public_path('css/app.css')) ?: '1')
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ $cssVersion }}">
<script src="{{ asset('js/lucide.min.js') }}"></script>
