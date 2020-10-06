<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@yield("title")</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="{{ url('/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ url('/') }}/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ url('/') }}/bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ url('/') }}/dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ url('/') }}/plugins/iCheck/square/blue.css">

        <!-- my style -->
        <link rel="stylesheet" href="{{ url('/') }}/css/w3.css">
        <link rel="stylesheet" href="{{ url('/') }}/css/animate.css">
        <link rel="stylesheet" href="{{ url('/') }}/css/iziToast.min.css">
        <link rel="stylesheet" href="{{ url('/') }}/css/app.css">
        <!-- Google Font -->

        {!! view("template.commen") !!}
        <script>
            var public_path = '{{ url("/") }}';
        </script>

        <style>
            body {
                background-image: url('{{ url('/') }}/image/login.jpg')!important;
                background-size: cover!important;
            }

        </style>
    </head>
    <body class="hold-transition login-page w3-light-gray" style="overflow: hidden">
        <div class="login-box" style="margin-left: 5%" >
            <!-- /.login-logo -->
            <div class="login-box-body w3-card w3-animate-left w3-light-gray" >
                    <div class="login-logo">
                            @yield("logo")
                        <a href="#"><b>@yield("title")</a>
                    </div>
                    <br>
                <p class="login-box-msg">@yield("subtitle")</p>

                <form action="{{ $route }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group has-feedback">
                        <input type="text" name="email" class="form-control" placeholder="البريد الالكترونى">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" class="form-control" placeholder="كلمة المرور">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <br>
                    <div class="">
                        <!-- /.col -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">تسجيل الدخول</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery 3 -->
        <script src="{{ url('/') }}/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{ url('/') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="{{ url('/') }}/plugins/iCheck/icheck.min.js"></script>
        <script src="{{ url('/') }}/plugins/iCheck/icheck.min.js"></script>
        <script src="{{ url('/') }}/js/iziToast.min.js"></script>
        <script src="{{ url('/') }}/dist/js/demo.js"></script>
        <script src="{{ url('/') }}/js/app.js"></script>

        {!! view("template.msg") !!}
    </body>
</html>
