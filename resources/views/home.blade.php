<!doctype html>

<html lang="{{ env('APP_LOCALE') }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="theme-color" content="#FFF">
        <meta name="min-date" content="{{ $minDate }}">
        <meta name="max-date" content="{{ $maxDate }}">
        <link rel="icon" href="logo-large.png" />
        <link rel="manifest" href="/manifest.json">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <title>{{ env('APP_NAME') }}</title>
    </head>

    <body class="bg-light">

        <div id="app">
            <top-navbar></top-navbar>
            <transition
                mode="out-in"
                name="fade"
                appear
            >
                <router-view></router-view>
            </transition>
        </div>

        <script src="{{ mix('/js/app.js') }}"></script>

    </body>

</html>
