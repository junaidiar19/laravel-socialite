@extends('layouts.app')

@section('content')

<style>
    .text-line {
    overflow: hidden;
    text-align: center;
    }

    .text-line:before,
    .text-line:after {
    background-color: gray;
    content: "";
    display: inline-block;
    height: 0.5px;
    position: relative;
    vertical-align: middle;
    width: 50%;
    }

    .text-line:before {
    right: 0.5em;
    margin-left: -50%;
    }

    .text-line:after {
    left: 0.5em;
    margin-right: -50%;
    }

</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    @error('error')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mb-3">
                            {{ __('Login') }}
                        </button>
                    </form>

                    <p class="text-line">or</p>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <a href="{{ route('social.oauth', 'google') }}" class="btn btn-danger btn-block">Google</a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('social.oauth', 'facebook') }}" class="btn btn-primary btn-block">Facebook</a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('guruinovatif.login') }}" class="btn btn-success btn-block">Guruinovatif</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
