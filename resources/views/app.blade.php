<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta property="og:site_name" content="Mystery Fun Fest" />
        <meta property="og:title" content="{{ $title ?? 'Mystery Fun Fest' }}" />
        <meta property="og:description" content="{{ $description ?? 'Mystery Fun Fest is a cooperative event in which two teams compete to accrue the most tickets throughout its 24-day duration.' }}" />
        <meta property="og:image" content="{{ $image ?? asset('/img/home.jpg') }}" />

        <link rel="icon" type="image/png" href="/img/favicon.png">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        @inertia
    </body>
</html>
