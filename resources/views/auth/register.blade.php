@extends('auth.master-auth')

@section('title')
    <title>Register</title>
@endsection

@section('body')
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Create Account</h3></div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('do-register') }}">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input required class="form-control" id="inputFirstName" name="user_name" type="text" placeholder="Enter your first name" />
                                                    <label for="inputFirstName">First name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input required class="form-control" id="inputLastName" name="user_name_last" type="text" placeholder="Enter your last name" />
                                                    <label for="inputLastName">Last name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input required onchange="emailChecking()" class="form-control" id="inputEmail" name="user_email" type="email" placeholder="name@example.com" />
                                            <label for="inputEmail">Email address</label>
                                            <small id="email-message" class="form-text"></small>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input required class="form-control" id="inputPassword" name="user_password" type="password" placeholder="Create a password" />
                                                    <label for="inputPassword">Password</label>
                                                </div>
                                                <small id="password-message" class="form-text"></small>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input required onkeyup="passChecking()" class="form-control" id="inputPasswordConfirm" name="password_confirmation" type="password" placeholder="Confirm password" />
                                                    <label for="inputPasswordConfirm">Confirm Password</label>
                                                </div>
                                                <small id="password-match-message" class="form-text"></small>
                                            </div>
                                        </div>
                                        <div class="mt-4 mb-0">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary btn-block" id="btn-register" disabled>Create Account</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="{{route('login')}}">Have an account? Go to login</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Abi Sarirayndra {{\Carbon\Carbon::now()->isoFormat('Y')}}</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function passChecking(){
            var password = $('#inputPassword').val();
            var confPassword = $('#inputPasswordConfirm').val();
            if (password !== confPassword) {
                $('#password-match-message').text('Unmatch Password!').css('color', 'red');
                $('#btn-register').prop('disabled', true);
            } else {
                $('#password-match-message').text('Password Match').css('color', 'green');
                $('#btn-register').prop('disabled', false);
            }
            
        }

        function emailChecking(){
            var email = $('#inputEmail').val();
            $.ajax({
                url: "{{ route('emailChecking') }}",
                type: "POST",
                data: {
                    user_email: email,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.exists){
                        $('#email-message').text('Email sudah digunakan').css('color', 'red');
                        $('#btn-register').prop('disabled', true);
                    } else {
                        $('#email-message').text('Email tersedia').css('color', 'green');
                        $('#btn-register').prop('disabled', false);
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
@endsection
        
