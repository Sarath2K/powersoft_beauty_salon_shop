@extends('layouts.header')
@section('master-content')
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img src="{{asset('../../assets/images/logo.svg')}}">
                            </div>
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')"/>

                            <form method="POST" class="pt-3" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group">
                                    <input type="email" class="form-control form-control-lg"
                                           id="email"
                                           name="email"
                                           placeholder="Username"
                                           value="{{ old('email') }}" required
                                           autofocus autocomplete="username"/>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg"
                                           id="password"
                                           name="password"
                                           placeholder="Password"
                                           required autocomplete="current-password"/>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                                </div>

                                <div class="mt-3">
                                    <button
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn"
                                        type="submit">SIGN IN
                                    </button>
                                </div>

                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label for="remember_me" class="form-check-label text-muted">
                                            <input type="checkbox"
                                                   class="form-check-input" id="remember_me" name="remember">
                                            {{ __('Remember me') }}
                                        </label>
                                    </div>

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="auth-link text-black">Forgot
                                            password?</a>
                                    @endif
                                </div>

                                <div class="text-center mt-4 font-weight-light"> Don't have an account? <a
                                        href="{{ route('register') }}" class="text-primary">Create</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
@endsection
