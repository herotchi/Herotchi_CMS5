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
        <style>
            body {
                padding-top: 40px;
                padding-bottom: 40px;
            }
            .no-header {
                width: 100%;
                max-width: 480px;
                padding: 15px;
                margin: auto;
        }
        </style>

        <!-- Scripts -->
        {{--@vite(['resources/css/app.css', 'resources/js/app.js'])--}}
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </head>
    <body class="bg-body-tertiary h-100">
        {{--<div class="">
            <div>
                <a href="/">
                    <x-application-logo class="" />
                </a>
            </div>

            <div class="">
                {{ $slot }}
            </div>
        </div>--}}
        <div class="container">
            <main>
                <div class="no-header">
                    <div class="text-center">
                        <x-application-logo class="my-4" alt="" width="57" height="57" />
                    </div>
                    {{ $slot }}
                </div>
            </main>
            <x-toasts />
        </div>
    </body>
</html>
