@extends('layouts.master2')

@section('title')
    {{ __('auth.login_title') }}
@stop

@section('css')
<link href="{{URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css')}}" rel="stylesheet">
<style>
    .full-height {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
    }
    .login-container {
        width: 100%;
        max-width: 550px;
        margin: 0 50px;
    }
    .alert {
        padding: 15px;
        border-radius: 5px;
        font-size: 16px;
        position: relative;
    }
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    .close {
        position: absolute;
        top: 5px;
        right: 10px;
        color: inherit;
        font-size: 1.5rem;
        font-weight: bold;
    }
    .form-check-input {
        width: 20px;
        height: 20px;
        margin-right: -30px;
    }
    .form-check-label {
        font-size: 19px;
        color: #333;
    }
    .form-check {
        display: flex;
        align-items: center;
    }
</style>
@endsection

@section('content')

<div class="container-fluid full-height">
    <div class="login-container">
        <div class="card-sigin border-0 shadow-lg rounded-lg">
            <div class="mb-4 d-flex align-items-center justify-content-center">
                <h1 class="main-logo1 ml-2 my-auto tx-30 text-primary"> Nine9x.Pro </h1>
            </div>
            <div class="card-body p-5">
                <div class="main-signup-header">
                    <h2 class="text-center text-dark">{{ __('auth.welcome') }}</h2>
                    <br>
                    <h5 class="font-weight-semibold text-center">{{ __('auth.login_now') }}</h5>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ __('auth.done') }}</strong> {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ __('auth.error') }}</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="font-weight-bold">{{ __('auth.username') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="{{ __('auth.enter_username') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="font-weight-bold">{{ __('auth.password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password"
                                   placeholder="{{ __('auth.enter_password') }}">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group row mb-4">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label ms-3" for="remember">
                                        {{ __('auth.remember_me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block py-2">
                            {{ __('auth.login') }}
                        </button>

                        <div class="text-center mt-4">
                            <a href="{{ route('password.request') }}" class="text-dark">
                                {{ __('auth.forgot_password') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert").fadeOut("slow");
        }, 5000);
    });
</script>
@endsection
