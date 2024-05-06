<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-head/>
    </head>
    <body class="antialiased">
        <x-navbar/>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Login</h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="emailInput1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="emailInput1" placeholder="someone@example.com" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        </div>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="mb-3">
                            <label for="passwordInput1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="passwordInput1" name="password" required autocomplete="current-password">
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <x-footer/>
    </body>
</html>
