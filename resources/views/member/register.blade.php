@extends('layout.bgregister')

@section('content')
    

                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="First Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password2">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Repeat Password">
                                    </div>
                                </div>

                                <!-- <div class="form-group row">
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
                                </div> -->

                                <a href="/member/login" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </a>
                                <hr>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.html">Already have an account? Login!</a>
                            </div>

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
