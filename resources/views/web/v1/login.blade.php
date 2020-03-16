@extends('front.v1.layouts.basic')

@section('container')

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                        </div>
                                        <form class="user">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck">
                                                    <label class="custom-control-label" for="customCheck">Remember Me</label>
                                                </div>
                                            </div>
                                            <a href="javascript:;" class="btn btn-primary btn-user btn-block" id="link_go_login">Login</a>
                                            <hr>
                                            <a href="javascript:;" class="btn btn-google btn-user btn-block"><i class="fab fa-google fa-fw"></i> Login with Google</a>
                                            <a href="javascript:;" class="btn btn-facebook btn-user btn-block"><i class="fab fa-facebook-f fa-fw"></i> Login with Facebook</a>
                                        </form>
                                        <hr>
                                        <div class="text-center"><a class="small" href="javascript:;">Forgot Password?</a></div>
                                        <div class="text-center"><a class="small" href="javascript:;">Create an Account!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

@endsection


@section('page_script')

<script type="text/javascript">
    $(document).ready(function(){
        $("#link_go_login").click(function(){
            commonAjaxFunc({
                'method' : 'post',
                'url' : '/login',
                'data' : {
                    'user_email' : 'psmever@gmail.com',
                    'user_password' : 'alsrns78',
                }
            });
        });
    });
</script>

@endsection