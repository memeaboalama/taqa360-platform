<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>


    <title>@yield('title')</title>


    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">



    @livewireStyles
    @stack('css')

    <style>
        :root {
            --primary-color: #306FAA;
            --primary-hover: #245C8E;
            --light-bg-color: #F2F2F3;
            --dark-text-color: #333333;
            --primary-rgb: 48, 111, 170;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--light-bg-color);
            color: var(--dark-text-color);
        }

        .form-input {
            width: 100%;
            /* --- MODIFIED: Reduced vertical padding for smaller height --- */
            padding: 0.6rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.9rem; /* Slightly smaller font size */
            outline: none;
            transition: all 0.2s;
            background-color: #ffffff;
            color: var(--dark-text-color);
        }

        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(var(--primary-rgb), 0.5);
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--dark-text-color);
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            width: 100%;
            background-color: var(--primary-color);
            color: var(--dark-text-color);
            /* --- MODIFIED: Reduced vertical padding for smaller height --- */
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            outline: none;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background: var(--light-bg-color);
        }

        .card {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 28rem;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
        }

        a:hover {
            color: var(--primary-hover);
        }


        /* General Text & Link Colors */
        .text-gray-900 { color: var(--dark-text-color); }
        .text-gray-700 { color: #4b5563; } /* Kept for secondary text if needed */
        .text-gray-600 { color: #4b5563; }

        .text-blue-600, a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .hover\\:text-blue-500:hover, a:hover {
            color: var(--primary-hover);
        }

        /* Utility classes from original code - for compatibility */
        .rtl-support { direction: 'rtl'; text-align: right; }
        .text-red-500 { color: #ef4444; }
        .text-xs { font-size: 0.75rem; }
        .grid { display: grid; }
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .gap-4 { gap: 1rem; }
        @media (min-width: 768px) { .md\\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .mt-6 { margin-top: 1.5rem; }
        .text-center { text-align: center; }
        .font-medium { font-weight: 500; }
        .mb-2 { margin-bottom: 0.5rem; }
        .ml-2 { margin-left: 0.5rem; }
        .mr-2 { margin-right: 0.5rem; }
        .block { display: block; }
        .text-sm { font-size: 0.875rem; }
        .space-y-8 > * + * { margin-top: 2rem; }
        .space-y-5 > * + * { margin-top: 1.25rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .relative { position: relative; }
        .w-full { width: 100%; }
        .max-w-md { max-width: 28rem; }
        .h-4 { height: 1rem; }
        .w-4 { width: 1rem; }
        .focus\\:ring-2:focus { box-shadow: 0 0 0 2px rgba(var(--primary-rgb), 0.5); }
        .focus\\:ring-blue-500:focus { box-shadow: 0 0 0 2px rgba(var(--primary-rgb), 0.5); }
        .border-gray-300 { border-color: #d1d5db; }
        .rounded-lg { border-radius: 0.5rem; }
        .text-3xl { font-size: 1.875rem; }
        .font-bold { font-weight: 700; }

    </style>
</head>

<body class="bg-gray-50">
    @php $currentLocale = app()->getLocale(); $targetLocale = $currentLocale === 'ar' ? 'en' : 'ar'; @endphp
    <div class="fixed top-4 {{ $currentLocale === 'ar' ? 'left-4' : 'right-4' }} z-50">
        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($targetLocale) }}"
            class="flex items-center gap-2 bg-white border border-gray-300 rounded-full px-4 py-2 text-sm font-medium shadow-sm hover:shadow transition"
            title="{{ $currentLocale === 'ar' ? 'English' : 'العربية' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="2" y1="12" x2="22" y2="12"></line>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
            </svg>
            <span>{{ $currentLocale === 'ar' ? 'English' : 'العربية' }}</span>
        </a>
    </div>
    @yield('content')


    @livewireScripts

    @stack('scripts')
</body>
</html>
