<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="Content-Security-Policy" content="block-all-mixed-content">
    <meta name="Author" content="">
    <meta name="keywords"
        content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Crop Image Css -->
    <link rel="icon" href="{{ asset('assets/ecommerce/images/favicon.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles

    @stack('css')
    <style>
        :root {
            --primary-color: #306FAA;
            --primary-hover: #245C8E;

            --primary-rgb: {
                    {
                    config('app.primary_rgp', '48, 111, 170')
                }
            }

            ;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f9fafb;
        }

        .rtl-support {
            direction: 'rtl';
            text-align: right;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            direction: 'rtl';
            text-align: right;
        }

        .btn-primary {
            width: 100%;
            background-color: #306FAA;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            outline: none;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #245C8E;
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f7ff 100%);
        }

        .card {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 28rem;
        }

        .language-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

        .language-btn {
            padding: 0.25rem 0.75rem;
            background-color: #e5e7eb;
            color: #374151;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }

        .language-btn:hover {
            background-color: #d1d5db;
        }

        .terms-label {
            display: flex;
            align-items: center;
            direction: 'rtl';
            text-align: right;
        }

        .text-red-500 {
            color: #ef4444;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .gap-4 {
            gap: 1rem;
        }

        .md\\:grid-cols-2 {
            @media (min-width: 768px) {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .mt-6 {
            margin-top: 1.5rem;
        }

        .text-center {
            text-align: center;
        }

        .text-gray-600 {
            color: #4b5563;
        }

        .text-blue-600 {
            color: #245C8E;
        }

        .hover\\:text-blue-500:hover {
            color: #306FAA;
        }

        .font-medium {
            font-weight: 500;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .ml-2 {
            margin-left: 0.5rem;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .block {
            display: block;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-gray-700 {
            color: #374151;
        }

        .space-y-8>*+* {
            margin-top: 2rem;
        }

        .space-y-5>*+* {
            margin-top: 1.25rem;
        }

        .space-y-4>*+* {
            margin-top: 1rem;
        }

        .relative {
            position: relative;
        }

        .w-full {
            width: 100%;
        }

        .max-w-md {
            max-width: 28rem;
        }

        .h-4 {
            height: 1rem;
        }

        .w-4 {
            width: 1rem;
        }

        .focus\\:ring-2:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        .focus\\:ring-blue-500:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        .border-gray-300 {
            border-color: #d1d5db;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .text-3xl {
            font-size: 1.875rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-gray-900 {
            color: #111827;
        }
    </style>
</head>