<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EverPeak Camp Co.</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }
        .title {
            font-size: 64px;
            font-weight: 600;
        }
        .links a {
            margin: 0 10px;
            font-weight: 500;
            text-transform: uppercase;
            text-decoration: none;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Include Bootstrap Navbar -->
    @include('layouts.header')

    <div class="container mt-5 text-center">
        <h1 class="title mb-4">EverPeak Camp Co.</h1>
        <p class="lead">Your trusted source for outdoor & camping gears.</p>

        <div class="links mt-4">
            <a href="https://laravel.com/docs" class="btn btn-outline-secondary btn-sm">Documentation</a>
            <a href="https://laracasts.com" class="btn btn-outline-secondary btn-sm">Laracasts</a>
            <a href="https://laravel-news.com" class="btn btn-outline-secondary btn-sm">Laravel News</a>
            <a href="https://pulse.laravel.com" class="btn btn-outline-secondary btn-sm">Pulse</a>
            <a href="https://forge.laravel.com" class="btn btn-outline-secondary btn-sm">Forge</a>
            <a href="https://vapor.laravel.com" class="btn btn-outline-secondary btn-sm">Vapor</a>
            <a href="https://github.com/laravel/laravel" class="btn btn-outline-secondary btn-sm">GitHub</a>
        </div>
    </div>

    <!-- Bootstrap JS (for dropdowns, navbar toggler, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
