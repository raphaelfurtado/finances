@php
    $banner = \App\Models\AppSetting::getBanner();
@endphp

@if($banner)
    <div class="fixed top-0 left-0 right-0 z-50 px-4 py-3 text-center {{ 
            $banner['type'] === 'info' ? 'bg-blue-500 text-white' :
            ($banner['type'] === 'success' ? 'bg-green-500 text-white' :
                ($banner['type'] === 'warning' ? 'bg-yellow-500 text-gray-900' :
                    'bg-red-500 text-white')) 
        }}">
        <div class="max-w-md mx-auto flex items-center justify-center gap-2">
            @if($banner['type'] === 'info')
                <i data-lucide="info" class="w-5 h-5"></i>
            @elseif($banner['type'] === 'success')
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            @elseif($banner['type'] === 'warning')
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            @else
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
            @endif
            <p class="font-semibold text-sm">{{ $banner['message'] }}</p>
        </div>
    </div>

    <!-- Spacer to prevent content from being hidden behind the banner -->
    <div class="h-12"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
@endif