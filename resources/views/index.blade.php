<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-head/>
    </head>
    <body class="antialiased">
        <x-navbar/>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Welcome!</h1>
                    <p>Please login or register to continue. Alternatively, request a map link from an existing user...</p>
                </div>
            </div>
        <x-footer/>
    </body>
</html>
