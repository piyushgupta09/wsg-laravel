<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @yield('top')
        
    </head>

    <body>

        @yield('body')
        @yield('bottom')

        <noscript>
            <strong>JavaScript is required for this web application to function properly.</strong>
        </noscript>

    </body>

</html>


