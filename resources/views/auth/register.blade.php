@extends('layout.bgregister')

@section('content')
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

    <div class="container">
        <h3 class="heading" style="text-align: center; margin-bottom:20px;">Register Account</h3>

        <!-- Your form code here -->
        <form class="user" method="POST" action="{{ route('register') }}">
            <!-- Form fields -->
        </form>
    </div>

    <style>
        .container {
            align-items: center; /* Align items horizontally in the center */
        }

        .heading {
            margin-bottom: 20px; /* Add some space between the heading and the form */
        }
    </style>

    <form class="user" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
                <input class="form-control form-control-user" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Name">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control form-control-user" type="email" name="email" value="{{ old('email') }}" required placeholder="Email">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input class="form-control form-control-user" type="password" name="password" required autocomplete="new-password" placeholder="Password">
            </div>
            <div class="col-sm-6">
                <input class="form-control form-control-user" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="adminCheckbox" name="adminCheckbox">
                    <label class="form-check-label" for="adminCheckbox">
                    Are you an admin?
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group row" id="adminPasswordField" style="display: none;">
            <div class="col-sm-6">
                <input class="form-control form-control-user" type="password" name="adminPassword" placeholder="Admin Password">
            </div>
        </div>




        <!-- <div>
            <label>{{ __('Name') }}</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
        </div>

        <div>
            <label>{{ __('Email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" required />
        </div>

        <div>
            <label>{{ __('Password') }}</label>
            <input type="password" name="password" required autocomplete="new-password" />
        </div>

        <div>
            <label>{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password" />
        </div> -->

        <!-- <a href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a> -->

        <div>
            <button type="submit" class="btn btn-primary btn-user btn-block">
                {{ __('Register') }}
            </button>
        </div>

    <hr>

        <div class="text-center">
            <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
        </div>

    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const adminCheckbox = document.getElementById('adminCheckbox');
        const adminPasswordField = document.getElementById('adminPasswordField');

        adminCheckbox.addEventListener('change', function() {
            if (this.checked) {
                adminPasswordField.style.display = 'block';
            } else {
                adminPasswordField.style.display = 'none';
            }
        });
    });
</script>
@endsection
