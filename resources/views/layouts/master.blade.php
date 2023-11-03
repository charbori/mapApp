<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Rental your product</title>

        <link href="https://unpkg.com/tailwindcss@1.2.0/dist/tailwind.min.css" rel="stylesheet">
        @vite(['resources/css/master.css'])
        <!-- Custom styles for this template -->
        @yield('style')
        @vite(['resources/js/app.js'])
        @vite(['resources/css/app.css'])
    </head>
    <body>
        @if (getMobile())
            @include('navigations.masternav_mo')
        @else
            @include('navigations.masternav')
        @endif
        @include('flash::message')
        @if (trim(Request::path()) === 'home')
            @include('layouts.main')
        @else
            @yield('contents')
        @endif
        @yield('javascript')
    </body>
</html>
