<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
    @livewireStyles
    <!-- Styles -->
</head>
<body class="antialiased">
<div id="app"
    class="">
        <livewire:follow-towing-order />


</div>
@livewireScripts
@vite(['resources/css/app.css', 'resources/js/app.js'])

</body>
</html>
