<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-head/>
    </head>
    <body class="antialiased">
        <x-navbar/>
        <div class="container">
            <h1>Map Not Found</h1>
            <p>The map you are looking for does not exist.</p>
        <x-footer/>
    </body>
</html>
