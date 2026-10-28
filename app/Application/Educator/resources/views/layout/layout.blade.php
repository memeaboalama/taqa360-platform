<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <title>@yield('title', 'Educator Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons Css (Remix Icon) -->
    <link href="{{ asset('assets/vendor/css/icons.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/ecommerce/images/favicon.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles
    @stack('css')

    <style>
        :root {
            --background-hsl: 240 2% 95%;
            /* #F2F2F3 */
            --foreground-hsl: 0 0% 20%;
            /* #333333 */
            --primary-hsl: 210 56% 43%;
            /* #306FAA */
            --primary-foreground-hsl: 0 0% 100%;
            --border-hsl: 220 13% 91%;
            --card-hsl: 0 0% 100%;
            --primary-rgb: 48, 111, 170;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: hsl(var(--background-hsl));
            color: hsl(var(--foreground-hsl));
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        border: 'hsl(var(--border-hsl))',
                        background: 'hsl(var(--background-hsl))',
                        foreground: 'hsl(var(--foreground-hsl))',
                        card: {
                            DEFAULT: 'hsl(var(--card-hsl))',
                            foreground: 'hsl(var(--foreground-hsl))'
                        },
                        primary: {
                            DEFAULT: 'hsl(var(--primary-hsl))',
                            foreground: 'hsl(var(--primary-foreground-hsl))'
                        },
                    }
                }
            }
        }
    </script>
</head>

<body class="antialiased">
    @if(session('login_welcome'))
    <div id="page-loader" style="position: fixed; inset: 0; z-index: 999999; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <span style="width: 80px; height: 80px; background: rgba(var(--primary-rgb), 0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="rgb(var(--primary-rgb))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                <line x1="15" y1="9" x2="15.01" y2="9"></line>
            </svg>
        </span>
        <p class="font-bold mb-3" style="color: #000;">{{ __('educator::auth.loading_dashboard_msg') }}</p>
        <div class="w-8 h-8 border-4 border-gray-200 border-t-transparent rounded-full animate-spin" style="border-top-color: rgb(var(--primary-rgb));"></div>
    </div>
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                var loader = document.getElementById('page-loader');
                if (loader) {
                    loader.style.transition = 'opacity 0.4s ease';
                    loader.style.opacity = '0';
                    setTimeout(function() {
                        if (loader.parentNode) loader.parentNode.removeChild(loader);
                    }, 450);
                }
            }, 500);
        });
    </script>
    @endif
    @yield('content')

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
    @stack('js')
</body>

</html>