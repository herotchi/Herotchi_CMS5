<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Scripts -->
        {{--@vite(['resources/css/app.css', 'resources/js/app.js'])--}}
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </head>
    <body class="bg-body-tertiary h-100">
        <div class="">
            @if (auth('admin')->check())
                @include('layouts.admin-navigation')
            @elseif(auth()->check())
                @include('layouts.app-navigation')
            @endif
        </div>
        <div {{ $attributes->merge(['class' => 'container']) }}>
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            @include('layouts.footer')
            <x-toasts />
        </div>
    </body>
    {{--<body class="">
        <div class="">
            @include('layouts.app-navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="">
                    <div class="">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>--}}
</html>
