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
                    <h1>Register</h1>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nameInput1" class="form-label">Name</label>
                            <input type="text" class="form-control" id="nameInput1" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        </div>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                        <div class="mb-3">
                            <label for="emailInput1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="emailInput1" placeholder="name@example.com" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        </div>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="mb-3">
                            <label for="passwordInput1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="passwordInput1" name="password" required autocomplete="new-password">
                        </div>

                        <div class="mb-3">
                            <label for="passwordInput2" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="passwordInput2" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <x-footer/>
    </body>
</html>
