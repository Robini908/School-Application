@extends('layouts.login_master')

@section('content')
    <div class="page-content login-cover mb-4">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="container justify-content-center align-items-center col-md-4">

                <!-- registr card -->
                <form method="POST" action="{{ route('register') }}" class="text-black bg-white p-4">
                    <h3 class="text-center">Create Account</h3>
                    @csrf
                    <div class="form-group row">
                        
                        <input id="name" type="text" placeholder="Enter your Name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        
                    </div>

                    <div class="form-group row">                       
                        <input id="email" type="email" placeholder="Enter your email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif                      
                    </div>

                    <div class="form-group row">
                            <input id="password" type="password" placeholder="Enter your password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                    </div>

                    <div class="form-group row">                      
                            <input id="password-confirm" type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
                    </div>

                    <div class="form-group ">                        
                        <button type="submit" class="btn btn-md blogin form-control p-1">
                            {{ __('Register') }}
                        </button>                       
                    </div>
                </form>

            </div>


        </div>

    </div>
    @endsection
