@extends('layout.bglogin')

@section('content')
    @if (session('status'))
        <div>
            {{ session('status') }} 
        </div>
    @endif

    @if ($errors->any())
        <div>
            <div>{{ __('Whoops! Something went wrong.') }}</div>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="user" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <input class="form-control form-control-user" type="email" name="email" value="{{ old('email') }}" required autofocus aria-describedby="emailHelp" placeholder="Enter Email Address..."/>
        </div>

        <div class="form-group">
            <input class="form-control form-control-user" type="password" name="password" required autocomplete="current-password" placeholder="Enter Password"/>
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox small">
                <input type="checkbox" class="custom-control-input" name="remember" id="customCheck">
                <label class="custom-control-label" for="customCheck">Remember Me</label>
            </div>
        </div>

        <div>
            <button type="submit" class="btn btn-primary btn-user btn-block">
               {{ __('Login') }} 
            </button>
        </div>

        <hr>
                                    

        <!-- <div>
            <label>{{ __('Remember me') }}</label>
            <input type="checkbox" name="remember">
        </div> -->

        @if (Route::has('password.request'))
            <div class="text-center">
                <a class="small" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            </div>
        @endif

        <div class="text-center">
            <a class="small" href="/register">Create an Account!</a>
        </div>

    </form>
@endsection
